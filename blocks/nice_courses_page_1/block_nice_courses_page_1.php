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
 * Class definition for the block_nice_courses_page_1.
 *
 * @package    block_nice_courses_page_1
 * @copyright  2025 Nice Learning <support@docs.nicelearning.org>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_nice_courses_page_1 extends block_base {

    /**
     * Initialization method.
     *
     * @return void
     */
    public function init(): void {
        $this->title = get_string('pluginname', 'block_nice_courses_page_1');
    }

    /**
     * Specialization method to process block-specific configurations.
     *
     * @return void
     */
    public function specialization(): void {
        global $CFG, $DB;

        include($CFG->dirroot . '/theme/nice/inc/block_handler/specialization.php');

        if (empty($this->config)) {
            $this->config = new \stdClass();
            $this->config->main_title = 'All Courses';
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

        $this->content->main_description =
            !empty($this->config->main_description) ? $this->config->main_description : '';

        $this->content->title_placement =
            !empty($this->config->title_placement) ? $this->config->title_placement : 0;

        $this->content->description =
            !empty($this->config->description) ? $this->config->description : '';

        $this->config->show_shortname =
            isset($this->config->show_shortname) ? $this->config->show_shortname : 1;

        $people = get_string('people', 'block_nice_courses_page_1');
        $displaycourses = get_string('display_courses', 'block_nice_courses_page_1');

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

                $courserecord = $DB->get_record('course', ['id' => $courseobj->id], 'category');

                if ($courserecord) {
                    $coursecategory = $DB->get_record(
                        'course_categories',
                        ['id' => $courserecord->category]
                    );

                    if ($coursecategory && $coursecategory->visible) {
                        $courseobj->category = $coursecategory->id;
                        $courseobj->category_name = $coursecategory->name;
                        $courses->$courseid = $courseobj;
                    }
                }
            }

            $categories = [];

            foreach ($courses as $course) {
                $categories[$course->category] = $course->category_name;
            }

            $categories = array_unique($categories);
        }

        $text = '
            <section class="nice-courses-page-1">
                <div class="nice-courses-page-container">
                    <div class="container">
                        <div class="nice-courses-page-main-title-container">
                            <div class="h2 mb-0 fw-bold ' . $alignmentclass . '">
                                ' . format_text(
                                    $this->content->main_title,
                                    FORMAT_HTML,
                                    ['filter' => true]
                                ) . '
                            </div>
                        </div>
                        <div class="nice-courses-page-main-description-container">
                            <p class="m-0">
                                ' . format_text(
                                    $this->content->main_description,
                                    FORMAT_HTML,
                                    ['filter' => true]
                                ) . '
                            </p>
                        </div>
                    </div>
                    <div class="container">
                        <div class="row">
        ';

        if (!empty($this->config->courses)) {
            $chelper = new coursecat_helper();

            foreach ($courses as $course) {
                $courseinfo = $DB->get_record('course', ['id' => $course->id], 'id, visible');
                if ($courseinfo && $courseinfo->visible) {
                    $nicecoursehandler = new theme_nice_course_handler();
                    $nicecourse = $nicecoursehandler->theme_nice_get_course_details($course->id);
                    $nicecoursedescription = $nicecoursehandler
                        ->theme_nice_get_course_description($course->id, 100);

                    $shortnamecontent = '';

                    if ($this->config->show_shortname) {
                        $shortnamecontent = '
                            <div class="nice-course-card-sub-title-container d-flex align-items-center">
                                <i class="fa-solid fa-book"></i>
                                <span>' . $nicecourse->short_name . '</span>
                            </div>';
                    }

                    $text .= '
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                            <a href="' . $nicecourse->url . '">
                                <div class="nice-course-card-container">
                                    <div class="nice-course-card nice-border-radius nice-background-white">
                                        <div class="nice-course-card-image-container position-relative">
                                            ' . $nicecourse->niceRender->course_image . '
                                            <div class="nice-course-card-image-overlay position-absolute"></div>
                                        </div>
                                        <div class="nice-course-card-content-container">
                                            ' . $shortnamecontent . '
                                            <div class="nice-course-card-title-container position-relative">
                                                <div class="h4 m-0 fw-bold">'
                                                    . $nicecourse->full_name .
                                                '</div>
                                            </div>
                                            <div class="nice-course-card-description-container">
                                                ' . $nicecoursedescription . '
                                            </div>
                                            <div class="nice-course-card-content d-flex align-items-center justify-content-between">
                                                <div class="nice-course-card-students-container nice-color-main">
                                                    <i class="fa-regular fa-circle-user"></i>
                                                    ' . $nicecourse->enrolments . '
                                                    <span>' . $people . '</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>';
                }
            }
        } else {
            $text .= '
                <div class="container">
                    <span class="nice-color-yellow">
                        ' . $displaycourses . '
                    </span>
                </div>';
        }

        $text .= '
                        </div>
                    </div>
                </div>
            </section>
        ';

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
