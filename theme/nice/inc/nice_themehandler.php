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
 * Theme handler script for theme_nice.
 *
 * Prepares global variables and template contexts for theme rendering.
 *
 * @package     theme_nice
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $USER, $CFG, $SESSION, $OUTPUT, $COURSE, $DB;

require_once($CFG->dirroot . '/theme/nice/inc/nice_page_handler.php');

// Fetch navbar logo width from theme settings.
$navbarlogowidth = get_config('theme_nice', 'navbar_logo_width');
if (empty($navbarlogowidth)) {
    $navbarlogowidth = '';
}

// Check if the user can access admin settings.
$canaccessadmin = has_capability('moodle/site:config', context_system::instance(), $USER);
$templatecontext['canaccessadmin'] = $canaccessadmin;

// Site-wide search URL.
$searchurl = new moodle_url('/search/index.php');

// Determine global context and user status.
$context = context_system::instance();
$isguest = isguestuser(); // Checks if the current user is a guest.

// Add block button (for editors/admins).
$addblockbutton = $OUTPUT->addblockbutton();

// Determine page heading based on context (e.g. blog, activity).
$nicepagehandler = new nicepagehandler();
$pageheading = $nicepagehandler->nicegetpagetitle();

// Determine user role class for body tag styling.
if (is_siteadmin()) {
    $userstatus = 'role-supreme';
} else {
    $userstatus = 'role-standard';
}

// Prepare secondary navigation data if present.
$secondarynavigation = false;
$overflow = '';
if ($PAGE->has_secondary_navigation()) {
    $tablistnav = $PAGE->has_tablist_secondary_navigation();
    $moremenu = new \core\navigation\output\more_menu(
        $PAGE->secondarynav,
        'nav-tabs',
        true,
        $tablistnav
    );
    $secondarynavigation = $moremenu->export_for_template($OUTPUT);
    $overflowdata = $PAGE->secondarynav->get_overflow_menu_data();
    if (!is_null($overflowdata)) {
        $overflow = $overflowdata->export_for_template($OUTPUT);
    }
}

// Prepare primary navigation data.
$primary = new core\navigation\output\primary($PAGE);
$renderer = $PAGE->get_renderer('core');
$primarymenu = $primary->export_for_template($renderer);

// Check if main settings menu should appear in the header.
$buildregionmainsettings = !$PAGE->include_region_main_settings_in_header_actions()
    && !$PAGE->has_secondary_navigation();
$regionmainsettingsmenu = $buildregionmainsettings
    ? $OUTPUT->region_main_settings_menu()
    : false;

// Activity header content for the page (if any).
$header = $PAGE->activityheader;
$headercontent = $header->export_for_template($renderer);

// Authentication details.
$loginurl = get_login_url();
$isloggedin = isloggedin();

// Prepare body classes.
$niceuserbodyclass = 'nice_body_class';
$extraclasses = [
    'nice_no_class',
    $userstatus,
    $niceuserbodyclass,
];

// Retrieve blocks in side-pre (right sidebar) and left regions.
$blockshtml = $OUTPUT->blocks('side-pre');
$leftblocks = $OUTPUT->blocks('left');

// These variables will be deprecated in future versions.
$hasblocks = strpos($blockshtml, 'data-block=') !== false;
$hasleftblocks = strpos($leftblocks, 'data-block=') !== false;

// Use new sidebar booleans instead.
$sidebarleft = strpos($leftblocks, 'data-block=') !== false;
$sidebarright = strpos($blockshtml, 'data-block=') !== false;

// Main settings menu again in case context changes.
$regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();

// Prepare user name for profile icon.
if ($isloggedin) {
    $niceprofileiconusername = $USER->firstname . ' ' . $USER->lastname;
} else {
    $niceprofileiconusername = '';
}

// Prepare full-width block regions.
$blocksfullwidthtop = $OUTPUT->blocks('fullwidth-top');
$blocksfullwidthbottom = $OUTPUT->blocks('fullwidth-bottom');
$blocksabovecontent = $OUTPUT->blocks('above-content');
$blocksbelowcontent = $OUTPUT->blocks('below-content');

// Check for specific pages where blocks should be hidden.
$userprofilefromcourseparticipants = strpos(
    $_SERVER['REQUEST_URI'],
    "user/view.php"
) !== false && isset($_GET["course"]);

$coursesectionpage = strpos(
    $_SERVER['REQUEST_URI'],
    "course/view.php"
) !== false && isset($_GET["section"]);

if (
    strpos($_SERVER['REQUEST_URI'], "user/index.php") !== false
    || strpos($_SERVER['REQUEST_URI'], "course/edit.php") !== false
    || strpos($_SERVER['REQUEST_URI'], "course/completion.php") !== false
    || strpos($_SERVER['REQUEST_URI'], "course/admin.php") !== false
    || $coursesectionpage
    || $userprofilefromcourseparticipants
) {
    $sidebarleft = false;
    $sidebarright = false;
    $blocksabovecontent = false;
    $blocksbelowcontent = false;
    $blocksfullwidthtop = false;
    $blocksfullwidthbottom = false;
}

// Load theme assets such as logos and social media config.
$themeconfig = theme_config::load('nice');

// Navbar logo URL.
$navbarlogo = '';
if (!empty($themeconfig->settings->navbar_logo)) {
    $navbarlogo = $themeconfig->setting_file_url('navbar_logo', 'navbar_logo');
}

// Footer logo URL.
$footerlogo = '';
if (!empty($themeconfig->settings->footer_logo)) {
    $footerlogo = $themeconfig->setting_file_url('footer_logo', 'footer_logo');
}

// Determine how social media links open (same tab or new tab).
$socialmediatarget = get_config('theme_nice', 'social_media_link_target');
if ($socialmediatarget == 1) {
    $socialmediatargethref = '_blank';
} else {
    $socialmediatargethref = '_self';
}

// Load theme-specific feature toggles.
$scrolltotopvisibility = get_config('theme_nice', 'scroll_to_top_visibility');
$homepagelinkvisibility = get_config('theme_nice', 'homepagelink');
$dashboardlinkvisibility = get_config('theme_nice', 'dashboardlink');
$logourl = get_config('theme_nice', 'logourl');
