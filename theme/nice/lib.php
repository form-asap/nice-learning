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
 * Theme lib containing functions.
 *
 * @package    theme_nice
 * @copyright  2025 Nice Learning <support@docs.nicelearning.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


/**
 * Loads jQuery and migration files, and the theme JS file.
 *
 * @param moodle_page $page The page object to which requirements are added.
 * @return void
 */
function theme_nice_page_init(moodle_page $page) {
    $page->requires->jquery();
}

/**
 * Return the main SCSS content for the theme.
 *
 * It loads the preset SCSS file from boost theme and appends
 * the theme-specific pre and post SCSS files.
 *
 * @param theme_config $theme The theme config object.
 * @return string Compiled SCSS content.
 */
function theme_nice_get_main_scss_content($theme) {
    global $CFG;

    $scss = '';
    $filename = !empty($theme->settings->preset) ? $theme->settings->preset : null;
    $fs = get_file_storage();

    $context = context_system::instance();
    if ($filename == 'default.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    } else if ($filename == 'plain.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/plain.scss');
    } else if ($filename && ($presetfile = $fs->get_file($context->id, 'theme_boost', 'preset', 0, '/', $filename))) {
        $scss .= $presetfile->get_content();
    } else {
        // Safety fallback - maybe new installs etc.
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    }

    // Pre CSS - this is loaded AFTER any prescss from the setting but before the main scss.
    $pre = file_get_contents($CFG->dirroot . '/theme/nice/scss/pre.scss');
    // Post CSS - this is loaded AFTER the main scss but before the extra scss from the setting.
    $post = file_get_contents($CFG->dirroot . '/theme/nice/scss/post.scss');

    // Combine them together.
    return $pre . "\n" . $scss . "\n" . $post;
}

/**
 * Loads the CSS Styles and replace the background images.
 * If background image not available in the settings take the default images.
 *
 * @param string $css
 * @param string $theme
 * @return string $css
 */
function theme_nice_process_css($css, $theme) {
    global $CFG;

    $tag = '[[nice-learning:nice]]';
    $css = str_replace($tag, $CFG->wwwroot . '/theme/nice', $css);

    $tag = '[[string:nice_settings_menu]]';
    $css = str_replace($tag, get_string('nice_settings_menu', 'theme_nice'), $css);

    $tag = '[[string:nice_page_settings_menu]]';
    $css = str_replace($tag, get_string('nice_page_settings_menu', 'theme_nice'), $css);

    $setting = $theme->settings->brandcolor;
    $tag = '[[setting:brandcolor]]';
    $replacement = $setting;
    if (is_null($replacement)) {
        $replacement = '#1F5F73';
    }
    $css = str_replace($tag, $replacement, $css);

    $setting = $theme->settings->secondarycolor;
    $tag = '[[setting:secondarycolor]]';
    $replacement = $setting;
    if (is_null($replacement)) {
        $replacement = '#374C50';
    }
    $css = str_replace($tag, $replacement, $css);

    $setting = $theme->settings->footer_bg;
    $tag = '[[setting:footer_bg]]';
    $replacement = $setting;
    if (is_null($replacement)) {
        $replacement = '#E6F8FF';
    }
    $css = str_replace($tag, $replacement, $css);

    $setting = $theme->settings->box;
    $tag = '[[setting:box]]';
    $replacement = $setting;
    if (is_null($replacement)) {
        $replacement = 'rgb(0 0 0 / 10%) 0px 0px 40px';
    }
    $css = str_replace($tag, $replacement, $css);

    $setting = $theme->settings->borderradius;
    $tag = '[[setting:borderradius]]';
    $replacement = $setting;
    if (is_null($replacement)) {
        $replacement = '5';
    }
    $css = str_replace($tag, $replacement, $css);

    $setting = $theme->settings->breadcrumbbackground;
    $tag = '[[setting:breadcrumbbackground]]';
    $replacement = $setting;
    if (is_null($replacement)) {
        $replacement = 'linear-gradient(90deg,#1F5F73 30%,#84868a 80%)';
    }
    $css = str_replace($tag, $setting, $css);

    $setting = $theme->settings->breadcrumbcolor;
    $tag = '[[setting:breadcrumbcolor]]';
    $replacement = $setting;
    if (is_null($replacement)) {
        $replacement = 'white';
    }
    $css = str_replace($tag, $setting, $css);

    $setting = $theme->settings->footer_background;
    $tag = '[[setting:footer_background]]';
    $replacement = $setting;
    if (is_null($replacement)) {
        $replacement = '#211f1f';
    }
    $css = str_replace($tag, $setting, $css);

    $setting = $theme->settings->footer_color;
    $tag = '[[setting:footer_color]]';
    $replacement = $setting;
    if (is_null($replacement)) {
        $replacement = 'white';
    }
    $css = str_replace($tag, $setting, $css);

    $setting = $theme->settings->footer_copy_background;
    $tag = '[[setting:footer_copy_background]]';
    $replacement = $setting;
    if (is_null($replacement)) {
        $replacement = '#111718';
    }
    $css = str_replace($tag, $setting, $css);

    $setting = $theme->settings->footer_copy_color;
    $tag = '[[setting:footer_copy_color]]';
    $replacement = $setting;
    if (is_null($replacement)) {
        $replacement = 'white';
    }
    $css = str_replace($tag, $setting, $css);

    $setting = $theme->settings->scroll_to_top_background;
    $tag = '[[setting:scroll_to_top_background]]';
    $replacement = $setting;
    if (is_null($replacement)) {
        $replacement = '#f5f6f7';
    }
    $css = str_replace($tag, $setting, $css);

    $setting = $theme->settings->card_image_overlay;
    $tag = '[[setting:card_image_overlay]]';
    $replacement = $setting;
    if (is_null($replacement)) {
        $replacement = 'linear-gradient(0deg, rgba(0, 0, 0, 62%) 0, transparent 100%)';
    }
    $css = str_replace($tag, $setting, $css);

    return $css;
}

/**
 * Serves theme files for theme_nice.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return false|null false if file not found, does not return if found - sends file
 */
function theme_nice_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {

    // Check if the $context passed in matches CONTEXT_SYSTEM
    // (i.e., this function is intended to serve system-wide theme files)
    // and if the file area is either 'footer_logo' or 'navbar_logo'.
    if ($context->contextlevel == CONTEXT_SYSTEM && (
        $filearea === 'footer_logo' ||
        $filearea === 'navbar_logo'
    )) {

        // Load the configuration for the 'nice' theme.
        $theme = theme_config::load('nice');

        // If the 'cacheability' option is not provided in $options,
        // set it to 'public'.
        if (!array_key_exists('cacheability', $options)) {
            $options['cacheability'] = 'public';
        }

        // Serve the file using the setting_file_serve method from the theme's configuration.
        // The function returns the output generated by setting_file_serve.
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);

    } else {

        // If the function is not intended to serve a system-wide theme file,
        // or if the file area does not match 'footer_logo' or 'navbar_logo',
        // send a 'file not found' error.
        send_file_not_found();
    }
}

/**
 * Get previous, next, and jump-to navigation links for activities
 *
 * @param stdClass $cm The course module object
 * @return array|null Array with navigation data or null if no CM
 */
function theme_nice_get_prev_next_links($cm) {
    global $PAGE;

    // Basic sanity check.
    if (!$cm || empty($cm->course)) {
        return null;
    }

    // Don't show activity nav on edit pages (e.g., mod/book/edit.php) or pages without a view URL.
    $currentpage = $PAGE->url ? $PAGE->url->out_as_local_url(false) : '';
    if ($currentpage && preg_match('@/mod/[^/]+/edit\.php@', $currentpage)) {
        return null;
    }

    $modinfo = get_fast_modinfo($cm->course);
    $cms     = $modinfo->get_cms();

    // Helper: get a moodle_url for a cm_info, or null if none.
    $get_url = static function($ci) {
        // Some Moodle versions expose $ci->url, others prefer get_url().
        if (isset($ci->url) && $ci->url instanceof moodle_url) {
            return $ci->url;
        }
        if (method_exists($ci, 'get_url')) {
            $u = $ci->get_url();
            return ($u instanceof moodle_url) ? $u : null;
        }
        return null;
    };

    // Build a linear list of *visible* cms that actually have a URL (viewable activities).
    $ordered = [];
    foreach ($cms as $ci) {
        if (!$ci->uservisible) {
            continue;
        }
        $u = $get_url($ci);
        if ($u instanceof moodle_url) {
            $ordered[] = $ci;
        }
    }

    if (empty($ordered)) {
        return null;
    }

    // Find index of current cm in the filtered list.
    $idx = null;
    foreach ($ordered as $i => $ci) {
        if ((int)$ci->id === (int)$cm->id) {
            $idx = $i;
            break;
        }
    }

    // If current cm isn't in the list (e.g., it has no URL on this page), bail gracefully.
    if ($idx === null) {
        return null;
    }

    // Find previous with URL (already filtered), if any.
    $prev = ($idx > 0) ? $ordered[$idx - 1] : null;

    // Find next with URL, if any.
    $next = ($idx < count($ordered) - 1) ? $ordered[$idx + 1] : null;

    // Build jumpto list.
    $jumpto = [];
    foreach ($ordered as $i => $ci) {
        $u = $get_url($ci); // guaranteed non-null by filtering, but be extra safe.
        if (!$u) {
            continue;
        }
        $jumpto[] = [
            'id'      => $ci->id,
            'name'    => $ci->get_formatted_name(),
            'url'     => $u->out(false),
            'current' => ((int)$ci->id === (int)$cm->id),
            'index'   => $i + 1,
            'modname' => get_string('modulename', $ci->modname),
            // get_icon_url() can be null; Mustache handles null fine.
            'icon'    => $ci->get_icon_url()
        ];
    }

    // Format prev/next payloads safely.
    $prevpayload = null;
    if ($prev) {
        $pu = $get_url($prev);
        if ($pu) {
            $prevpayload = [
                'url'  => $pu->out(false),
                'name' => $prev->get_formatted_name()
            ];
        }
    }

    $nextpayload = null;
    if ($next) {
        $nu = $get_url($next);
        if ($nu) {
            $nextpayload = [
                'url'  => $nu->out(false),
                'name' => $next->get_formatted_name()
            ];
        }
    }

    return [
        'prev'    => $prevpayload,
        'next'    => $nextpayload,
        'jumpto'  => $jumpto,
        'current' => [
            'name'  => $cm->get_formatted_name(),
            'index' => $idx + 1,
            'total' => count($ordered)
        ]
    ];
}
