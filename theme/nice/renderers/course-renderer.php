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
 * Course renderer for theme_nice.
 *
 * This renderer overrides core_course_renderer methods
 * to customize the display of course categories and courses.
 *
 * Based on core_course_renderer from the Boost theme.
 *
 * @package    theme_nice
 * @copyright  2025 Nice Learning <support@docs.nicelearning.org>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @see core_course_renderer
 */

defined('MOODLE_INTERNAL') || die();
global $CFG;

require_once($CFG->dirroot . "/course/renderer.php");
require_once($CFG->dirroot . '/theme/nice/inc/course_handler/nice_course_handler.php');

/**
 * Course renderer
 */
class theme_nice_core_course_renderer extends core_course_renderer {

    /**
     * Returns HTML to display a course category as a part of a tree.
     *
     * This is an internal function. To display a particular category and all its contents,
     * use {@see core_course_renderer::course_category()}.
     *
     * @param coursecat_helper $chelper Various display options.
     * @param core_course_category $coursecat The course category to render.
     * @param int $depth Depth of this category in the current tree.
     * @return string HTML of the rendered category.
     */
    protected function coursecat_category(coursecat_helper $chelper, $coursecat, $depth) {

        global $CFG;

        $categoryname = $coursecat->get_formatted_name();
        $nicecategorylink = new moodle_url('/course/index.php', ['categoryid' => $coursecat->id]);

        $nicecat = $coursecat->id;
        $nicecatsummaryunclean = $chelper->get_category_formatted_description($coursecat);
        $nicecatsummary = preg_replace("/<img[^>]+\>/i", " ", $nicecatsummaryunclean ?? '');
        $childrencourses = $coursecat->get_courses();
        $niceitemscount = '';

        if ($coursecat->get_children_count() > 0) {
            $niceitemscount .= $coursecat->get_children_count() . ' ' . get_string('categories');
        } else {
            $niceitemscount .= count($coursecat->get_courses()) . ' ' . get_string('courses');
        }
        $nicecatupdated = get_string('modified') . ' ' . userdate($coursecat->timemodified, '%d %B %Y', 0);

        $contentimages = '';
        if ($description = $chelper->get_category_formatted_description($coursecat)) {
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML($description);
            libxml_clear_errors();
            $xpath = new \DOMXPath($dom);
            $src = $xpath->evaluate("string(//img/@src)");
        }

        if (isset($src) && !empty($src)) {
            $contentimages = '<img class="img-whp" src="' . $src . '" alt="' . $categoryname . '">';
        } else {
            $contentimages = '<img class="img-whp" src="'
                . $CFG->wwwroot . '/theme/nice/pix/category.jpg" alt="'
                . $categoryname . '">';
            foreach ($childrencourses as $childcourse) {
                if ($childcourse === reset($childrencourses)) {
                    foreach ($childcourse->get_course_overviewfiles() as $file) {
                        $isimage = $file->is_valid_image();
                        $url = moodle_url::make_pluginfile_url(
                            $file->get_contextid(),
                            $file->get_component(),
                            $file->get_filearea(),
                            null,
                            $file->get_filepath(),
                            $file->get_filename(),
                            !$isimage
                        )->out();
                        if ($isimage) {
                            $contentimages = '<img class="img-whp" src="' . $url . '" alt="' . $categoryname . '">';
                        }
                    }
                }
            }
        }

        $content = '';
        $content .= '
            <div class="col-xl-3 col-lg-4 col-sm-6 col-md-6">
                <div class="courses_list_content">
                    <a href="' . $nicecategorylink . '">
                        <div class="single-courses-box">
                            <div class="image">
                                ' . $contentimages . '
                            </div>
                            <div class="content">
                                <div class="list-title list-title-sub">
                                    <h3>' . $categoryname . '</h3>
                                </div>
                                <div>
                                    <i class="fa-solid fa-book"></i>
                                    <span>' . $niceitemscount . '</span>
                                </div>
                            </div>
                        </div><!--End single-courses-box -->
                    </a>
                </div><!--End courses_list_content -->
            </div><!--End col-xl-3 col-lg-4 col-sm-6 col-md-6-->';

        return $content;
    }

    /**
     * Renders the list of subcategories in a category
     *
     * @param coursecat_helper $chelper various display options
     * @param core_course_category $coursecat
     * @param int $depth depth of the category in the current tree
     * @return string
     */
    protected function coursecat_subcategories(coursecat_helper $chelper, $coursecat, $depth) {
        global $CFG;
        $subcategories = [];
        if (!$chelper->get_categories_display_option('nodisplay')) {
            $subcategories = $coursecat->get_children($chelper->get_categories_display_options());
        }
        $totalcount = $coursecat->get_children_count();
        if (!$totalcount) {
            return '';
        }
        $content = '';
        $content .= '<div class="no-container">';
        $content .= '<div class="courses row courses_container">';

        $paginationurl = $chelper->get_categories_display_option('paginationurl');
        $paginationallowall = $chelper->get_categories_display_option('paginationallowall');
        if ($totalcount > count($subcategories)) {
            if ($paginationurl) {

                $perpage = $chelper->get_categories_display_option('limit', $CFG->coursesperpage);
                $page = $chelper->get_categories_display_option('offset') / $perpage;
                $pagingbar = $this->paging_bar($totalcount, $page, $perpage,
                    $paginationurl->out(false, ['perpage' => $perpage]));
                if ($paginationallowall) {
                    $pagingbar .= html_writer::tag('div', html_writer::link(
                        $paginationurl->out(false, ['perpage' => 'all']),
                        get_string('showall', '', $totalcount)
                    ), ['class' => 'paging paging-showall']);
                }
            } else if ($viewmoreurl = $chelper->get_categories_display_option('viewmoreurl')) {

                if ($viewmoreurl->compare(new moodle_url('/course/index.php'), URL_MATCH_BASE)) {
                    $viewmoreurl->param('categoryid', $coursecat->id);
                }
                $viewmoretext = $chelper->get_categories_display_option('viewmoretext', new lang_string('viewmore'));
                $morelink = ' <div class="col-12 paging paging-morelink">
                              <div class="courses_all_btn text-center">
                                <a class="btn btn-transparent mt-3 mb-3" href="'.$viewmoreurl.'">'.$viewmoretext.'</a>
                              </div>
                            </div>';
            }
        } else if (($totalcount > $CFG->coursesperpage) && $paginationurl && $paginationallowall) {

            $pagingbar = html_writer::tag(
                'div',
                html_writer::link(
                    $paginationurl->out(false, ['perpage' => $CFG->coursesperpage]),
                    get_string('showperpage', '', $CFG->coursesperpage)
                ),
                ['class' => 'paging paging-showperpage']
            );
        }

        foreach ($subcategories as $subcategory) {
            $content .= $this->coursecat_category($chelper, $subcategory, $depth + 1);
        }

        if (!empty($pagingbar)) {
            $content .= $pagingbar;
        }
        if (!empty($morelink)) {
            $content .= $morelink;
        }

        $content .= '</div>';
        $content .= '</div>';
        return $content;
    }

    /**
     * Displays one course in the list of courses.
     *
     * This is an internal function. To display information about just one course,
     * please use {@see core_course_renderer::course_info_box()}.
     *
     * @param coursecat_helper $chelper Various display options.
     * @param core_course_list_element|stdClass $course The course object.
     * @param string $additionalclasses Additional classes to add to the main <div> tag
     *     (usually depends on the course’s position in the list—first/last/even/odd).
     * @return string HTML of the course box.
     */
    protected function coursecat_coursebox(coursecat_helper $chelper, $course, $additionalclasses = '') {
        $content = $this->coursecat_coursebox_content($chelper, $course);
        return $content;
    }

    /**
     * Returns HTML to display a single course using a Mustache template
     * theme_nice/core_course/coursecat-coursebox-content.
     *
     *
     * @param coursecat_helper $chelper Helper providing display options.
     * @param stdClass|core_course_list_element $course The course object.
     * @return string Rendered HTML for a single course card.
     */
    public function coursecat_coursebox_content(coursecat_helper $chelper, $course) {
        global $CFG;

        if ($course instanceof stdClass) {
            $course = new core_course_list_element($course);
        }

        $courseurl = new moodle_url('/course/view.php', ['id' => $course->id]);
        $overviewfiles = [];
        foreach ($course->get_course_overviewfiles() as $file) {
            $overviewfiles[] = [
                'fileurl' => moodle_url::make_pluginfile_url(
                    $file->get_contextid(),
                    $file->get_component(),
                    $file->get_filearea(),
                    null,
                    $file->get_filepath(),
                    $file->get_filename(),
                    !$file->is_valid_image()
                )->out(),
                'isimage' => $file->is_valid_image(),
                'filename' => $file->get_filename(),
            ];
        }

        $contacts = [];
        if ($course->has_course_contacts()) {
            foreach ($course->get_course_contacts() as $contact) {
                $contacts[] = [
                    'rolename' => implode(", ", array_map(
                        function ($role) {
                            return $role->displayname;
                        },
                        $contact['roles']
                    )),
                    'username' => $contact['username'],
                    'userurl' => (new moodle_url('/user/view.php', [
                        'id' => $contact['user']->id,
                        'course' => SITEID,
                    ]))->out(),
                ];
            }
        }

        $data = [
            'id' => $course->id,
            'fullname' => $course->get_formatted_name(),
            'shortname' => $course->shortname,
            'summary' => $course->has_summary() ? $chelper->get_course_formatted_summary($course) : '',
            'summarytext' => $course->summary,
            'overviewfiles' => $overviewfiles,
            'coursecontacts' => $contacts,
            'courseurl' => $courseurl->out(),
            'enrolledusercount' => count_enrolled_users(context_course::instance($course->id)),
        ];

        return $this->render_from_template('theme_nice/core_course/coursecat-coursebox-content', $data);
    }

    /**
     * Renders HTML to display particular course category - list of it's subcategories and courses
     *
     * Invoked from /course/index.php
     *
     * @param int|stdClass|core_course_category $category
     */
    public function course_category($category) {
        global $CFG;
        $usertop = core_course_category::user_top();
        if (empty($category)) {
            $coursecat = $usertop;
        } else if (is_object($category) && $category instanceof core_course_category) {
            $coursecat = $category;
        } else {
            $coursecat = core_course_category::get(is_object($category) ? $category->id : $category);
        }
        $site = get_site();
        $actionbar = new \core_course\output\category_action_bar($this->page, $coursecat);
        $output = $this->render_from_template('core_course/category_actionbar', $actionbar->export_for_template($this));

        if (core_course_category::is_simple_site()) {

            $strfulllistofcourses = get_string('fulllistofcourses');
            $this->page->set_title("$site->shortname: $strfulllistofcourses");
        } else if (!$coursecat->id || !$coursecat->is_uservisible()) {
            $strcategories = get_string('categories');
            $this->page->set_title("$site->shortname: $strcategories");
        } else {
            $strfulllistofcourses = get_string('fulllistofcourses');
            $this->page->set_title("$site->shortname: $strfulllistofcourses");
        }

        $chelper = new coursecat_helper();
        if ($description = $chelper->get_category_formatted_description($coursecat)) {
            $output .= $this->box($description, ['class' => 'generalbox info']);
        }
        $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_AUTO)
            ->set_attributes(['class' => 'row category-browse category-browse-'.$coursecat->id]);

        $coursedisplayoptions = [];
        $catdisplayoptions = [];
        $browse = optional_param('browse', null, PARAM_ALPHA);
        $perpage = optional_param('perpage', $CFG->coursesperpage, PARAM_INT);
        $page = optional_param('page', 0, PARAM_INT);
        $baseurl = new moodle_url('/course/index.php');
        if ($coursecat->id) {
            $baseurl->param('categoryid', $coursecat->id);
        }
        if ($perpage != $CFG->coursesperpage) {
            $baseurl->param('perpage', $perpage);
        }
        $coursedisplayoptions['limit'] = $perpage;
        $catdisplayoptions['limit'] = $perpage;
        if ($browse === 'courses' || !$coursecat->get_children_count()) {
            $coursedisplayoptions['offset'] = $page * $perpage;
            $coursedisplayoptions['paginationurl'] = new moodle_url($baseurl, ['browse' => 'courses']);
            $catdisplayoptions['nodisplay'] = true;
            $catdisplayoptions['viewmoreurl'] = new moodle_url($baseurl, ['browse' => 'categories']);
            $catdisplayoptions['viewmoretext'] = new lang_string('viewallsubcategories');
        } else if ($browse === 'categories' || !$coursecat->get_courses_count()) {
            $coursedisplayoptions['nodisplay'] = true;
            $catdisplayoptions['offset'] = $page * $perpage;
            $catdisplayoptions['paginationurl'] = new moodle_url($baseurl, ['browse' => 'categories']);
            $coursedisplayoptions['viewmoreurl'] = new moodle_url($baseurl, ['browse' => 'courses']);
            $coursedisplayoptions['viewmoretext'] = new lang_string('viewallcourses');
        } else {
            $coursedisplayoptions['viewmoreurl'] = new moodle_url($baseurl, ['browse' => 'courses', 'page' => 1]);
            $catdisplayoptions['viewmoreurl'] = new moodle_url($baseurl, ['browse' => 'categories', 'page' => 1]);
        }
        $chelper->set_courses_display_options($coursedisplayoptions)->set_categories_display_options($catdisplayoptions);

        $output .= $this->coursecat_tree($chelper, $coursecat);

        return $output;
    }
}
