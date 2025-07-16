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

require_once($CFG->libdir . '/filelib.php');

require_once($CFG->dirroot . '/blog/lib.php');

require_once($CFG->dirroot . '/blog/locallib.php');

/**
 * Class definition for block_nice_blogs_slider_2.
 *
 * @package     block_nice_blogs_slider_2
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_nice_blogs_slider_2 extends block_base {

    /**
     * Initialization method.
     *
     * @return void
     */
    public function init(): void {
        $this->title = get_string('pluginname', 'block_nice_blogs_slider_2');
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
            $this->config = new \stdClass();
            $this->config->main_title = 'All Blogs';
            $this->config->link = $CFG->wwwroot . '/blog';
        }
    }

    /**
     * Generate the block's content.
     *
     * @return stdClass
     */
    public function get_content(): stdClass {
        global $CFG, $DB, $COURSE, $USER;

        // Use $this->page instead of global $PAGE in blocks.
        $PAGE = $this->page;

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

        $this->content->link = !empty($this->config->link)
            ? $this->config->link
            : '';

        $this->config->show_date = isset($this->config->show_date)
            ? $this->config->show_date
            : 1;

        $this->config->show_user = isset($this->config->show_user)
            ? $this->config->show_user
            : 1;

        $this->config->show_description = isset($this->config->show_description)
            ? $this->config->show_description
            : 1;

        $this->content->posts = !empty($this->config->posts)
            ? $this->config->posts
            : [];

        $viewallblogs = get_string('view_all_blogs', 'block_nice_blogs_slider_2');
        $displayblogs = get_string('display_blogs', 'block_nice_blogs_slider_2');

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

        $blogpageurl = new moodle_url('/blog/index.php');
        $bloglistinginstance = new blog_listing();
        $allblogentries = $bloglistinginstance->get_entries();

        $text = '
            <section class="nice-blogs-slider-2">
                <div class="nice-blogs-slider-container">
                    <div class="container">
                        <div class="nice-blogs-slider-title-container">
                            <div class="h2 mb-0 fw-bold ' . $alignmentclass . '">'
                                . format_text($this->content->main_title, FORMAT_HTML, ['filter' => true]) . '
                            </div>
                        </div>
                        <div class="nice-blogs-slider-description-container">
                            <p class="m-0">'
                                . format_text($this->content->main_description, FORMAT_HTML, ['filter' => true]) . '
                            </p>
                        </div>
                    </div>
                    <div class="container">
                        <div class="owl-carousel nice-blogs-slider-two">';

        if (!empty($this->content->posts)) {
            foreach ($allblogentries as $entryid => $entrydetails) {
                $individualblogentryurl = new moodle_url('/blog/index.php', ['entryid' => $entryid]);

                $currentblogentry = new blog_entry($entryid);
                $blogentryattachments = $currentblogentry->get_attachments();

                $shortsummary = strip_tags($entrydetails->summary);
                $wordsarray = str_word_count($shortsummary, 1);

                if (count($wordsarray) > 14) {
                    $shortsummary = implode(' ', array_slice($wordsarray, 0, 14)) . ' ...';
                } else {
                    $shortsummary = implode(' ', $wordsarray);
                }

                $dateclass = $this->config->show_date ? "date-is-active" : "date-is-hidden";
                $userclass = $this->config->show_user ? "user-is-active" : "user-is-hidden";

                $hrclass = '';
                if ($dateclass === "date-is-hidden" && $userclass === "user-is-hidden") {
                    $hrclass = 'hr-is-hidden';
                }

                $datecontent = '';
                if ($this->config->show_date) {
                    $datecontent = '
                        <div class="nice-blog-card-date-container ' . $userclass . '">
                            <span>
                                <i class="fa-solid fa-calendar-days"></i>
                            </span>
                            <span>'
                                . userdate($entrydetails->created, '%d %B %Y', 0) . '
                            </span>
                        </div>';
                }

                $usercontent = '';
                if ($this->config->show_user) {
                    $usercontent = '
                        <div class="nice-blog-card-user-container ' . $dateclass . '">
                            <span>
                                <i class="fa-regular fa-circle-user"></i>
                            </span>
                            <span>'
                                . $entrydetails->firstname . ' ' . $entrydetails->lastname . '
                            </span>
                        </div>';
                }

                $descriptioncontent = '';
                if ($this->config->show_description) {
                    $descriptioncontent = '
                        <div class="nice-blog-card-description-container">
                            <p class="mb-0">' . $shortsummary . '</p>
                        </div>';
                }

                if (in_array($entrydetails->id, $this->content->posts)) {
                    $imageurl = !empty($blogentryattachments)
                        ? $blogentryattachments[0]->url
                        : $CFG->wwwroot . '/theme/nice/pix/blocks/blogs/default_blog_image.jpg';

                    $text .= '
                        <a href="' . $individualblogentryurl . '">
                            <div class="nice-blog-card-container position-relative"
                                data-nice-blog-id="' . $entrydetails->id . '">
                                <div class="nice-blog-card nice-border-radius nice-background-white">
                                    <div class="nice-blog-card-image-container position-relative">
                                        <img class="nice-blog-card-image nice-border-radius w-100 h-100 object-fit-cover"
                                            src="' . $imageurl . '"
                                            alt="' . $entrydetails->subject . '" />
                                        <div class="position-absolute nice-blog-card-image-overlay"></div>
                                    </div>
                                    <div class="nice-blog-card-content-container">
                                        <div class="nice-blog-card-title-container">
                                            <div class="nice-blog-card-title m-0 h4 fw-bold nice-color-black">'
                                                . $entrydetails->subject . '
                                            </div>
                                        </div>
                                        ' . $descriptioncontent . '
                                        <hr class="' . $hrclass . '">
                                        <div>'
                                            . $datecontent
                                            . $usercontent . '
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>';
                }
            }
        } else {
            $text .= '
                <span class="nice-color-yellow">'
                    . $displayblogs . '
                </span>';
        }

        $text .= '
                        </div>
                        <div class="nice-blog-card-button-container text-center">
                            <a href="' . htmlspecialchars($this->content->link) . '" class="btn btn-primary">'
                                . $viewallblogs . '
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
