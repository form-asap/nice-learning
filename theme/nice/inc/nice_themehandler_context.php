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
 * Template context builder for theme_nice layouts.
 *
 * This file constructs the $templatecontext array,
 * which provides variables to the Mustache templates used
 * in theme_nice for rendering pages and layouts.
 *
 * @package     theme_nice
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Prepare context variables to pass to Mustache templates.
$templatecontext = [
    'sitehomeurl'                   => new moodle_url('/', ['redirect' => 0]),
    'isguest'                       => $isguest,
    'navbar_logo'                   => $navbarlogo,
    'navbar_logo_width'             => $navbarlogowidth,
    'hasnavbarlogo'                 => !empty($navbarlogo),
    'home_string'                   => get_string('home'),
    'canaccessadmin'                => $canaccessadmin,
    'searchurl'                     => $searchurl,
    'sitename' => format_string(
        $SITE->shortname,
        true,
        [
            'context' => context_course::instance(SITEID),
            'escape' => false,
        ]
    ),
    'output'                        => $OUTPUT,
    'pageheading'                   => $pageheading,
    'leftblocks'                    => $leftblocks,
    'user_profile_picture' => new moodle_url(
        '/user/pix.php/' . $USER->id . '/f1.jpg'
    ),
    'profile_icon_username'         => $niceprofileiconusername,
    'login_url'                     => $loginurl,
    'isloggedin'                    => $isloggedin,
    'sidepreblocks'                 => $blockshtml,
    'hasblocks'                     => $hasblocks,
    'bodyattributes'                => $bodyattributes,
    'primarymoremenu'               => $primarymenu['moremenu'],
    'secondarymoremenu'             => $secondarynavigation ?: false,
    'mobileprimarynav'              => $primarymenu['mobileprimarynav'],
    'usermenu'                      => $primarymenu['user'],
    'langmenu'                      => $primarymenu['lang'],
    'headercontent'                 => $headercontent,
    'overflow'                      => $overflow,
    'addblockbutton'                => $addblockbutton,
    'blocks_fullwidth_top'          => $blocksfullwidthtop,
    'blocks_fullwidth_bottom'       => $blocksfullwidthbottom,
    'blocks_above_content'          => $blocksabovecontent,
    'has_blocks_above_content'      => !empty($blocksabovecontent),
    'blocks_below_content'          => $blocksbelowcontent,
    'has_blocks_below_content'      => !empty($blocksbelowcontent),
    'has_blocks_fullwidth_top'      => !empty($blocksfullwidthtop),
    'regionmainsettingsmenu'        => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu'     => !empty($regionmainsettingsmenu),

    'hasfooterlogo'                 => !empty($footerlogo),
    'footer_logo'                   => $footerlogo,
    'footer_logo_width'             => get_config('theme_nice', 'footer_logo_width') ?: '250',
    'footer_description'            => format_text(
        get_config('theme_nice', 'footer_description'),
        FORMAT_HTML,
        ['filter' => true]
    ),
    'footer_column_title_one'       => format_text(
        get_config('theme_nice', 'footer_column_title_one'),
        FORMAT_HTML,
        ['filter' => true]
    ),
    'footer_column_title_two'       => format_text(
        get_config('theme_nice', 'footer_column_title_two'),
        FORMAT_HTML,
        ['filter' => true]
    ),
    'footer_column_title_three'     => format_text(
        get_config('theme_nice', 'footer_column_title_three'),
        FORMAT_HTML,
        ['filter' => true]
    ),
    'footer_column_body_one'        => format_text(
        get_config('theme_nice', 'footer_column_body_one'),
        FORMAT_HTML,
        ['filter' => true, 'noclean' => true]
    ),
    'footer_column_body_two'        => format_text(
        get_config('theme_nice', 'footer_column_body_two'),
        FORMAT_HTML,
        ['filter' => true, 'noclean' => true]
    ),
    'footer_column_body_three'      => format_text(
        get_config('theme_nice', 'footer_column_body_three'),
        FORMAT_HTML,
        ['filter' => true, 'noclean' => true]
    ),
    'footer_copyright_text'         => format_text(
        get_config('theme_nice', 'footer_copyright_text'),
        FORMAT_HTML,
        ['filter' => true]
    ),
    'social_media_target_href'      => $socialmediatargethref,
    'social_media_title' => format_text(
        get_config('theme_nice', 'social_media_title'),
        FORMAT_HTML,
        ['filter' => true]
    ),
    'facebook_url'                  => get_config('theme_nice', 'facebook_url'),
    'x_url'                         => get_config('theme_nice', 'x_url'),
    'instagram_url'                 => get_config('theme_nice', 'instagram_url'),
    'youtube_url'                   => get_config('theme_nice', 'youtube_url'),
    'linkedin_url'                  => get_config('theme_nice', 'linkedin_url'),

    'scroll_to_top_visibility'      => $scrolltotopvisibility,
    'homepage_link_visibility'      => $homepagelinkvisibility,
    'dashboard_link_visibility'     => $dashboardlinkvisibility,
    'is_dashboard'                  => $logourl == '1' ? true : false,
    'dashboard_string'              => get_string('myhome'),
    'navbuttons'                    => $PAGE->cm ? theme_nice_get_prev_next_links($PAGE->cm) : null,


];

// Load jQuery for pages that depend on it.
$PAGE->requires->jquery();
