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
 * Class definition for block_nice_contact_us_page_1.
 *
 * @package    block_nice_contact_us_page_1
 * @copyright  2025 Nice Learning <support@docs.nicelearning.org>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_nice_contact_us_page_1 extends block_base {

    /**
     * Initialization method.
     *
     * @return void
     */
    public function init(): void {
        $this->title = get_string('pluginname', 'block_nice_contact_us_page_1');
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
            $this->config->main_title = 'Contact Us';
            $this->config->phone = '+14 888 3421 0213';
            $this->config->email = 'example@example.com';
            $this->config->working_hours = '(9 AM - 6 PM, Sunday - Friday)';
            $this->config->location =
                'John Doe, 123 Sydney Street, Suite 4B, Sydney, NSW 2000, Australia';
            $this->config->form_title =
                'Send us your message and we will contact you the soonest.';
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

        $this->content->main_title = $this->config->main_title ?? '';
        $this->content->main_description = $this->config->main_description ?? '';
        $this->content->title_placement = $this->config->title_placement ?? 0;
        $this->content->phone = $this->config->phone ?? '';
        $this->content->email = $this->config->email ?? '';
        $this->content->working_hours = $this->config->working_hours ?? '';
        $this->content->location = $this->config->location ?? '';
        $this->content->form_title = $this->config->form_title ?? '';

        $firstnametext = get_string('firstname', 'block_nice_contact_us_page_1');
        $lastnametext = get_string('lastname', 'block_nice_contact_us_page_1');
        $subjecttext = get_string('subject', 'block_nice_contact_us_page_1');
        $messagetext = get_string('message', 'block_nice_contact_us_page_1');
        $sendtext = get_string('send', 'block_nice_contact_us_page_1');
        $emailtext = get_string('email', 'block_nice_contact_us_page_1');

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
            <section class="nice-contact-us-page-1">
                <div class="container">
                    <div class="nice-contact-us-page">
                        <div class="nice-contact-us-main-title-container ' . $alignmentclass . '">
                            <h2 class="h2 mb-0 fw-bold">'
                                . format_text($this->content->main_title, FORMAT_HTML, ['filter' => true]) . '
                            </h2>
                        </div>
                        <div class="nice-contact-us-main-description-container">
                            <p class="m-0">'
                                . format_text($this->content->main_description, FORMAT_HTML, ['filter' => true]) . '
                            </p>
                        </div>';

        $text .= '<div class="nice-contact-us-items-container">';

        if (!empty($this->content->phone)) {
            $text .= '
                <div class="nice-contact-us-item d-flex align-items-center">
                    <div class="nice-contact-us-item-icon d-flex align-items-center justify-content-center rounded-circle">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <span>' . format_text($this->content->phone, FORMAT_HTML, ['filter' => true]) . '</span>
                </div>';
        }

        if (!empty($this->content->email)) {
            $emailformatted = format_text($this->content->email, FORMAT_HTML, ['filter' => true]);
            $text .= '
                <div class="nice-contact-us-item d-flex align-items-center">
                    <div class="nice-contact-us-item-icon d-flex align-items-center justify-content-center rounded-circle">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <span>
                        <a href="mailto:' . $emailformatted . '">' . $emailformatted . '</a>
                    </span>
                </div>';
        }

        if (!empty($this->content->working_hours)) {
            $text .= '
                <div class="nice-contact-us-item d-flex align-items-center">
                    <div class="nice-contact-us-item-icon d-flex align-items-center justify-content-center rounded-circle">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <span>'
                        . format_text($this->content->working_hours, FORMAT_HTML, ['filter' => true]) . '
                    </span>
                </div>';
        }

        if (!empty($this->content->location)) {
            $text .= '
                <div class="nice-contact-us-item d-flex align-items-center">
                    <div class="nice-contact-us-item-icon d-flex align-items-center justify-content-center rounded-circle">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <span>'
                        . format_text($this->content->location, FORMAT_HTML, ['filter' => true]) . '
                    </span>
                </div>';
        }

        $text .= '
            <div class="nice-contact-us-form-container mx-auto nice-border-radius nice-background-white">
                <form method="post" action="' . $CFG->wwwroot . '/local/contact/index.php">
                    <div class="nice-contact-us-form-title">
                        <p class="m-0">'
                            . format_text($this->content->form_title, FORMAT_HTML, ['filter' => true]) . '
                        </p>
                    </div>
                    <div class="mt-3">
                        <input class="form-control" type="text" name="firstname" required placeholder="'
                            . $firstnametext . '" maxlength="45" />
                    </div>
                    <div class="mt-3">
                        <input class="form-control" type="text" name="lastname" required placeholder="'
                            . $lastnametext . '" maxlength="45" />
                    </div>
                    <div class="mt-3">
                        <input class="form-control" type="email" name="email" required placeholder="'
                            . $emailtext . '" maxlength="45" />
                    </div>
                    <div class="mt-3">
                        <input class="form-control" type="text" name="subject" required placeholder="'
                            . $subjecttext . '" maxlength="45" />
                    </div>
                    <div class="mt-3">
                        <textarea id="message" class="form-control" name="message" rows="5" minlength="15" required placeholder="'
                            . $messagetext . '"></textarea>
                    </div>
                    <div class="mt-3">
                        <button type="submit" name="submit" class="btn btn-primary">'
                            . $sendtext . '
                        </button>
                    </div>
                    <input type="hidden" name="sesskey" value="' . sesskey() . '" />
                    <input type="hidden" name="name" value="name" />
                </form>
            </div>';

        $text .= '</div></div></div></section>';

        $this->content->footer = '';
        $this->content->text = $text;

        return $this->content;
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
