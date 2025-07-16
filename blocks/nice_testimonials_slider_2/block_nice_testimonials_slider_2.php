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
 * Class definition for the block_nice_testimonials_slider_2.
 *
 * @package     block_nice_testimonials_slider_2
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_nice_testimonials_slider_2 extends block_base {

    /**
     * Initialization method.
     *
     * @return void
     */
    public function init(): void {
        $this->title = get_string('pluginname', 'block_nice_testimonials_slider_2');
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

            $this->config->main_title = 'Testimonials';

            for ($i = 1; $i <= 4; $i++) {
                $field = "image{$i}_default";
                $this->config->$field = $CFG->wwwroot
                    . "/theme/nice/pix/blocks/testimonials/nice_testimonial_{$i}.jpg";
            }

            $defaultnames = [
                1 => "Jack Wiliam",
                2 => "Liam Noah",
                3 => "Elijah Benjamin",
                4 => "Lucas Owen"
            ];

            for ($i = 1; $i <= 4; $i++) {
                $namefield = "name{$i}";
                if (!isset($this->config->$namefield)) {
                    $this->config->$namefield = $defaultnames[$i];
                }
            }

            $defaultposition = [
                1 => "Systems Administrator",
                2 => "Software Developer",
                3 => "Frontend Engineer",
                4 => "Network Engineer"
            ];

            for ($i = 1; $i <= 4; $i++) {
                $positionfield = "position{$i}";
                if (!isset($this->config->$positionfield)) {
                    $this->config->$positionfield = $defaultposition[$i];
                }
            }

            $defaultdescription = [
                1 => "Ever since we switched to this theme, our user engagement has skyrocketed. "
                    . "The modern design and intuitive layout is something our visitors always compliment us on. "
                    . "Truly a game-changer!",
                2 => "The attention to detail in this theme is unparalleled. Every feature feels tailor-made "
                    . "for our needs, providing an exceptional browsing experience for all users.",
                3 => "The flexibility and adaptability of this theme are astounding. It has infused our website "
                    . "with a fresh energy and vibrancy that we didn't know we were missing.",
                4 => "Navigating our site has never been smoother. This theme not only looks good but makes backend "
                    . "management a breeze. A seamless blend of form and function!"
            ];

            for ($i = 1; $i <= 4; $i++) {
                $descriptionfield = "description{$i}";
                if (!isset($this->config->$descriptionfield)) {
                    $this->config->$descriptionfield = $defaultdescription[$i];
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
<section class="nice-testimonials-slider-2">
    <div class="nice-testimonials-slider-container">
        <div class="container">
            <div class="nice-testimonials-slider-title-container">
                <div class="h2 mb-0 fw-bold ' . $alignmentclass . '">' . $formattedtitle . '</div>
            </div>
            <div class="nice-testimonials-slider-description-container">
                <p class="m-0">' . $formatteddescription . '</p>
            </div>
        </div>
        <div class="container">
            <div class="owl-carousel nice-testimonials-slider-two">';

        $testimonialcount = 0;
        for ($i = 1; $i <= 10; $i++) {
            if (!empty($this->config->{"name{$i}"})) {
                $testimonialcount++;
            }
        }
        $testimonialcount = max($testimonialcount, 4);

        for ($i = 1; $i <= $testimonialcount; $i++) {
            $imagefield = 'image' . $i;
            $defaultimagefield = "image{$i}_default";
            $fs = get_file_storage();

            $files = $fs->get_area_files(
                $this->context->id,
                'block_nice_testimonials_slider_2',
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
                    "/{$this->context->id}/block_nice_testimonials_slider_2/items/{$i}/{$filename}"
                );
            }

            $name = !empty($this->config->{"name{$i}"})
                ? format_text($this->config->{"name{$i}"}, FORMAT_HTML, ['filter' => true])
                : '';

            $position = !empty($this->config->{"position{$i}"})
                ? format_text($this->config->{"position{$i}"}, FORMAT_HTML, ['filter' => true])
                : '';

            $description = !empty($this->config->{"description{$i}"})
                ? format_text($this->config->{"description{$i}"}, FORMAT_HTML, ['filter' => true])
                : '';

            $this->content->text .= '
                <div class="nice-testimonial-card-container">
                    <div class="nice-testimonial-card nice-border-radius nice-background-white position-relative">
                        <div class="nice-testimonial-description-container">
                            <p>' . $description . '</p>
                        </div>
                        <div class="nice-testimonial-quote-contianer position-absolute">
                            <i class="fa-solid fa-quote-left"></i>
                        </div>
                        <div class="nice-testimonial-card-image-container text-center">
                            <div class="nice-testimonial-card-image position-relative">
                                <img class="rounded-circle mx-auto" src="' . $imgsrc . '" alt="' . $name . ' ' . $i . '"/>
                                <div class="nice-testimonial-card-image-overlay position-absolute rounded-circle m-auto"></div>
                            </div>
                            <div>
                                <div class="nice-testimonial-card-image-name fw-bold">
                                    <span>' . $name . '</span>
                                </div>
                                <div class="nice-testimonial-card-image-position">
                                    <span>' . $position . '</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
        }

        $this->content->text .= '
            </div>
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
                'block_nice_testimonials_slider_2',
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
