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
 * Class definition for the block_nice_about_us_1.
 *
 * @package     block_nice_about_us_1
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_nice_about_us_1 extends block_base {

    /**
     * Initialization method.
     *
     * @return void
     */
    public function init(): void {
        $this->title = get_string('pluginname', 'block_nice_about_us_1');
    }

    /**
     * Specialization method to process block-specific configurations.
     *
     * @return void
     */
    public function specialization(): void {
        global $CFG, $DB;

        // Include external PHP file required for this block's specialization.
        include($CFG->dirroot . '/theme/nice/inc/block_handler/specialization.php');

        if (empty($this->config)) {
            $this->config = new \stdClass();
            $this->config->main_title = 'About US';
            $this->config->main_description = 'In an ever-evolving world, learning stands as the beacon that guides '
                . 'individuals towards personal and professional growth. It is not merely the absorption of facts, '
                . 'but the cultivation of a mindset that is curious, adaptable, and open to new experiences. Whether '
                . 'we are diving deep into academic subjects or picking up life skills, the process of learning '
                . 'enriches our understanding, broadens our horizons, and equips us with tools to navigate the '
                . 'challenges and opportunities life presents.';
            $this->config->default_image = $CFG->wwwroot
                . '/theme/nice/pix/blocks/aboutus/nice_about_us_1.jpg';
        }
    }

    /**
     * Generate the block's content.
     *
     * @return stdClass
     */
    public function get_content(): stdClass {
        global $CFG, $DB;
        require_once($CFG->libdir . '/filelib.php');

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();

        $this->content->main_title = !empty($this->config->main_title)
            ? $this->config->main_title
            : '';

        $this->content->title_placement = !empty($this->config->title_placement)
            ? $this->config->title_placement
            : 0;

        $this->content->main_description = !empty($this->config->main_description)
            ? $this->config->main_description
            : '';

        $this->content->main_link = !empty($this->config->main_link)
            ? $this->config->main_link
            : '';

        $this->content->image = !empty($this->config->default_image)
            ? $this->config->default_image
            : '';

        $fs = get_file_storage();

        $files = $fs->get_area_files(
            $this->context->id,
            'block_nice_about_us_1',
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

        $alignmentclass = 'text-start';

        switch ($this->content->title_placement) {
            case 1:
                $alignmentclass = 'text-end';
                break;
            case 2:
                $alignmentclass = 'text-center';
                break;
            default:
                $alignmentclass = 'text-start';
                break;
        }

        $readmoretext = get_string('config_read_more', 'block_nice_about_us_1');

        $button = '';
        if (!empty($this->content->main_link)) {
            $button = '
                <div class="nice-about-us-button-container">
                    <a href="' . htmlspecialchars($this->content->main_link) . '" 
                        class="btn btn-primary" aria-label="Read More">
                        ' . $readmoretext . '
                    </a>
                </div>
            ';
        }

        $text = '
            <section class="nice-about-us-1 position-relative z-1">
                <div class="nice-about-us-background-image-container">
                    <img class="position-absolute nice-about-us-background-image" 
                        src="' . $this->content->image . '" 
                        alt="' . format_text(
                            $this->content->main_title,
                            FORMAT_HTML,
                            ['filter' => true]
                        ) . '">
                </div>
                <div class="nice-about-us-container">
                    <div class="container">
                        <div class="nice-about-us-title-container">
                            <div class="h2 mb-0 fw-bold ' . $alignmentclass . '">
                                ' . format_text(
                                    $this->content->main_title,
                                    FORMAT_HTML,
                                    ['filter' => true]
                                ) . '
                            </div>
                        </div>
                        <div class="nice-about-us-content-container">
                            <p class="mb-0">
                                ' . format_text(
                                    $this->content->main_description,
                                    FORMAT_HTML,
                                    ['filter' => true]
                                ) . '
                            </p>
                        </div>
                        ' . $button . '
                    </div>
                    <div class="position-absolute nice-about-us-overlay nice-color-black"></div>
                </div>
            </section>
        ';

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
