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
 * Class definition for the block_nice_accordion.
 *
 * @package     block_nice_accordion
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_nice_accordion extends block_base {

    /**
     * Initialization method.
     *
     * @return void
     */
    public function init(): void {
        $this->title = get_string('pluginname', 'block_nice_accordion');
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
            $this->config = new \stdClass();

            $this->config->main_title = 'Frequently Asked Questions';

            $defaulttitles = [
                1 => "Why choose our platform for online learning?",
                2 => "How do I troubleshoot access issues on the portal?",
            ];

            for ($i = 1; $i <= 2; $i++) {
                $titlefield = "title{$i}";
                if (!isset($this->config->$titlefield)) {
                    $this->config->$titlefield = $defaulttitles[$i];
                }
            }

            $defaultdescription = [
                1 => "Our platform offers a unique blend of engaging course material, interactive quizzes, "
                    . "and dedicated mentor support, ensuring that learners not only gain knowledge but also apply "
                    . "what they've learned in real-world contexts. With a diverse catalog of courses from renowned "
                    . "educators around the globe, we prioritize quality and practicality in every lesson.",
                2 => "If you're experiencing access issues, start by checking your internet connection and ensuring "
                    . "you're using the latest version of your browser. Clearing your browser's cache and cookies "
                    . "can also resolve many common issues. If problems persist, please reach out to our dedicated "
                    . "support team through the 'Contact Us' page, and we'll assist you promptly.",
            ];

            for ($i = 1; $i <= 2; $i++) {
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
        global $CFG, $DB;

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
            <section class="nice-accordion-container">
                <div class="nice-accordion">
                    <div class="container">
                        <div class="nice-accordion-main-title-container">
                            <div class="h2 mb-0 fw-bold ' . $alignmentclass . '">'
                                . $formattedtitle . '
                            </div>
                        </div>
                        <div class="nice-accordion-main-description-container m-0">
                            <p>'
                                . $formatteddescription . '
                            </p>
                        </div>
                    </div>
                    <div class="container">
                        <div class="accordion" id="nice-accordion-id">';

        $accordioncount = 0;
        for ($i = 1; $i <= 20; $i++) {
            if (!empty($this->config->{"title{$i}"})) {
                $accordioncount++;
            }
        }
        $accordioncount = max($accordioncount, 2);

        for ($i = 1; $i <= $accordioncount; $i++) {
            $title = isset($this->config->{"title{$i}"})
                ? format_text($this->config->{"title{$i}"}, FORMAT_HTML, ['filter' => true])
                : '';

            $description = isset($this->config->{"description{$i}"})
                ? format_text($this->config->{"description{$i}"}, FORMAT_HTML, ['filter' => true])
                : '';

            $showfirst = isset($this->config->show_first) ? $this->config->show_first : 1;
            $linkcollapse = isset($this->config->link_collapse) ? $this->config->link_collapse : 1;
            $iconclass = ($showfirst && $i == 1) ? 'fa-minus' : 'fa-plus';

            $this->content->text .= '
                <div class="nice-accordion-content-container nice-border-radius nice-background-white">
                    <div class="nice-accordion-content" id="headingOne-' . $i . '">
                        <h2 class="mb-0">
                            <button class="btn p-0 btn-link nice-color-black w-100 d-flex align-items-center justify-content-between'
                                . ($i == 1 && $showfirst == 1 ? ' nice-color-main' : '') . '"
                                type="button" data-toggle="collapse" data-target="#nice-accordion--' . $i . '"
                                aria-expanded="' . ($i == 1 && $showfirst == 1 ? 'true' : 'false') . '"
                                aria-controls="collapse-' . $i . '">
                                <span class="fw-bold">' . $title . '</span>
                                <span class="accordion-icon nice-color-black rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fa-solid ' . $iconclass . '"></i>
                                </span>
                            </button>
                        </h2>
                    </div>
                    <div id="nice-accordion--' . $i . '" class="collapse'
                        . ($i == 1 && $showfirst == 1 ? ' show' : '') . '"
                        aria-labelledby="headingOne-' . $i . '" '
                        . ($linkcollapse == 1 ? 'data-parent="#nice-accordion-id"' : '') . '>
                        <div class="nice-accordion-description-container">
                            <p>' . $description . '</p>
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
