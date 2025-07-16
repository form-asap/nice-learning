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
 * Class definition for the block_nice_subscribe_with_us.
 *
 * @package     block_nice_subscribe_with_us
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_nice_subscribe_with_us extends block_base {

    /**
     * Initialization method.
     *
     * @return void
     */
    public function init(): void {
        $this->title = get_string('pluginname', 'block_nice_subscribe_with_us');
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
            $this->config = new stdClass();
            $this->config->main_title = 'Subscribe With US';
            $this->config->main_description =
                'Subscribe with us today to unlock a world of endless learning opportunities.';
        }
    }

    /**
     * Generate the block's content.
     *
     * @return stdClass
     */
    public function get_content(): stdClass {
        global $CFG;

        require_once($CFG->libdir . '/filelib.php');

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();

        $this->content->main_title = !empty($this->config->main_title)
            ? $this->config->main_title : '';

        $this->content->title_and_description_placement =
            !empty($this->config->title_and_description_placement)
                ? $this->config->title_and_description_placement : 0;

        $this->content->main_description = !empty($this->config->main_description)
            ? $this->config->main_description : '';

        $placeholder = get_string('placeholder', 'block_nice_subscribe_with_us');

        $alignmentclass = 'text-start';

        switch ($this->content->title_and_description_placement) {
            case 1:
                $alignmentclass = 'text-end';
                break;
            case 2:
                $alignmentclass = 'text-center';
                break;
        }

        $text = '
            <div class="nice-subscribe-with-us-container nice-background-light-grey">
                <div class="nice-subscribe-with-us">
                    <div class="container">
                        <div class="' . $alignmentclass . '">
                            <div class="nice-subscribe-with-us-main-title-container">
                                <div class="h2 mb-0 fw-bold">'
                                    . format_text(
                                        $this->content->main_title,
                                        FORMAT_HTML,
                                        ['filter' => true]
                                    ) . '
                                </div>
                            </div>
                            <div class="nice-subscribe-with-us-main-description-container">
                                <p>'
                                    . format_text(
                                        $this->content->main_description,
                                        FORMAT_HTML,
                                        ['filter' => true]
                                    ) . '
                                </p>
                            </div>
                            <div class="nice-subscribe-with-us-form-container">
                                <form class="nice-floating-form position-relative" method="post" action="'
                                    . $CFG->wwwroot . '/local/contact/index.php">
                                    <input
                                        class="form-control"
                                        type="email"
                                        name="email"
                                        placeholder="' . $placeholder . '"
                                        required="required"
                                    />
                                    <input
                                        type="hidden"
                                        id="sesskey"
                                        name="sesskey"
                                        value="' . sesskey() . '"
                                    />
                                    <input
                                        type="hidden"
                                        id="name"
                                        name="name"
                                        value="name"
                                    />
                                    <button
                                        type="submit"
                                        name="submit"
                                        value="Submit"
                                        class="nice-color-main">
                                        <i class="fa-regular fa-envelope"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';

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
