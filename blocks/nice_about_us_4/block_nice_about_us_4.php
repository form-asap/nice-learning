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
 * Class definition for the block_nice_about_us_4.
 *
 * @package     block_nice_about_us_4
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_nice_about_us_4 extends block_base {

    /**
     * Initialization method.
     *
     * @return void
     */
    public function init(): void {
        $this->title = get_string('pluginname', 'block_nice_about_us_4');
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

            // Loop to set default image paths for each about us item.
            for ($i = 1; $i <= 2; $i++) {
                $field = "image{$i}_default";
                $this->config->$field = $CFG->wwwroot
                    . '/theme/nice/pix/blocks/aboutus/nice_about_us_4_' . $i . '.jpg';
            }

            $defaulttitles = [
                1 => "Personalized learning experiences just for you.",
                2 => "Expert instructors with real-world experience.",
            ];

            for ($i = 1; $i <= 2; $i++) {
                $titlefield = "title{$i}";
                if (!isset($this->config->$titlefield)) {
                    $this->config->$titlefield = $defaulttitles[$i];
                }
            }

            $defaultdescriptions = [
                1 => "Online courses deliver unparalleled flexibility, enabling students to manage their academic "
                    . "commitments alongside work and family obligations—making education more accessible.",
                2 => "The vibrant campus life nurtures a sense of community and belonging, enriching both academic and "
                    . "social experiences in a diverse and inclusive environment.",
            ];

            for ($i = 1; $i <= 2; $i++) {
                $descriptionfield = "description{$i}";
                if (!isset($this->config->$descriptionfield)) {
                    $this->config->$descriptionfield = $defaultdescriptions[$i];
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
        global $CFG, $DB;

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
            default:
                $alignmentclass = 'text-start';
                break;
        }

        $this->content->text = '
            <div class="nice-about-us-4">
                <div class="nice-about-us-container">
                    <div class="container">
                        <div class="nice-about-us-main-title">
                            <div class="h2 mb-0 fw-bold ' . $alignmentclass . '">'
                                . $formattedtitle . '
                            </div>
                        </div>
                        <div class="nice-about-us-main-description">
                            <p class="m-0">'
                                . $formatteddescription . '
                            </p>
                        </div>
                        <div class="owl-carousel nice-about-us-four">';

        $itemcount = 0;
        for ($i = 1; $i <= 20; $i++) {
            if (!empty($this->config->{"title{$i}"})) {
                $itemcount++;
            }
        }
        $itemcount = max($itemcount, 2);

        for ($i = 1; $i <= $itemcount; $i++) {
            $imagefield = 'image' . $i;
            $fs = get_file_storage();

            $files = $fs->get_area_files(
                $this->context->id,
                'block_nice_about_us_4',
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
                    "/{$this->context->id}/block_nice_about_us_4/items/" . $i . '/' . $mainfilename
                );
            }

            $title = isset($this->config->{"title{$i}"})
                ? format_text($this->config->{"title{$i}"}, FORMAT_HTML, ['filter' => true])
                : '';

            $description = isset($this->config->{"description{$i}"})
                ? format_text($this->config->{"description{$i}"}, FORMAT_HTML, ['filter' => true])
                : '';

            $this->content->text .= '
                <div class="nice-about-us-box-container">
                    <div class="row align-items-center">
                        <div class="col-lg-7 col-md-6">
                            <div class="nice-about-us-box-image-container position-relative">
                                <img class="object-fit-cover img-fluid w-100 height-100 nice-border-radius"
                                    src="' . $imgsrc . '"
                                    alt="' . $title . ' ' . $i . '"/>
                                <div class="position-absolute nice-border-radius nice-about-us-box-image-overlay"></div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-6">
                            <div class="nice-about-us-content-container nice-border-radius nice-background-light-grey">
                                <div class="h4 fw-bold">'
                                    . $title . '
                                </div>
                                <p class="mb-0">'
                                    . $description . '
                                </p>
                            </div>
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
            'accepted_types' => ['.jpg', '.png', '.gif']
        ];

        for ($i = 1; $i <= 4; $i++) {
            $field = 'image' . $i;
            if (!isset($data->$field)) {
                continue;
            }
            file_save_draft_area_files(
                $data->$field,
                $this->context->id,
                'block_nice_about_us_4',
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
