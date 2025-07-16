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
 * Class definition for the block_nice_courses_slider_4.
 *
 * @package    block_nice_courses_slider_4
 * @copyright  2025 Nice Learning <support@docs.nicelearning.org>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_nice_courses_slider_4 extends block_base {

    /**
     * Initialization method.
     *
     * @return void
     */
    public function init(): void {
        $this->title = get_string('pluginname', 'block_nice_courses_slider_4');
    }

    /**
     * Specialization method to process block-specific configurations.
     *
     * @return void
     */
    public function specialization(): void {
        global $CFG;

        include($CFG->dirroot . '/theme/nice/inc/block_handler/specialization.php');

        if (empty($this->config)) {
            $this->config = new \stdClass();
            $this->config->main_title = 'All Courses';
            $this->config->link = $CFG->wwwroot . '/course';
        }
    }

    /**
     * Generate the block's content.
     *
     * @return stdClass
     */
    public function get_content(): stdClass {
        global $CFG, $DB, $COURSE, $USER;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();

        $this->content->main_title =
            !empty($this->config->main_title) ? $this->config->main_title : '';

        $this->content->title_placement =
            !empty($this->config->title_placement) ? $this->config->title_placement : 0;

        $this->content->main_description =
            !empty($this->config->main_description) ? $this->config->main_description : '';

        $this->content->link =
            !empty($this->config->link) ? $this->config->link : '';

        $this->config->show_shortname =
            isset($this->config->show_shortname) ? $this->config->show_shortname : 1;

        $this->config->show_students =
            isset($this->config->show_students) ? $this->config->show_students : 1;

        $viewallcourses = get_string('view_all_courses', 'block_nice_courses_slider_4');
        $people = get_string('people', 'block_nice_courses_slider_4');
        $displaycourses = get_string('display_courses', 'block_nice_courses_slider_4');

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
        $courses = new stdClass();

        if (!empty($this->config->courses)) {
            $coursesarr = $this->config->courses;

            foreach ($coursesarr as $courseid) {
                $courserecord = $DB->get_record(
                    'course',
                    ['id' => $courseid],
                    'id, category'
                );

                if ($courserecord) {
                    $categoryrecord = $DB->get_record(
                        'course_categories',
                        ['id' => $courserecord->category]
                    );

                    if ($categoryrecord) {
                        $category = core_course_category::get($categoryrecord->id);

                        $courseobj = new stdClass();
                        $courseobj->id = $courserecord->id;
                        $courseobj->category = $category->id;
                        $courseobj->category_name = $category->get_formatted_name();

                        $courses->$courseid = $courseobj;
                    }
                }
            }

            foreach ($courses as $course) {
                $categories[$course->category] = $course->category_name;
            }

            $categories = array_unique($categories);
        }

        $studentshiddenclass = $this->config->show_students ? '' : ' students-is-hidden';

        $text = '
            <section class="nice-courses-slider-4">
                <div class="nice-courses-slider-container">
                    <div class="container">
                        <div class="nice-courses-slider-title-container">
                            <div class="h2 mb-0 fw-bold ' . $alignmentclass . '">'
                                . format_text(
                                    $this->content->main_title,
                                    FORMAT_HTML,
                                    ['filter' => true]
                                ) . '
                            </div>
                        </div>
                        <div class="nice-courses-slider-description-container">
                            <p class="m-0">'
                                . format_text(
                                    $this->content->main_description,
                                    FORMAT_HTML,
                                    ['filter' => true]
                                ) . '
                            </p>
                        </div>
                    </div>
                    <div class="container">
                        <div class="owl-carousel nice-courses-slider-four">';

        if (!empty($this->config->courses)) {
            $chelper = new coursecat_helper();

            foreach ($courses as $course) {
                if ($DB->record_exists('course', ['id' => $course->id])) {
                    $nicecoursehandler = new niceCourseHandler();
                    $nicecourse = $nicecoursehandler->niceGetCourseDetails($course->id);
                    $nicecoursedescription = $nicecoursehandler
                        ->niceGetCourseDescription($course->id, 100);

                    $studentscontent = '';
                    if ($this->config->show_students) {
                        $studentscontent = '
                            <div class="nice-course-card-students-container fw-bold text-center position-absolute nice-border-radius nice-box-shadow nice-background-white">
                                <i class="fa-regular fa-circle-user"></i>
                                ' . $nicecourse->enrolments . '
                                <span>' . $people . '</span>
                            </div>';
                    }

                    $text .= '
                        <a href="' . $nicecourse->url . '" class="course-card-link">
                            <div class="nice-course-card-container position-relative">
                                <div class="nice-course-card overflow-hidden nice-border-radius nice-background-white">
                                    <div class="nice-course-card-image-container position-relative">'
                                        . $nicecourse->niceRender->courseImage . '
                                        <div class="nice-course-card-image-overlay position-absolute"></div>
                                    </div>
                                    <div class="nice-course-card-content-container">
                                        <div class="nice-course-card-title-container' . $studentshiddenclass . '">
                                            <div class="h4 fw-bold m-0">'
                                                . $nicecourse->fullName . '
                                            </div>
                                        </div>
                                        <div class="nice-course-card-description-container">'
                                            . $nicecoursedescription . '
                                        </div>'
                                        . $studentscontent . '
                                    </div>
                                </div>
                            </div>
                        </a>';
                }
            }
        } else {
            $text .= '
                <span class="nice-color-yellow">'
                    . $displaycourses . '
                </span>';
        }

        $text .= '
                        </div>
                        <div class="nice-course-card-button-container text-center">
                            <a href="' . htmlspecialchars($this->content->link) . '" class="btn btn-primary">'
                                . $viewallcourses . '
                            </a>
                        </div>
                    </div>
                </div>
            </section>';

        $this->content->text = $text;
        $this->content->footer = '';

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
