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
 * Class definition for the block_nice_hero_2
 *
 * @package    block_nice_hero_2
 * @copyright  2025 Nice Learning <support@docs.nicelearning.org>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_nice_hero_2 extends block_base {

    /**
     * Initialization method.
     *
     * @return void
     */
    public function init(): void {
        $this->title = get_string('pluginname', 'block_nice_hero_2');
    }

    /**
     * Specialization method to process block-specific configurations.
     *
     * @return void
     */
    public function specialization(): void {
        global $CFG, $DB;

        // Include specialization handler.
        include($CFG->dirroot . '/theme/nice/inc/block_handler/specialization.php');

        if (empty($this->config)) {
            $this->config = new stdClass();
            $this->config->title = 'Learning and education are the heart of every endeavor.';
            $this->config->default_image = $CFG->wwwroot .
                '/theme/nice/pix/blocks/hero/nice_hero_2.jpg';
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

        $this->content->title = !empty($this->config->title)
            ? $this->config->title : '';

        $this->content->description = !empty($this->config->description)
            ? $this->config->description : '';

        $this->content->image = !empty($this->config->default_image)
            ? $this->config->default_image : '';

        $fs = get_file_storage();

        $files = $fs->get_area_files(
            $this->context->id,
            'block_nice_hero_2',
            'content'
        );

        foreach ($files as $file) {
            $filename = $file->get_filename();
            if ($filename !== '.' && $filename !== '..') {
                $url = moodle_url::make_pluginfile_url(
                    $file->get_contextid(),
                    $file->get_component(),
                    $file->get_filearea(),
                    null,
                    $file->get_filepath(),
                    $filename
                );
                $this->content->image = $url;
                break;
            }
        }

        $readmorebutton = '';

        if (isset($this->config->show_read_more) && $this->config->show_read_more == 1) {
            $readmorelink = isset($this->config->read_more_link)
                ? $this->config->read_more_link : '#';

            $readmoretext = get_string('config_read_more', 'block_nice_hero_2');

            $readmorebutton = '
                <div class="nice-button-container">
                    <a class="nice-button nice-background-white nice-border-radius fw-bold nice-color-black"
                       href="' . $readmorelink . '">
                        ' . $readmoretext . '
                    </a><!--End nice-button-->
                </div><!--End nice-button-container-->';
        }

        $alttext = format_text(
            $this->content->title,
            FORMAT_HTML,
            ['filter' => true]
        );

        $text = '
            <section class="nice-hero hero-nice-2 position-relative">
                <div class="nice-hero-image-container w-100">
                    <img class="w-100 h-100 object-fit-cover"
                         src="' . $this->content->image . '"
                         alt="' . $alttext . '" />
                </div><!--End nice-hero-image-container-->
                <div class="nice-hero-overlay w-100 h-100 position-absolute"></div><!--End nice-hero-overlay-->
                <div class="nice-hero-content-container w-100 position-absolute nice-color-white
                            d-flex align-items-center justify-content-center text-center">
                    <div class="nice-hero-content-size">
                        <h1 class="nice-hero-content-title fw-bold">'
                            . format_text(
                                $this->content->title,
                                FORMAT_HTML,
                                ['filter' => true]
                            ) . '
                        </h1><!--End nice-hero-content-title-->
                        ' . $readmorebutton . '
                    </div><!--End nice-hero-content-size-->
                </div><!--End nice-hero-content-container-->
            </section><!--End nice-hero-->';

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
