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
 * Class definition for the block_nice_courses_slider_5.
 *
 * @package    block_nice_courses_slider_5
 * @copyright  2025 Nice Learning <support@docs.nicelearning.org>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_nice_courses_slider_5 extends block_base {

    /**
     * Initialization method.
     *
     * @return void
     */
    public function init(): void {
        $this->title = get_string('pluginname', 'block_nice_courses_slider_5');
    }

    /**
     * Specialization method to process block-specific configurations.
     *
     * @return void
     */
    public function specialization(): void {
        global $CFG, $DB;

        // Include external specialization file.
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
    public function get_content(): ?stdClass {
        global $CFG, $DB, $COURSE, $USER, $PAGE;

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

        $this->content->link = !empty($this->config->link)
            ? $this->config->link : '';

        $this->config->show_students = isset($this->config->show_students)
            ? $this->config->show_students : 1;

        $this->config->show_description = isset($this->config->show_description)
            ? $this->config->show_description : 1;

        $this->config->show_price = isset($this->config->show_price)
            ? $this->config->show_price : 1;

        $viewallcourses = get_string('view_all_courses', 'block_nice_courses_slider_5');
        $people = get_string('people', 'block_nice_courses_slider_5');
        $displaycourses = get_string('display_courses', 'block_nice_courses_slider_5');

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

            foreach ($coursesarr as $key => $courseid) {
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

        $hrcontent = '<hr>';

        if (!$this->config->show_price && !$this->config->show_students) {
            $hrcontent = '';
        }

        $descriptionhiddenclass = ($this->config->show_description)
            ? ''
            : ' description-is-hidden';

        $text = '
            <section class="nice-courses-slider-5">
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
                        <div class="owl-carousel nice-courses-slider-five">';

        if (!empty($this->config->courses)) {
            $chelper = new coursecat_helper();
            $totalcourses = count($coursesarr);

            foreach ($courses as $course) {
                if ($DB->record_exists('course', ['id' => $course->id])) {
                    $nicecoursehandler = new niceCourseHandler();
                    $nicecourse = $nicecoursehandler->niceGetCourseDetails($course->id);
                    $nicecoursedescription = $nicecoursehandler->niceGetCourseDescription(
                        $course->id,
                        100
                    );

                    $studentscontent = '';

                    if ($this->config->show_students) {
                        $studentscontent = '
                            <div class="nice-course-card-students-container">
                                <i class="fa-regular fa-circle-user"></i>'
                                . $nicecourse->enrolments . '
                                <span>' . $people . '</span>
                            </div>';
                    }

                    $descriptioncontent = '';

                    if ($this->config->show_description) {
                        $descriptioncontent = '
                            <div class="nice-course-card-description-container">'
                                . $nicecoursedescription . '
                            </div>';
                    }

                    $pricecontent = '';

                    if ($this->config->show_price) {
                        $pricecontent = '
                            <div class="nice-course-card-price-container fw-bold nice-color-main">
                                <span>$</span>
                                <span>' . $nicecourse->course_price . '</span>
                            </div>';
                    }

                    $text .= '
                        <a href="' . $nicecourse->url . '">
                            <div class="nice-course-card-container">
                                <div class="nice-course-card nice-border-radius nice-background-white overflow-hidden">
                                    <div class="nice-course-card-image-container position-relative">'
                                        . $nicecourse->niceRender->courseImage . '
                                        <div class="nice-course-card-image-overlay position-absolute"></div>
                                    </div>
                                    <div class="nice-course-card-content-container nice-background-white">
                                        <div class="nice-course-card-category-container nice-color-main">
                                            <ul>
                                                <li>' . $nicecourse->categoryName . '</li>
                                            </ul>
                                        </div>
                                        <div class="nice-course-card-title-container ' . $descriptionhiddenclass . '">
                                            <div class="h4 fw-bold m-0">'
                                                . $nicecourse->fullName . '
                                            </div>
                                        </div>'
                                        . $descriptioncontent .
                                        $hrcontent . '
                                        <div class="nice-ccourse-ard-font-size-16 d-flex justify-content-between">'
                                            . $studentscontent .
                                            $pricecontent . '
                                        </div>
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
