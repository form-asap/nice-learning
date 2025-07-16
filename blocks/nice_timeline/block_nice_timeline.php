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

/**
 * Class definition for the block_nice_timeline.
 *
 * @package     block_nice_timeline
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_nice_timeline extends block_base {

    /**
     * Initialization method.
     *
     * @return void
     */
    public function init(): void {
        $this->title = get_string('pluginname', 'block_nice_timeline');
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

            $this->config->main_title = 'Timeline';

            $defaultdates = [
                1 => "2020",
                2 => "2021",
                3 => "2022",
                4 => "2023",
            ];

            for ($i = 1; $i <= 4; $i++) {
                $datefield = "date{$i}";
                if (!isset($this->config->$datefield)) {
                    $this->config->$datefield = $defaultdates[$i];
                }
            }

            $defaulttitles = [
                1 => "Timeline Item Title One",
                2 => "Timeline Item Title Two",
                3 => "Timeline Item Title Three",
                4 => "Timeline Item Title Four",
            ];

            for ($i = 1; $i <= 4; $i++) {
                $titlefield = "title{$i}";
                if (!isset($this->config->$titlefield)) {
                    $this->config->$titlefield = $defaulttitles[$i];
                }
            }

            $defaultdescription = [
                1 => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
                2 => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
                3 => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
                4 => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
            ];

            for ($i = 1; $i <= 4; $i++) {
                $descriptionfield = "description{$i}";
                if (!isset($this->config->$descriptionfield)) {
                    $this->config->$descriptionfield = $defaultdescription[$i];
                }
            }

            for ($i = 1; $i <= 4; $i++) {
                $field = "image{$i}_default";
                $this->config->$field = $CFG->wwwroot
                    . '/theme/nice/pix/blocks/timeline/timeline.png';
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

        $this->content->text = '
<section class="nice-timeline-section">
    <div class="container">
        <div class="nice-timeline-main-title-container">
            <div class="h2 mb-0 fw-bold ' . $alignmentclass . '">'
            . $formattedtitle .
            '</div>
        </div>
        <div class="nice-timeline-main-description-container">
            <p>' . $formatteddescription . '</p>
        </div>
    </div>
    <div class="nice-timeline-items position-relative d-flex flex-wrap mx-auto">';

        $timelinecount = 0;
        for ($i = 1; $i <= 10; $i++) {
            if (!empty($this->config->{"title{$i}"})) {
                $timelinecount++;
            }
        }
        $timelinecount = max($timelinecount, 2);

        for ($i = 1; $i <= $timelinecount; $i++) {
            $imagefield = 'image' . $i;
            $defaultimagefield = "image{$i}_default";
            $fs = get_file_storage();

            $files = $fs->get_area_files(
                $this->context->id,
                'block_nice_timeline',
                'items',
                $i,
                'sortorder DESC, id ASC',
                false,
                0,
                0,
                1
            );

            $imgsrc = $this->config->$defaultimagefield;

            if (!empty($this->config->$imagefield) && count($files) >= 1) {
                $mainfile = reset($files);
                $filename = $mainfile->get_filename();
                $imgsrc = moodle_url::make_file_url(
                    "$CFG->wwwroot/pluginfile.php",
                    "/{$this->context->id}/block_nice_timeline/items/{$i}/{$filename}"
                );
            }

            $title = isset($this->config->{"title{$i}"})
                ? format_text($this->config->{"title{$i}"}, FORMAT_HTML, ['filter' => true])
                : '';

            $date = isset($this->config->{"date{$i}"})
                ? format_text($this->config->{"date{$i}"}, FORMAT_HTML, ['filter' => true])
                : '';

            $description = isset($this->config->{"description{$i}"})
                ? format_text($this->config->{"description{$i}"}, FORMAT_HTML, ['filter' => true])
                : '';

            $this->content->text .= '
        <div class="nice-timeline-item position-relative">
            <div class="nice-timeline-dot position-absolute"></div>
            <div class="nice-timeline-date">' . $date . '</div>
            <div class="nice-timeline-container nice-background-white nice-border-radius text-start">
                <h3 class="nice-timeline-title fw-bold text-capitalize">' . $title . '</h3>
                <p class="nice-timeline-content">' . $description . '</p>
                <div class="nice-timeline-image">
                    <img class="nice-border-radius" src="' . $imgsrc . '" alt="' . $title . ' ' . $i . '"/>
                </div>
            </div>
        </div>';
        }

        $this->content->text .= '
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
        global $CFG;

        $filemanageroptions = [
            'maxbytes' => $CFG->maxbytes,
            'subdirs' => 0,
            'maxfiles' => 1,
            'accepted_types' => ['.jpg', '.png', '.gif']
        ];

        for ($i = 1; $i <= 10; $i++) {
            $field = 'image' . $i;
            if (!isset($data->$field)) {
                continue;
            }
            file_save_draft_area_files(
                $data->$field,
                $this->context->id,
                'block_nice_timeline',
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
