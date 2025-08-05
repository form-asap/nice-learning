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

require_once($CFG->dirroot . '/theme/nice/inc/course_handler/nice_course_handler.php');

require_once($CFG->libdir . '/filelib.php');

/**
 * Class definition for the block_nice_categories_slider_2.
 *
 * @package    block_nice_categories_slider_2
 * @copyright  2025 Nice Learning <support@docs.nicelearning.org>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_nice_categories_slider_2 extends block_base {

    /**
     * Initialization method.
     *
     * @return void
     */
    public function init(): void {
        $this->title = get_string('pluginname', 'block_nice_categories_slider_2');
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
            $this->config->main_title = 'All Categories';
            $this->config->link = $CFG->wwwroot . '/course';
        }
    }

    /**
     * Trims long text.
     *
     * @param string $text The text.
     * @param int $length Maximum length.
     * @return string Trimmed text.
     */
    private function trimdescription(string $text, int $length = 100): string {
        if (strlen($text) <= $length) {
            return $text;
        }

        $text = substr($text, 0, $length);
        $lastspace = strrpos($text, ' ');

        if ($lastspace !== false) {
            $text = substr($text, 0, $lastspace);
        }

        return $text . ' ...';
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
            ? $this->config->main_title : '';

        $this->content->main_description = !empty($this->config->main_description)
            ? $this->config->main_description : '';

        $this->content->title_placement = !empty($this->config->title_placement)
            ? $this->config->title_placement : 0;

        $this->content->description = !empty($this->config->description)
            ? $this->config->description : '';

        $this->content->link = !empty($this->config->link)
            ? $this->config->link : '';

        $this->config->show_count = isset($this->config->show_count)
            ? $this->config->show_count : 1;

        $this->config->show_description = isset($this->config->show_description)
            ? $this->config->show_description : 1;

        $viewallcourses = get_string('view_all_courses', 'block_nice_categories_slider_2');
        $chooseitems = get_string('choose_items', 'block_nice_categories_slider_2');

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
            <section class="nice-categories-slider-2">
                <div class="nice-categories-slider-container">
                    <div class="container">
                        <div class="nice-categories-slider-title-container">
                            <div class="h2 mb-0 fw-bold ' . $alignmentclass . '">'
                                . format_text($this->content->main_title, FORMAT_HTML, ['filter' => true]) . '
                            </div>
                        </div>
                        <div class="nice-categories-slider-description-container">
                            <p class="m-0">'
                                . format_text($this->content->main_description, FORMAT_HTML, ['filter' => true]) . '
                            </p>
                        </div>
                    </div>
                    <div class="container">
                        <div class="owl-carousel nice-categories-slider-two">';

        if (!empty($this->config->categories)) {
            foreach ($this->config->categories as $categoryid) {
                $categoryrecord = $DB->get_record('course_categories', ['id' => $categoryid], '*');

                // Skip hidden categories
                if (!$categoryrecord || !$categoryrecord->visible) {
                    continue;
                }
                
                $categoryobj = core_course_category::get($categoryid);
                $categoryrecord = $DB->get_record(
                    'course_categories',
                    ['id' => $categoryid],
                    'description'
                );

                $context = context_coursecat::instance($categoryid);
                $description = file_rewrite_pluginfile_urls(
                    $categoryrecord->description,
                    'pluginfile.php',
                    $context->id,
                    'coursecat',
                    'description',
                    null
                );

                preg_match('/<img[^>]+src="([^">]+)"/', $description, $matches);
                $imgurl = $matches[1] ?? '';

                if (empty($imgurl)) {
                    $imgurl = $CFG->wwwroot . "/theme/nice/pix/blocks/categories/category.png";
                }

                $plaindescription = trim(strip_tags($categoryobj->description ?? ''));
                $trimmeddescription = $this->trimdescription($plaindescription, 97);

                $countcontent = '';
                if ($this->config->show_count) {
                    $countcontent = '
                        <div class="nice-category-count-container d-flex align-items-center justify-content-center gap-1 position-absolute fw-bold text-center nice-border-radius nice-background-main nice-color-white">
                            <span><i class="fa-solid fa-book-open"></i></span>
                            <span>' . $categoryobj->coursecount . '</span>
                        </div>';
                }

                $descriptioncontent = '';
                if ($this->config->show_description) {
                    $descriptioncontent = '
                        <div class="nice-category-card-description-container">
                            <p>' . trim($trimmeddescription) . '</p>
                        </div>';
                }

                $descriptionclass = $this->config->show_description
                    ? "description-is-active"
                    : "description-is-hidden";

                $text .= '
                    <a href="' . $CFG->wwwroot . '/course/index.php?categoryid=' . $categoryobj->id . '">
                        <div class="nice-category-card-container position-relative" data-nice-category-id="' . $categoryobj->id . '">
                            <div class="nice-category-card nice-border-radius nice-background-white">
                                <div class="nice-category-card-image-container position-relative">
                                    <img class="' . $descriptionclass . '" src="' . $imgurl . '" alt="' . format_text($categoryobj->name, FORMAT_HTML, ['filter' => true]) . '" />
                                    <div class="nice-category-card-image-title z-1 w-100 fw-bold text-center position-absolute">
                                        <div class="h4 fw-bold nice-color-white m-0">'
                                            . format_text($categoryobj->name, FORMAT_HTML, ['filter' => true]) . '
                                        </div>
                                    </div>
                                    <div class="nice-category-card-image-overlay position-absolute ' . $descriptionclass . '"></div>
                                </div>
                                <div class="nice-category-card-content-container m-0 ' . $descriptionclass . '">'
                                    . $descriptioncontent . $countcontent . '
                                </div>
                            </div>
                        </div>
                    </a>';
            }
        } else {
            $text .= '
                <span class="nice-color-yellow">'
                    . $chooseitems . '
                </span>';
        }

        $text .= '
                        </div>
                        <div class="nice-category-card-button-container text-center">
                            <a href="' . htmlspecialchars($this->content->link) . '" class="btn btn-primary">'
                                . $viewallcourses . '
                            </a>
                        </div>
                    </div>
                </div>
            </section>';

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
