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
 * Class definition for the block_nice_boxes_3.
 *
 * @package     block_nice_boxes_3
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_nice_boxes_3 extends block_base {

    /**
     * Initialization method.
     *
     * @return void
     */
    public function init(): void {
        $this->title = get_string('pluginname', 'block_nice_boxes_3');
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
            $this->config->main_title = 'Why Choose US';

            for ($i = 1; $i <= 4; $i++) {
                $field = "image{$i}_default";
                $this->config->$field = $CFG->wwwroot .
                    '/theme/nice/pix/blocks/boxes/nice_box_' . $i . '.png';
            }

            $defaulttitles = [
                1 => "Personalized learning experiences just for you.",
                2 => "Expert instructors with real-world experience.",
                3 => "Flexible learning options to fit your schedule.",
                4 => "Advanced resources for continuous learning."
            ];

            for ($i = 1; $i <= 4; $i++) {
                $titlefield = "title{$i}";
                if (!isset($this->config->$titlefield)) {
                    $this->config->$titlefield = $defaulttitles[$i];
                }
            }
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
            ? $this->config->main_title
            : '';

        $this->content->main_description = !empty($this->config->main_description)
            ? $this->config->main_description
            : '';

        $this->content->title_placement = !empty($this->config->title_placement)
            ? $this->config->title_placement
            : 0;

        $formattedtitle = format_text(
            $this->content->main_title,
            FORMAT_HTML,
            ['filter' => true]
        );

        $formatteddescription = format_text(
            $this->content->main_description,
            FORMAT_HTML,
            ['filter' => true]
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

        $titleclass = 'nice-boxes-main-title';
        if (!empty($formatteddescription) && trim($formatteddescription) !== '') {
            $titleclass .= ' description-is-not-empty';
        }

        $this->content->text = '
            <div class="boxes-nice-3">
            <div class="nice-boxes-one nice-boxes-container d-flex align-items-center justify-content-center">
                <div class="container">
                    <div class="' . $titleclass . '">
                        <div class="h2 mb-0 fw-bold ' . $alignmentclass . '">'
                            . $formattedtitle . '
                        </div>
                    </div>
                    <div class="nice-boxes-main-description">
                        <p>'
                            . $formatteddescription . '
                        </p>
                    </div>
                    <div class="row">';

        for ($i = 1; $i <= 4; $i++) {
            $image = 'image' . $i;
            $fs = get_file_storage();

            $files = $fs->get_area_files(
                $this->context->id,
                'block_nice_boxes_3',
                'items',
                $i,
                'sortorder DESC, id ASC',
                false,
                0,
                0,
                1
            );

            $imgsrc = $this->config->{"image{$i}_default"};

            if (!empty($this->config->$image) && count($files) >= 1) {
                $mainfile = reset($files);
                $mainfilename = $mainfile->get_filename();
                $imgsrc = moodle_url::make_file_url(
                    "$CFG->wwwroot/pluginfile.php",
                    "/{$this->context->id}/block_nice_boxes_3/items/$i/$mainfilename"
                );
            }

            $title = isset($this->config->{"title{$i}"})
                ? format_text(
                    $this->config->{"title{$i}"},
                    FORMAT_HTML,
                    ['filter' => true]
                )
                : '';

            $this->content->text .= '
                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
                    <div class="nice-box-container nice-background-white nice-border-radius position-relative" 
                        data-nice-box-number="box' . $i . '">
                        <div class="nice-box-image-container position-absolute nice-background-white rounded-circle">
                            <div class="nice-box-image">
                                <img src="' . $imgsrc . '" alt="' . $title . ' ' . $i . '" />
                            </div>
                        </div>
                        <div class="nice-box-title-container">
                            <p class="nice-box-title m-0 fw-bold">'
                                . $title . '
                            </p>
                        </div>
                    </div>
                </div>';
        }

        $this->content->text .= '
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
            'accepted_types' => ['.jpg', '.png', '.gif'],
        ];

        for ($i = 1; $i <= 4; $i++) {
            $field = 'image' . $i;
            if (!isset($data->$field)) {
                continue;
            }
            file_save_draft_area_files(
                $data->$field,
                $this->context->id,
                'block_nice_boxes_3',
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
