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
require_once($CFG->libdir . '/filelib.php');

/**
 * Class definition for the block_nice_instructors_slider_1
 *
 * @package     block_nice_instructors_slider_1
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_nice_instructors_slider_1 extends block_base {

    /**
     * Initialization method.
     *
     * @return void
     */
    public function init(): void {
        $this->title = get_string('pluginname', 'block_nice_instructors_slider_1');
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
            $this->config = new stdClass();
            $this->config->main_title = 'All Instructors';
        }
    }

    /**
     * Generate the block's content.
     *
     * @return stdClass
     */
    public function get_content(): stdClass {
        global $CFG, $DB;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();

        $this->content->main_title = !empty($this->config->main_title)
            ? $this->config->main_title : '';
        $this->content->main_description = !empty($this->config->main_description)
            ? $this->config->main_description : '';
        $this->content->title_placement = !empty($this->config->title_placement)
            ? $this->config->title_placement : 0;
        $this->content->description = !empty($this->config->description)
            ? $this->config->description : '';
        $this->content->link = !empty($this->config->link)
            ? $this->config->link : '';

        $viewallinstructors = get_string(
            'view_all_instructors',
            'block_nice_instructors_slider_1'
        );
        $chooseitems = get_string(
            'choose_items',
            'block_nice_instructors_slider_1'
        );

        $alignmentclass = 'text-start';

        switch ($this->content->title_placement) {
            case 1:
                $alignmentclass = 'text-end';
                break;
            case 2:
                $alignmentclass = 'text-center';
                break;
        }

        $text = '
            <section class="nice-instructors-slider-1">
                <div class="nice-instructors-slider-container">
                    <div class="container">
                        <div class="nice-instructors-slider-title-container">
                            <div class="h2 mb-0 fw-bold ' . $alignmentclass . '">'
                                . format_text(
                                    $this->content->main_title,
                                    FORMAT_HTML,
                                    ['filter' => true]
                                ) . '
                            </div>
                        </div>
                        <div class="nice-instructors-slider-description-container">
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
                        <div class="owl-carousel nice-instructors-slider-one">';

        $userdetails = [];

        $selecteduserids = isset($this->config->users)
            ? $this->config->users
            : [];

        if (!empty($selecteduserids)) {
            list($insql, $inparams) = $DB->get_in_or_equal($selecteduserids);
            $sql = "SELECT * FROM {user} WHERE id $insql";
            $selectedusers = $DB->get_records_sql($sql, $inparams);

            foreach ($selectedusers as $user) {
                $profileurl = new moodle_url(
                    '/user/profile.php',
                    ['id' => $user->id]
                );
                $userpicture = new user_picture($user);
                $userpicture->size = 128;
                $pictureurl = $userpicture->get_url($this->page)->out(false);

                $fullname = format_string($user->firstname . ' ' . $user->lastname);

                $text .= '
                    <a href="' . $profileurl . '">
                        <div class="nice-instructor-card-container position-relative">
                            <div class="nice-instructor-card nice-background-white text-center nice-border-radius overflow-hidden">
                                <div class="nice-instructor-card-image-container position-relative">
                                    <img class="rounded-circle" src="' . $pictureurl . '" alt="' . $fullname . '"/>
                                    <div class="nice-instructor-card-image-overlay position-absolute rounded-circle"></div>
                                </div>
                                <div class="nice-instructor-card-content-container">
                                    <div class="h4 fw-bold m-0">' . $fullname . '</div>
                                </div>
                            </div>
                        </div>
                    </a>';
            }
        } else {
            $text .= '
                <span class="nice-color-yellow">'
                    . $chooseitems . '
                </span>';
        }

        $text .= '
                        </div>';

        if (!empty($this->content->link)) {
            $text .= '
                        <div class="nice-instructor-card-button-container text-center">
                            <a href="' . htmlspecialchars($this->content->link) . '" class="btn btn-primary">'
                                . $viewallinstructors . '
                            </a>
                        </div>';
        }

        $text .= '
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
