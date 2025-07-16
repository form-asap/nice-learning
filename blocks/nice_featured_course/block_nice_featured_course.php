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

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot . '/course/renderer.php');

require_once($CFG->dirroot . '/theme/nice/inc/course_handler/nice_course_handler.php');

/**
 * Class definition for the block_nice_featured_course.
 *
 * @package     block_nice_featured_course
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_nice_featured_course extends block_base {

    /**
     * Initialization method.
     *
     * @return void
     */
    public function init(): void {
        $this->title = get_string('pluginname', 'block_nice_featured_course');
    }

    /**
     * Specialization method to process block-specific configurations.
     *
     * @return void
     */
    public function specialization(): void {
        global $CFG, $DB;

        // Include external specialization logic.
        include($CFG->dirroot . '/theme/nice/inc/block_handler/specialization.php');

        if (empty($this->config)) {
            $this->config = new stdClass();
            $this->config->main_title = 'Featured Course';
        }
    }

    /**
     * Check if a user is enrolled in a course.
     *
     * @param int $courseid The course ID.
     * @param int $userid The user ID.
     * @return bool True if enrolled.
     */
    public function isuserenrolled($courseid, $userid): bool {
        $context = context_course::instance($courseid);
        return is_enrolled($context, $userid);
    }

    /**
     * Generate the block's content.
     *
     * @return stdClass
     */
    public function get_content(): ?stdClass {
        global $CFG, $DB, $COURSE, $USER;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->main_title = !empty($this->config->main_title)
            ? $this->config->main_title : '';
        $this->content->title_placement = !empty($this->config->title_placement)
            ? $this->config->title_placement : 0;
        $this->content->main_description = !empty($this->config->main_description)
            ? $this->config->main_description : '';

        $this->config->show_shortname = isset($this->config->show_shortname)
            ? $this->config->show_shortname : 1;
        $this->config->show_enddate = isset($this->config->show_enddate)
            ? $this->config->show_enddate : 1;
        $this->config->show_students = isset($this->config->show_students)
            ? $this->config->show_students : 1;
        $this->config->show_price = isset($this->config->show_price)
            ? $this->config->show_price : 1;
        $this->config->section_direction = isset($this->config->section_direction)
            ? $this->config->section_direction : 1;

        $viewallcourses = get_string('view_all_courses', 'block_nice_featured_course');
        $people = get_string('people', 'block_nice_featured_course');
        $enrollnow = get_string('enroll_now', 'block_nice_featured_course');
        $view = get_string('view', 'block_nice_featured_course');
        $displaycourses = get_string('display_courses', 'block_nice_featured_course');

        $alignmentclass = 'text-start';
        switch ($this->content->title_placement) {
            case 1:
                $alignmentclass = 'text-end';
                break;
            case 2:
                $alignmentclass = 'text-center';
                break;
        }

        $categories = [];

        if (!empty($this->config->courses)) {
            $coursesarr = $this->config->courses;
            $courses = new stdClass();

            foreach ($coursesarr as $courseid) {
                $courseobj = new stdClass();
                $courseobj->id = $courseid;

                $courserecord = $DB->get_record(
                    'course',
                    ['id' => $courseobj->id],
                    'category'
                );

                $coursecategory = $DB->get_record(
                    'course_categories',
                    ['id' => $courserecord->category]
                );

                $coursecategory = core_course_category::get($coursecategory->id);

                $courseobj->category = $coursecategory->id;
                $courseobj->category_name = $coursecategory->get_formatted_name();

                $courses->{$courseid} = $courseobj;
            }

            $categories = [];
            foreach ($courses as $course) {
                $categories[$course->category] = $course->category_name;
            }
            $categories = array_unique($categories);
        }

        $text = '<section class="nice-featured-course-container">';
        $text .= '<div class="container">';
        $text .= '
            <div class="nice-featured-course-main-title ' . $alignmentclass . '">
                <div class="h2 mb-0 fw-bold">' .
                format_text(
                    $this->content->main_title,
                    FORMAT_HTML,
                    ['filter' => true]
                ) . '
                </div>
            </div>
            <div class="nice-featured-course-main-description">
                <p>' .
                format_text(
                    $this->content->main_description,
                    FORMAT_HTML,
                    ['filter' => true]
                ) . '
                </p>
            </div>';

        if (!empty($this->config->course)) {
            $courseid = $this->config->course;

            if ($DB->record_exists('course', ['id' => $courseid])) {
                $nicecoursehandler = new niceCourseHandler();
                $nicecourse = $nicecoursehandler->niceGetCourseDetails($courseid);
                $nicecoursedescription = $nicecoursehandler->niceGetCourseDescription(
                    $courseid,
                    150
                );

                $enddatetime = DateTime::createFromFormat('d/m/y', $nicecourse->endDate);
                $formattedenddate = $enddatetime
                    ? $enddatetime->format('F, d Y')
                    : '';

                $userisenrolled = $this->isuserenrolled(
                    $COURSE->id,
                    $USER->id
                );

                $direction = $this->config->section_direction ? '' : 'flex-row-reverse';

                $text .= '
                    <div class="row align-items-center ' . $direction . '">
                        <div class="col-lg-7 col-md-6">
                            <div class="nice-featured-course-image-container position-relative">'
                                . $nicecourse->niceRender->courseImage . '
                                <div class="nice-featured-course-image-overlay nice-border-radius position-absolute"></div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-6">
                            <div class="nice-featured-course-content-container nice-border-radius nice-background-white">
                                <div class="nice-featured-course-title-container h4 fw-bold">'
                                    . $nicecourse->fullName . '
                                </div>';

                if ($this->config->show_shortname) {
                    $text .= '
                        <div class="nice-featured-course-item d-flex align-items-center nice-background-light-grey nice-border-radius gap-2">
                            <i class="fa-solid fa-book"></i>
                            <span>' . $nicecourse->shortName . '</span>
                        </div>';
                }

                if ($this->config->show_enddate) {
                    $text .= '
                        <div class="nice-featured-course-item d-flex align-items-center nice-background-light-grey nice-border-radius gap-2">
                            <i class="fa-solid fa-calendar-days"></i>
                            <span>' . $formattedenddate . '</span>
                        </div>';
                }

                if ($this->config->show_students) {
                    $text .= '
                        <div class="nice-featured-course-item d-flex align-items-center nice-background-light-grey nice-border-radius gap-2">
                            <i class="fa-regular fa-circle-user"></i>
                            <span>' . $nicecourse->enrolments . '</span>
                            <span>' . $people . '</span>
                        </div>';
                }

                $text .= '
                    <div class="nice-featured-course-description-container">'
                        . $nicecoursedescription . '
                    </div>';

                $flex = $this->config->show_price
                    ? 'd-flex align-items-center justify-content-between'
                    : '';

                $text .= '
                    <div class="nice-featured-course-enroll-button ' . $flex . '">';

                if (!$userisenrolled) {
                    if ($this->config->show_price && !empty($nicecourse->course_price)) {
                        $text .= '
                            <div class="nice-course-card-button-price fw-bold">
                                <span>$' . $nicecourse->course_price . '</span>
                            </div>';
                    }
                }

                if (!$userisenrolled) {
                    $text .= '
                        <a href="' . $nicecourse->enrolmentLink . '" class="btn btn-primary" aria-label="' . $enrollnow . '">'
                            . $enrollnow . '
                        </a>
                    </div>';
                } else {
                    $text .= '
                        <a href="' . $nicecourse->enrolmentLink . '" class="btn btn-primary" aria-label="' . $view . '">'
                            . $view . '
                        </a>
                    </div>';
                }

                $text .= '
                            </div>
                        </div>
                    </div>';
            }
        } else {
            $text .= '
                <span class="nice-color-yellow">'
                    . $displaycourses . '
                </span>';
        }

        $text .= '</div></section>';

        $this->content->footer = '';
        $this->content->text = $text;

        return $this->content;
    }

    /**
     * Saves the block's instance configuration.
     *
     * @param object $data The data being saved.
     * @param bool $nolongerused Previously used, but no longer has a purpose.
     * @return bool
     */
    public function instance_config_save($data, $nolongerused = false) {
        return parent::instance_config_save($data, $nolongerused);
    }

    /**
     * Whether this block type allows multiple instances on a page.
     *
     * @return bool
     */
    public function instance_allow_multiple(): bool {
        return true;
    }

    /**
     * Whether this block has global configuration settings.
     *
     * @return bool
     */
    public function has_config(): bool {
        return true;
    }

    /**
     * Specifies where this block can be added.
     *
     * @return array Allowed formats.
     */
    public function applicable_formats(): array {
        return [
            'all' => true,
            'my' => false,
            'admin' => false,
            'course-view' => true,
            'course' => true,
        ];
    }
}
