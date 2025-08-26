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

/**
 * Plugin version and other meta-data are defined here.
 *
 * @package    theme_nice
 * @copyright  2025 Nice Learning <support@docs.nicelearning.org>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_nice\output;

use context_course;
use custom_menu;
use html_writer;
use moodle_url;
use stdClass;

/**
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * @package    theme_nice
 * @copyright  2023 Nice Learning
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_renderer extends \core_renderer {


    /**
     * Renders the lang menu
     *
     * @return mixed
     */
    public function render_lang_menu() {
        $langs = get_string_manager()->get_list_of_translations();
        $haslangmenu = $this->lang_menu() != '';
        $menu = new custom_menu();

        if ($haslangmenu) {
            $strlang = get_string('language');
            $currentlang = current_language();
            if (isset($langs[$currentlang])) {
                $currentlang = $langs[$currentlang];
            } else {
                $currentlang = $strlang;
            }
            $this->language = $menu->add($currentlang, new moodle_url('#'), $strlang, 10000);
            foreach ($langs as $langtype => $langname) {
                $this->language->add(
                    $langname,
                    new moodle_url($this->page->url, ['lang' => $langtype]),
                    $langname
                );
            }
            foreach ($menu->get_children() as $item) {
                $context = $item->export_for_template($this);
            }
            if (isset($context)) {
                return $this->render_from_template('theme_nice/lang_menu', $context);
            }
        }
    }

    /**
     * if blog page
     *
     * @return string
     */
    public function if_blog() {
        $actuallink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
            ? "https"
            : "http";
        $actuallink .= "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        if ( strpos($actuallink, '/blog') != false ):
            return true;
        endif;
    }

    /**
     * if course page
     *
     * @return string
     */
    public function if_course() {
        $actuallink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
            ? "https"
            : "http";
        $actuallink .= "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        if ( strpos($actuallink, 'index.php?categoryid') != false ):
            return true;
        endif;
    }

    /**
     * if home pages
     *
     * @return string
     */
    public function if_home_pages() {
        global $CFG;
        $actuallink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
            ? "https"
            : "http";
        $actuallink .= "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $hidebanner      = get_config('theme_nice', 'hide_banner');

        if ($hidebanner) {
            foreach (preg_split("/((\r?\n)|(\r\n?))/", $hidebanner) as $line) {
                $oldurl = 'http://localhost/moodle/nice-4.0/';
                $newurl = $CFG->wwwroot . '/';
                if (strpos($line, $oldurl) !== false) {
                    $line = str_replace($oldurl, $newurl, $line);
                }
                if ($actuallink == $line) {
                    return true;
                }
            }
        }
    }

    /**
     * if bottom content hide
     *
     * @return string
     */
    public function if_hide_page_bottom_content() {
        global $CFG;
        $actuallink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
            ? "https"
            : "http";
        $actuallink .= "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $hidepagebottomcontent     = get_config('theme_nice', 'hide_page_bottom_content');

        if ($hidepagebottomcontent) {
            foreach (preg_split("/((\r?\n)|(\r\n?))/", $hidepagebottomcontent) as $line) {
                $oldurl = 'http://localhost/moodle/nice-4.0/';
                $newurl = $CFG->wwwroot . '/';
                if (strpos($line, $oldurl) !== false) {
                    $line = str_replace($oldurl, $newurl, $line);
                }
                if ($actuallink == $line) {
                    return true;
                }
            }
        }
    }

    /**
     * Renders the context header for the page.
     *
     * @param array $headerinfo Heading information.
     * @param int $headinglevel What 'h' level to make the heading.
     * @return string A rendered context header.
     */
    public function context_header($headerinfo = null, $headinglevel = 1): string {
        global $DB, $USER, $CFG, $SITE;
        require_once($CFG->dirroot . '/user/lib.php');
        $context = $this->page->context;
        $heading = null;
        $imagedata = null;
        $subheader = null;
        $userbuttons = null;

        // Make sure to use the heading if it has been set.
        if (isset($headerinfo['heading'])) {
            $heading = $headerinfo['heading'];
        } else {
            $heading = $this->page->heading;
        }

        // The user context currently has images and buttons. Other contexts may follow.
        if ((isset($headerinfo['user']) || $context->contextlevel == CONTEXT_USER) && $this->page->pagetype !== 'my-index') {
            if (isset($headerinfo['user'])) {
                $user = $headerinfo['user'];
            } else {
                // Look up the user information if it is not supplied.
                $user = $DB->get_record('user', ['id' => $context->instanceid]);
            }

            // If the user context is set, then use that for capability checks.
            if (isset($headerinfo['usercontext'])) {
                $context = $headerinfo['usercontext'];
            }

            // Only provide user information if the user is the current user, or a user which the current user can view.
            // When checking user_can_view_profile(), either:
            // If the page context is course, check the course context (from the page object) or;
            // If page context is NOT course, then check across all courses.
            $course = ($this->page->context->contextlevel == CONTEXT_COURSE) ? $this->page->course : null;

            if (user_can_view_profile($user, $course)) {
                // Use the user's full name if the heading isn't set.
                if (empty($heading)) {
                    $heading = fullname($user);
                }

                $imagedata = $this->user_picture($user, ['size' => 100]);

                // Check to see if we should be displaying a message button.
                if (!empty($CFG->messaging) && has_capability('moodle/site:sendmessage', $context)) {
                    $userbuttons = [
                        'messages' => [
                            'buttontype' => 'message',
                            'title' => get_string('message', 'message'),
                            'url' => new moodle_url('/message/index.php', ['id' => $user->id]),
                            'image' => 'message',
                            'linkattributes' => \core_message\helper::messageuser_link_params($user->id),
                            'page' => $this->page,
                        ],
                    ];

                    if ($USER->id != $user->id) {
                        $iscontact = \core_message\api::is_contact($USER->id, $user->id);
                        $contacttitle = $iscontact ? 'removefromyourcontacts' : 'addtoyourcontacts';
                        $contacturlaction = $iscontact ? 'removecontact' : 'addcontact';
                        $contactimage = $iscontact ? 'removecontact' : 'addcontact';
                        $userbuttons['togglecontact'] = [
                            'buttontype' => 'togglecontact',
                            'title' => get_string($contacttitle, 'message'),
                            'url' => new moodle_url('/message/index.php', [
                                'user1' => $USER->id,
                                'user2' => $user->id,
                                $contacturlaction => $user->id,
                                'sesskey' => sesskey(),
                            ]),
                            'image' => $contactimage,
                            'linkattributes' => \core_message\helper::togglecontact_link_params($user, $iscontact),
                            'page' => $this->page,
                        ];
                    }

                    $this->page->requires->string_for_js('changesmadereallygoaway', 'moodle');
                }
            } else {
                $heading = null;
            }
        }

        $prefix = null;
        if ($context->contextlevel == CONTEXT_MODULE) {
            if ($this->page->course->format === 'singleactivity') {
                $heading = $this->page->course->fullname;
            } else {
                $heading = $this->page->cm->get_formatted_name();
                $imagedata = $this->pix_icon('monologo', '', $this->page->activityname, ['class' => 'activityicon']);
                $purposeclass = plugin_supports('mod', $this->page->activityname, FEATURE_MOD_PURPOSE);
                $purposeclass .= ' activityiconcontainer';
                $purposeclass .= ' modicon_' . $this->page->activityname;
                $imagedata = html_writer::tag('div', $imagedata, ['class' => $purposeclass]);
                $prefix = get_string('modulename', $this->page->activityname);
            }
        }

        $contextheader = new \context_header($heading, $headinglevel, $imagedata, $userbuttons, $prefix);
        return $this->render_context_header($contextheader);
    }

     /**
      * Renders the header bar.
      *
      * @param context_header $contextheader Header bar object.
      * @return string HTML for the header bar.
      */
    protected function render_context_header(\context_header $contextheader) {

        // Generate the heading first and before everything else as we might have to do an early return.
        if (!isset($contextheader->heading)) {
            $heading = $this->heading($this->page->heading, $contextheader->headinglevel, 'h2');
        } else {
            $heading = $this->heading($contextheader->heading, $contextheader->headinglevel, 'h2');
        }

        // All the html stuff goes here.
        $html = html_writer::start_div('page-context-header');

        // Image data.
        if (isset($contextheader->imagedata)) {
            // Header specific image.
            $html .= html_writer::div($contextheader->imagedata, 'page-header-image me-2');
        }

        // Headings.
        if (isset($contextheader->prefix)) {
            $prefix = html_writer::div($contextheader->prefix, 'text-muted text-uppercase small line-height-3');
            $heading = $prefix . $heading;
        }

        $html .= html_writer::tag('div', $heading, [
            'class' => 'page-header-headings',
        ]);

        // Buttons.
        if (isset($contextheader->additionalbuttons)) {
            $html .= html_writer::start_div('btn-group header-button-group nice-background-light-grey p-3 nice-border-radius');
            foreach ($contextheader->additionalbuttons as $button) {
                if (!isset($button->page)) {
                    // Include JS for messaging functionality.
                    if ($button['buttontype'] === 'togglecontact') {
                        \core_message\helper::togglecontact_requirejs();
                    }
                    if ($button['buttontype'] === 'message') {
                        \core_message\helper::messageuser_requirejs();
                    }

                    if ($button['buttontype'] === 'message') {
                        $iconkey = 'icons/message';
                    } else if ($button['buttontype'] === 'togglecontact') {
                        $iconkey = ($button['image'] === 'removecontact') ? 'icons/delete' : 'icons/add';
                    } else {
                        $iconkey = 'icons/add';
                    }

                    $image = $this->pix_icon($iconkey, $button['title'], 'theme_nice', [
                        'class' => 'iconsmall',
                        'role' => 'presentation',
                    ]);
                    $image .= html_writer::span($button['title'], 'header-button-title');
                } else {
                    // Fallback for image URLs from external sources.
                    $image = html_writer::empty_tag('img', [
                        'src' => $button['formattedimage'],
                        'role' => 'presentation',
                    ]);
                }

                $html .= html_writer::link($button['url'], html_writer::tag('span', $image), $button['linkattributes']);
            }
            $html .= html_writer::end_div();
        }
        $html .= html_writer::end_div();

        return $html;
    }


    /**
     * if course details
     *
     * @return string
     */
    public function if_single_course() {
        $actuallink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
            ? "https"
            : "http";
        $actuallink .= "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        if ( strpos($actuallink, '/course/view.php?id=') != false ):
            return true;
        endif;
    }

    /**
     * if user enrolled
     *
     * @return string
     */
    public function nice_if_user_enrolled() {

        global $USER;
        global $course;

        $courseid = $course->id;
        $userid = $USER->id;

        $context = context_course::instance($courseid);
        if(is_enrolled($context, $userid, '', true) == true):
            return false;
        else:
            return true;
        endif;

    }

    /**
     * enrol link
     *
     * @return string
     */
    public function nice_enroll_link() {
        $actuallink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
            ? "https"
            : "http";
        $actuallink .= "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $id = optional_param('id', 0, PARAM_INT);
        global $CFG;
        return $CFG->wwwroot . "/enrol/index.php?id=$id";
    }

    /**
     * course overview
     *
     * @return string
     */
    public function course_overview() {
        global $DB;
        global $course;
        $result = $DB->get_field("course", "summary", ["id" => $course->id]);
        $html = '';
        $html .= $result;

        return $html;
    }

    /**
     * Outputs the pix url base
     *
     * @return string an URL.
     */
    public function get_pix_image_url_base() {
        global $CFG;

        return $CFG->wwwroot . "/theme/nice/pix";
    }

    /**
     * Secure login info.
     *
     * @return string
     */
    public function secure_login_info() {
        return $this->login_info(false);
    }


    /**
     * Construct a user menu, returning HTML that can be echoed out by a
     * layout file.
     *
     * @param stdClass $user A user object, usually $USER.
     * @param bool $withlinks true if a dropdown should be built.
     * @return string HTML fragment.
     */
    /**
     * Construct a user menu, returning HTML that can be echoed out by a
     * layout file.
     *
     * @param stdClass $user A user object, usually $USER.
     * @param bool $withlinks true if a dropdown should be built.
     * @return string HTML fragment.
     */
    public function user_menu($user = null, $withlinks = null) {
        global $USER, $CFG;
        require_once($CFG->dirroot . '/user/lib.php');

        if (is_null($user)) {
            $user = $USER;
        }

        if (is_null($withlinks)) {
            $withlinks = empty($this->page->layout_options['nologinlinks']);
        }

        if (during_initial_install()) {
            return "";
        }

        $opts = user_get_user_navigation_info($user, $this->page);

        // Fetch the translated titles for the allowed items.
        $alloweditems = [
            get_string('profile', 'moodle'),
            get_string('grades', 'grades'),
            get_string('privatefiles', 'moodle'),
            get_string('preferences', 'moodle'),
        ];

        $nicenavitems = '';
        if ($withlinks) {
            foreach ($opts->navitems as $key => $value) {
                if ($value->itemtype === 'link' && in_array($value->title, $alloweditems)) {
                    $link = html_writer::link(
                        $value->url,
                        s($value->title),
                        ['class' => 'nice-dashboard-sidebar-item mb-3']
                    );
                    $nicenavitems .= html_writer::div(
                        $link,
                        'col-sm-6 col-md-6 col-lg-6 col-xl-3'
                    );
                }
            }
        }

        return $nicenavitems;
    }
    /**
     * region_main_settings_menu
     */
    public function nice_region_main_settings_menu() {
        $header = $this->context_header_settings_menu();

        if (!$this->page->blocks->is_block_present('settings')) {
            return $header;
        }
    }

    /**
     * Outputs a heading element.
     *
     * - $classes can be a string of CSS classes or an array of HTML attributes.
     * - Falls back to parent if $text is not a string.
     * - $id is applied even if also set in attributes.
     *
     * @param string|mixed $text   Heading text, or other content handled by parent.
     * @param int          $level  Heading level (1–6), default 2.
     * @param string|array $classes CSS class string or attributes array.
     * @param string|null  $id     Optional ID.
     * @return string              Rendered <h*> element HTML.
     */
    public function heading($text, $level = 2, $classes = 'main page-section-title-hide', $id = null) {
        // Build attribute array safely (supports either string class or attribute array).
        $attributes = [];

        if (is_array($classes)) {
            $attributes = $classes;
        } else if (!is_null($classes) && $classes !== '') {
            $attributes['class'] = $classes;
        }

        if (!is_null($id)) {
            // Respect explicit $id, override if also present in $attributes.
            $attributes['id'] = $id;
        }

        // Only format when the text is a string; if not, defer to parent (handles objects/templates).
        if (is_string($text)) {
            $content = format_string($text);
            return html_writer::tag('h' . (int)$level, $content, $attributes);
        } else {
            // Fallback to core behavior for non-string inputs to avoid type errors.
            return parent::heading($text, $level, $attributes);
        }
    }

    /**
     * Returns standard main content placeholder.
     * Designed to be called in theme layout.php files.
     *
     * @return string HTML fragment.
     */
    public function main_content() {
        // This is here because it is the only place we can inject the "main" role over the entire main content area
        // without requiring all theme's to manually do it, and without creating yet another thing people need to
        // remember in the theme.
        // This is an unfortunate hack. DO NO EVER add anything more here.
        // DO NOT add classes.
        // DO NOT add an id.
        return $this->unique_main_content_token;
    }

    /**
     * Indicates if fake blocks are viewed for the first time.
     *
     * @return bool
     */
    public function firstview_fakeblocks(): bool {
        global $SESSION;

        $firstview = false;
        if ($this->page->cm) {
            if (!$this->page->blocks->region_has_fakeblocks('side-pre')) {
                return false;
            }
            if (!property_exists($SESSION, 'firstview_fakeblocks')) {
                $SESSION->firstview_fakeblocks = [];
            }
            if (array_key_exists($this->page->cm->id, $SESSION->firstview_fakeblocks)) {
                $firstview = false;
            } else {
                $SESSION->firstview_fakeblocks[$this->page->cm->id] = true;
                $firstview = true;
                if (count($SESSION->firstview_fakeblocks) > 100) {
                    array_shift($SESSION->firstview_fakeblocks);
                }
            }
        }
        return $firstview;
    }

    /**
     * Renders a Mustache template with additional context for theme_nice.
     *
     * This method overrides the default render_from_template() behavior
     * to inject activity navigation buttons (previous, next, and jump-to)
     * into the context when rendering the main course layout template.
     *
     * @param string $templatename The name of the Mustache template to render.
     *                             Example: 'theme_nice/columns2'.
     * @param array $context The data to be passed into the Mustache template.
     * @return string The rendered HTML content.
     */
    public function render_from_template($templatename, $context) {
        global $PAGE;

        // Add navigation buttons for activity pages
        if (!empty($PAGE->cm) && $templatename === 'theme_nice/columns2') {
            $navlinks = theme_nice_get_prev_next_links($PAGE->cm);
            if ($navlinks) {
                $context['navbuttons'] = $navlinks;
            }
        }

        return parent::render_from_template($templatename, $context);
    }
}
