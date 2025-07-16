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
 * Class definition for the block_nice_about_us_3.
 *
 * @package     block_nice_about_us_3
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_nice_about_us_3 extends block_base {

    /**
     * Initialization method.
     *
     * @return void
     */
    public function init(): void {
        $this->title = get_string('pluginname', 'block_nice_about_us_3');
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

            $this->config->main_title = 'About US';

            $this->config->main_description = [
                'text' => '<p>Online learning has revolutionized the educational landscape, democratizing access '
                    . 'to knowledge in an unprecedented manner. In an age where information is at our fingertips, '
                    . 'e-learning platforms like Moodle empower individuals from all corners of the globe to embark '
                    . 'on their educational journeys from the comfort of their homes. It provides flexibility, allowing '
                    . 'learners to pace themselves according to their schedules and commitments. Beyond the convenience, '
                    . 'online learning fosters a culture of self-discipline, as students take greater ownership of '
                    . 'their progress.</p>',
                'format' => FORMAT_HTML
            ];

            // Loop to set default image paths for each box.
            for ($i = 1; $i <= 2; $i++) {
                $field = "image{$i}_default";
                $this->config->$field = $CFG->wwwroot
                    . '/theme/nice/pix/blocks/aboutus/nice_about_us_3_' . $i . '.png';
            }
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

        if (empty($this->config->main_description)) {
            $this->config->main_description = [
                'text' => '<p>Online learning has revolutionized the educational landscape, democratizing access '
                    . 'to knowledge in an unprecedented manner. In an age where information is at our fingertips, '
                    . 'e-learning platforms like Moodle empower individuals from all corners of the globe to embark '
                    . 'on their educational journeys from the comfort of their homes. It provides flexibility, allowing '
                    . 'learners to pace themselves according to their schedules and commitments. Beyond the convenience, '
                    . 'online learning fosters a culture of self-discipline, as students take greater ownership of '
                    . 'their progress.</p>',
                'format' => FORMAT_HTML
            ];
        }

        $this->content->main_title = !empty($this->config->main_title)
            ? $this->config->main_title
            : '';

        $this->content->main_description = !empty($this->config->main_description)
            ? $this->config->main_description
            : '';

        $this->content->title_placement = !empty($this->config->title_placement)
            ? $this->config->title_placement
            : 0;

        $this->content->main_link = !empty($this->config->main_link)
            ? $this->config->main_link
            : '';

        $formattedtitle = format_text(
            $this->content->main_title,
            FORMAT_HTML,
            ['filter' => true]
        );

        $maindescriptioneditor = $this->config->main_description;
        $maindescription = format_text(
            $maindescriptioneditor['text'],
            $maindescriptioneditor['format'],
            ['context' => $this->context]
        );

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

        $readmoretext = get_string('config_read_more', 'block_nice_about_us_3');

        $button = '';
        if (!empty($this->content->main_link)) {
            $button = '
                <div class="nice-about-us-button-container">
                    <a href="' . htmlspecialchars($this->content->main_link) . '"
                        class="btn btn-primary">
                        ' . $readmoretext . '
                    </a>
                </div>
            ';
        }

        $this->content->text = '
            <div class="nice-about-us-3">
                <div class="nice-about-us-container">
                    <div class="container">
                        <div class="nice-about-us-title-container">
                            <div class="h2 mb-0 fw-bold ' . $alignmentclass . '">'
                                . $formattedtitle . '
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-7">
                                <div>'
                                    . $maindescriptioneditor['text'] . '
                                </div>'
                                . $button . '
                            </div>
                            <div class="col-lg-5">
                                <div class="nice-about-us-images-container d-flex align-items-center gap-4">';

        for ($i = 1; $i <= 2; $i++) {
            $imagefield = 'image' . $i;
            $fs = get_file_storage();

            $files = $fs->get_area_files(
                $this->context->id,
                'block_nice_about_us_3',
                'items',
                $i,
                'sortorder DESC, id ASC',
                false,
                0,
                0,
                1
            );

            $imgsrc = $this->config->{"image{$i}_default"};

            if (!empty($this->config->$imagefield) && count($files) >= 1) {
                $mainfile = reset($files);
                $mainfilename = $mainfile->get_filename();
                $imgsrc = moodle_url::make_file_url(
                    "$CFG->wwwroot/pluginfile.php",
                    "/{$this->context->id}/block_nice_about_us_3/items/" . $i . '/' . $mainfilename
                );
            }

            $this->content->text .= '
                <div class="position-relative nice-about-us-image-container-' . $i . '">
                    <img class="nice-border-radius" src="' . $imgsrc . '" alt="' . $formattedtitle . '"/>
                    <div class="nice-about-us-image-overlay"></div>
                </div>';
        }

        $this->content->text .= '
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';

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
        global $CFG;

        $filemanageroptions = [
            'maxbytes' => $CFG->maxbytes,
            'subdirs' => 0,
            'maxfiles' => 1,
            'accepted_types' => ['.jpg', '.png', '.gif']
        ];

        for ($i = 1; $i <= 2; $i++) {
            $field = 'image' . $i;
            if (!isset($data->$field)) {
                continue;
            }
            file_save_draft_area_files(
                $data->$field,
                $this->context->id,
                'block_nice_about_us_3',
                'items',
                $i,
                $filemanageroptions
            );
        }

        return parent::instance_config_save($data, $nolongerused);
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
