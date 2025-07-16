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

/**
 * Class definition for the block_nice_course_need_help
 *
 * @package     block_nice_course_need_help
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_nice_course_need_help extends block_base {

    /**
     * Initialization method.
     *
     * @return void
     */
    public function init(): void {
        $this->title = get_string('pluginname', 'block_nice_course_need_help');
    }

    /**
     * Specialization method to process block-specific configurations.
     *
     * @return void
     */
    public function specialization(): void {
        global $CFG, $DB;

        // This line includes an external PHP file located in the specified path.
        include($CFG->dirroot . '/theme/nice/inc/block_handler/specialization.php');

        if (empty($this->config)) {
            $this->config = new \stdClass();
            $this->config->title = 'Need help?';
            $this->config->description = 'Are you experiencing issues accessing our course components? Email us at:';
            $this->config->email = 'email@example.com';
        }
    }

    /**
     * Generate the block's content.
     *
     * @return stdClass
     */
    public function get_content(): ?stdClass {
        global $CFG, $DB;

        require_once($CFG->libdir . '/filelib.php');

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();

        $this->content->title = !empty($this->config->title) ? $this->config->title : '';
        $this->content->description = !empty($this->config->description) ? $this->config->description : '';
        $this->content->email = !empty($this->config->email) ? $this->config->email : '';

        $text = '
            <section class="nice-course-need-help-container">
                <h5>' .
                    format_text(
                        $this->content->title,
                        FORMAT_HTML,
                        ['filter' => true]
                    ) . '
                </h5>

                <div class="nice-course-need-help d-flex align-items-center position-relative">
                    <p>' .
                        format_text(
                            $this->content->description,
                            FORMAT_HTML,
                            ['filter' => true]
                        ) . '
                    </p>
                    <a href="mailto:' .
                        format_text(
                            $this->content->email,
                            FORMAT_HTML,
                            ['filter' => true]
                        ) . '">' .
                        format_text(
                            $this->content->email,
                            FORMAT_HTML,
                            ['filter' => true]
                        ) . '
                    </a>
                    <div class="nice-course-need-help-icon-container position-absolute">
                        <i class="fa-regular fa-circle-question"></i>
                    </div><!--End nice-course-need-help-icon-container -->
                </div><!--End nice-course-need-help -->
            </section><!--End nice-course-need-help-container -->
        ';

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
            'my' => true,
            'admin' => false,
            'course-view' => true,
            'course' => true,
        ];
    }
}
