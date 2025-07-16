<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Course and category data handler class for theme_nice.
 *
 * This class provides helper methods to retrieve detailed information
 * about courses and categories used by the Nice theme’s custom blocks
 * and UI components.
 *
 * @package     theme_nice
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot. '/course/renderer.php');
require_once($CFG->dirroot . '/course/lib.php');

/**
 * Class niceCourseHandler
 *
 * Handles fetching course and category data for theme_nice.
 *
 * @package theme_nice
 */
class niceCourseHandler {

    /**
     * Fetches detailed course information by course ID.
     *
     * Retrieves various details about a course, including names, summary,
     * category, and other properties. Returns null if the course does not exist.
     *
     * @param int $courseid The ID of the course to retrieve.
     * @return stdClass|null An object containing course details, or null if course not found.
     */
    public function nicegetcoursedetails($courseid) {
        global $CFG, $COURSE, $USER, $DB, $SESSION, $SITE, $PAGE, $OUTPUT;

        $courseid = (int)$courseid;

        // Check if the course record exists.
        if ($DB->record_exists('course', ['id' => $courseid])) {

            // Prepare helper classes.
            $nicecourse = new \stdClass();
            $chelper = new coursecat_helper();
            $coursecontext = context_course::instance($courseid);

            $courserecord = $DB->get_record('course', ['id' => $courseid]);
            $courseelement = new core_course_list_element($courserecord);

            // Extract course details.
            $courseid = $courserecord->id;
            $courseshortname = $courserecord->shortname;
            $coursefullname = $courserecord->fullname;
            $coursesummary = $chelper->get_course_formatted_summary($courseelement, ['noclean' => true, 'para' => false]);
            $courseformat = $courserecord->format;
            $courseannouncements = $courserecord->newsitems;
            $coursestartdate = $courserecord->startdate;
            $courseenddate = $courserecord->enddate;
            $coursevisible = $courserecord->visible;
            $coursecreated = $courserecord->timecreated;
            $courseupdated = $courserecord->timemodified;
            $courserequested = $courserecord->requested;
            $courseenrolmentcount = count_enrolled_users($coursecontext);
            $courseisenrolled = is_enrolled($coursecontext, $USER->id, '', true);

            // Fetch category info.
            $categoryid = $courserecord->category;

            try {
                $coursecategory = core_course_category::get($categoryid);
                $categoryname = $coursecategory->get_formatted_name();
                $categoryurl = $CFG->wwwroot . '/course/index.php?categoryid='.$categoryid;
            } catch (Exception $e) {
                $coursecategory = "";
                $categoryname = "";
                $categoryurl = "";
            }

            // Generate enrolment and course URLs.
            $enrolmentlink = $CFG->wwwroot . '/enrol/index.php?id=' . $courseid;
            $courseurl = new moodle_url('/course/view.php', ['id' => $courseid]);

            // Check for payment options (e.g. PayPal).
            $enrolinstances = enrol_get_instances($courseid, true);

            foreach ($enrolinstances as $singleenrolinstances) {
                if ($singleenrolinstances->enrol == 'paypal') {
                    $courseprice = $singleenrolinstances->cost;
                    $coursecurrency = $singleenrolinstances->currency;
                } else {
                    $courseprice = '';
                    $coursecurrency = '';
                }
            }

            $nicecoursecontacts = [];
            if ($courseelement->has_course_contacts()) {
                foreach ($courseelement->get_course_contacts() as $key => $coursecontact) {
                    $nicecoursecontacts[$key] = new \stdClass();
                    $nicecoursecontacts[$key]->userId = $coursecontact['user']->id;
                    $nicecoursecontacts[$key]->username = $coursecontact['user']->username;
                    $nicecoursecontacts[$key]->name = $coursecontact['user']->firstname . ' ' . $coursecontact['user']->lastname;
                    $nicecoursecontacts[$key]->role = $coursecontact['role']->displayname;
                    $nicecoursecontacts[$key]->profileUrl = new moodle_url(
                        '/user/view.php',
                        [
                            'id' => $coursecontact['user']->id,
                            'course' => SITEID,
                        ]
                    );
                }
            }

            // Process course overview image.
            $contentimages = $contentfiles = $CFG->wwwroot . '/theme/nice/images/niceBg.png';
            foreach ($courseelement->get_course_overviewfiles() as $file) {
                $isimage = $file->is_valid_image();
                $url = file_encode_url("{$CFG->wwwroot}/pluginfile.php",
                    '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                    $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
                if ($isimage) {
                    $contentimages = $url;
                }
            }

            // Map data to niceCourse object.
            $nicecourse->courseId = $courseid;
            $nicecourse->enrolments = $courseenrolmentcount;
            $nicecourse->categoryId = $categoryid;
            $nicecourse->categoryName = $categoryname;
            $nicecourse->categoryUrl = $categoryurl;
            $nicecourse->shortName = format_text($courseshortname, FORMAT_HTML, ['filter' => true]);
            $nicecourse->fullName = format_text($coursefullname, FORMAT_HTML, ['filter' => true]);
            $nicecourse->summary = $coursesummary;
            $nicecourse->imageUrl = $contentimages;
            $nicecourse->format = $courseformat;
            $nicecourse->announcements = $courseannouncements;
            $nicecourse->startDate = userdate($coursestartdate, get_string('strftimedatefullshort', 'langconfig'));
            $nicecourse->endDate = userdate($courseenddate, get_string('strftimedatefullshort', 'langconfig'));
            $nicecourse->visible = $coursevisible;
            $nicecourse->created = userdate($coursecreated, get_string('strftimedatefullshort', 'langconfig'));
            $nicecourse->updated = userdate($courseupdated, get_string('strftimedatefullshort', 'langconfig'));
            $nicecourse->requested = $courserequested;
            $nicecourse->enrolmentLink = $enrolmentlink;
            $nicecourse->url = $courseurl;
            $nicecourse->teachers = $nicecoursecontacts;
            $nicecourse->course_price = $courseprice;
            $nicecourse->course_currency = $coursecurrency;
            $nicecourse->course_is_enrolled = $courseisenrolled;

            // Prepare a render object with minimal HTML.
            $nicerender = new \stdClass();
            $nicerender->updatedDate        = userdate($courseupdated, get_string('strftimedatefullshort', 'langconfig'));
            $nicerender->title              = '<h3><a href="'. $nicecourse->url .'">'. $nicecourse->fullName .'</a></h3>';
            $nicerender->fullName           = $nicecourse->fullName;
            $nicerender->url                = $nicecourse->url;
            $nicerender->courseImage        = '<img class="" src="'. $contentimages .'" alt="'.$nicecourse->fullName.'">';
            $nicerender->ImageUrl = $contentimages;
            /* @niceBreak */
            $nicecourse->niceRender = $nicerender;
            return $nicecourse;
        }
        return null;
    }

    /**
     * Fetches a course's formatted summary text.
     *
     * Retrieves the formatted summary for a given course. Optionally truncates
     * the summary to a specified maximum length.
     *
     * @param int $courseid The course ID.
     * @param int|null $maxlength Optional maximum length for the summary text.
     * @return string|null Formatted summary text, or null if unavailable.
     */
    public function nicegetcoursedescription($courseid, $maxlength) {
        global $CFG, $COURSE, $USER, $DB, $SESSION, $SITE, $PAGE, $OUTPUT;

        if ($DB->record_exists('course', ['id' => $courseid])) {
            $chelper = new coursecat_helper();
            $coursecontext = context_course::instance($courseid);

            $courserecord = $DB->get_record('course', ['id' => $courseid]);
            $courseelement = new core_course_list_element($courserecord);

            if ($courseelement->has_summary()) {
                $coursesummary = $chelper->get_course_formatted_summary($courseelement, ['noclean' => false, 'para' => false]);
                if ($maxlength != null) {
                    if (strlen($coursesummary) > $maxlength) {
                        $coursesummary = wordwrap($coursesummary, $maxlength);
                        $coursesummary = substr($coursesummary, 0, strpos($coursesummary, "\n")) . ' ...';
                    }
                }
                return $coursesummary;
            }

        }
        return null;
    }

    /**
     * Lists all course categories and their names.
     *
     * @return array Array of category names indexed by category ID.
     */
    public function nicelistcategories() {
        global $DB, $CFG;
        $topcategory = core_course_category::top();
        $topcategorykids = $topcategory->get_children();
        $areanames = [];
        foreach ($topcategorykids as $areaid => $topcategorykids) {
            $areanames[$areaid] = $topcategorykids->get_formatted_name();
            foreach ($topcategorykids->get_children() as $k => $child) {
                $areanames[$k] = $child->get_formatted_name();
            }
        }
        return $areanames;
    }

    /**
     * Fetches detailed category information by category ID.
     *
     * Retrieves details for a specific course category.
     *
     * @param int $categoryid The category ID.
     * @return stdClass|null Category details object or null if not found.
     */
    public function nicegetcategorydetails($categoryid) {
        global $CFG, $COURSE, $USER, $DB, $SESSION, $SITE, $PAGE, $OUTPUT;

        if ($DB->record_exists('course_categories', ['id' => $categoryid])) {

            $categoryrecord = $DB->get_record('course_categories', ['id' => $categoryid]);

            $chelper = new coursecat_helper();
            $categoryobject = core_course_category::get($categoryid);

            $nicecategory = new \stdClass();

            $categoryid = $categoryrecord->id;
            $categoryname = format_text($categoryrecord->name, FORMAT_HTML, ['filter' => true]);
            $categorydescription = $chelper->get_category_formatted_description($categoryobject);

            $categorysummary = format_string($categoryrecord->description, $striplinks = true, $options = null);
            $isvisible = $categoryrecord->visible;
            $categoryurl = $CFG->wwwroot . '/course/index.php?categoryid=' . $categoryid;
            $categorycourses = $categoryobject->get_courses();
            $categorycoursescount = count($categorycourses);

            // Fetch subcategories.
            $categorygetsubcategories = [];
            $categorysubcategories = [];
            if (!$chelper->get_categories_display_option('nodisplay')) {
                $categorygetsubcategories = $categoryobject->get_children($chelper->get_categories_display_options());
            }
            foreach ($categorygetsubcategories as $k => $nicesubcategory) {
                $nicesubcat = new \stdClass();
                $nicesubcat->id = $nicesubcategory->id;
                $nicesubcat->name = $nicesubcategory->name;
                $nicesubcat->description = $nicesubcategory->description;
                $nicesubcat->depth = $nicesubcategory->depth;
                $nicesubcat->coursecount = $nicesubcategory->coursecount;
                $categorysubcategories[$nicesubcategory->id] = $nicesubcat;
            }

            $categorysubcategoriescount = count($categorysubcategories);

            // Attempt to extract an image from the category description HTML.
            $outputimage = '';
            $description = $chelper->get_category_formatted_description($categoryobject);
            $src = "";
            if ($description) {
                $dom = new DOMDocument();
                $dom->loadHTML($description);
                $xpath = new DOMXPath($dom);
                $src = $xpath->evaluate("string(//img/@src)");
            }
            if ($src && $description) {
                $outputimage = $src;
            } else {
                // Fallback: grab the first course overview image in the category.
                foreach ($categorycourses as $childcourse) {
                    if ($childcourse === reset($categorycourses)) {
                        foreach ($childcourse->get_course_overviewfiles() as $file) {
                            if ($file->is_valid_image()) {
                                $imagepath = '/' . $file->get_contextid() . '/' . $file->get_component() . '/' .
                                    $file->get_filearea() . $file->get_filepath() . $file->get_filename();
                                $imageurl = file_encode_url(
                                    $CFG->wwwroot . '/pluginfile.php',
                                    $imagepath,
                                    false
                                );
                                $outputimage = $imageurl;
                                // Use the first image found.
                                break;
                            }
                        }
                    }
                }
            }

            // Map category details to the object.
            $nicecategory->categoryId = $categoryid;
            $nicecategory->categoryName = $categoryname;
            $nicecategory->categoryDescription = $categorydescription;
            $nicecategory->categorySummary = $categorysummary;
            $nicecategory->isVisible = $isvisible;
            $nicecategory->categoryUrl = $categoryurl;
            $nicecategory->courseImage = $outputimage;
            $nicecategory->ImageUrl = $outputimage;
            $nicecategory->courses = $categorycourses;
            $nicecategory->coursesCount = $categorycoursescount;
            $nicecategory->subcategories = $categorysubcategories;
            $nicecategory->subcategoriesCount = $categorysubcategoriescount;
            return $nicecategory;

        }
    }

    /**
     * Fetches example categories and their details.
     *
     * Retrieves up to a maximum number of example course categories,
     * with each returned as a detail object.
     *
     * @param int $maxnum Maximum number of categories to return.
     * @return stdClass[] Array of category detail objects.
     */
    public function nicegetexamplecategories($maxnum) {
        global $CFG, $DB;

        $nicecategories = $DB->get_records(
            'course_categories',
            [],
            $sort = '',
            $fields = '*',
            $limitfrom = 0,
            $limitnum = $maxnum
        );
        $nicereturn = [];
        foreach ($nicecategories as $nicecategory) {
            $nicereturn[] = $this->niceGetCategoryDetails($nicecategory->id);
        }
        return $nicereturn;
    }

    /**
     * Fetches example category IDs only.
     *
     * Retrieves up to a maximum number of example course category IDs.
     *
     * @param int $maxnum Maximum number of categories to return.
     * @return int[] Array of category IDs.
     */
    public function nicegetexamplecategoriesids($maxnum) {
        global $CFG, $DB;

        $nicecategories = $this->niceGetExampleCategories($maxnum);

        $nicereturn = [];
        foreach ($nicecategories as $key => $nicecategory) {
            $nicereturn[] = $nicecategory->categoryId;
        }
        return $nicereturn;
    }
}
