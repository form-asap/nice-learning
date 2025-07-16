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

/**
 * Class definition for the block_nice_course_features
 *
 * @package     block_nice_course_features
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_nice_course_features extends block_base {

    /**
     * Initialization method.
     *
     * @return void
     */
    public function init(): void {
        $this->title = get_string('pluginname', 'block_nice_course_features');
    }

    /**
     * Specialization method to process block-specific configurations.
     *
     * @return void
     */
    public function specialization(): void {
        global $CFG, $DB;

        // Include an external specialization handler file.
        include($CFG->dirroot . '/theme/nice/inc/block_handler/specialization.php');

        if (empty($this->config)) {
            $this->config = new \stdClass();

            // Set the default main title for the block.
            $this->config->main_title = 'Course features';

            // Set default icons for each course feature.
            $defaulticons = [
                1 => '<i class="fa-solid fa-note-sticky"></i>',
                2 => '<i class="fa-solid fa-pen"></i>',
                3 => '<i class="fa-regular fa-clock"></i>',
                4 => '<i class="fa-solid fa-language"></i>'
            ];

            for ($i = 1; $i <= 4; $i++) {
                $iconfield = "icon{$i}";
                if (!isset($this->config->$iconfield)) {
                    $this->config->$iconfield = $defaulticons[$i];
                }
            }

            // Set default titles for each course feature.
            $defaulttitles = [
                1 => "Topics",
                2 => "Assignments",
                3 => "Duration",
                4 => "Language"
            ];

            for ($i = 1; $i <= 4; $i++) {
                $titlefield = "title{$i}";
                if (!isset($this->config->$titlefield)) {
                    $this->config->$titlefield = $defaulttitles[$i];
                }
            }

            // Set default values for each course feature.
            $defaultvalues = [
                1 => "7",
                2 => "1",
                3 => "30 Learning hours",
                4 => "English"
            ];

            for ($i = 1; $i <= 4; $i++) {
                $valuefield = "value{$i}";
                if (!isset($this->config->$valuefield)) {
                    $this->config->$valuefield = $defaultvalues[$i];
                }
            }
        }
    }

    /**
     * Generate the block's content.
     *
     * @return stdClass
     */
    public function get_content(): ?stdClass {
        global $CFG, $DB;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();

        $this->content->main_title = !empty($this->config->main_title) ? $this->config->main_title : '';
        $formattedtitle = format_text($this->content->main_title, FORMAT_HTML, ['filter' => true]);

        $this->content->text = '
        <section class="nice-course-features-container">
            <div class="nice-course-features">
                <div class="container">
                    <div class="nice-course-features-title-container">
                        <h5>' . $formattedtitle . '</h5>
                    </div>
                </div>
                <div class="container">';

        $coursefeatures = 0;
        for ($i = 1; $i <= 10; $i++) {
            if (!empty($this->config->{"title{$i}"})) {
                $coursefeatures++;
            }
        }

        $coursefeatures = max($coursefeatures, 2);

        for ($i = 1; $i <= $coursefeatures; $i++) {
            $title = isset($this->config->{"title{$i}"})
                ? format_text($this->config->{"title{$i}"}, FORMAT_HTML, ['filter' => true])
                : '';

            $icon = isset($this->config->{"icon{$i}"})
                ? format_text($this->config->{"icon{$i}"}, FORMAT_HTML, ['filter' => true])
                : '';

            $value = isset($this->config->{"value{$i}"})
                ? format_text($this->config->{"value{$i}"}, FORMAT_HTML, ['filter' => true])
                : '';

            $this->content->text .= '
                <div class="nice-course-features-content-container d-flex align-items-center justify-content-between nice-background-light-grey nice-border-radius">
                    <div class="nice-course-features-content d-flex align-items-center">
                        <span>' . $icon . '</span>
                        <span>' . $title . '</span>
                    </div>
                    <span>' . $value . '</span>
                </div>';
        }

        $this->content->text .= '
                </div>
            </div>
        </section>';

        $this->content->footer = '';
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
     * Specifies where this block can be added.
     *
     * @return array Allowed formats.
     */
    public function applicable_formats(): array {
        return [
            'all' => false,
            'my' => false,
            'admin' => false,
            'course-view' => true,
            'course' => false,
        ];
    }
}
