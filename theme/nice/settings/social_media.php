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
 * Theme social media settings to be loaded.
 *
 * @package     theme_nice
 * @category    admin
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/*
* --------------------
* Social media settings tab
* --------------------
*/

// Create the settings page for configuring social media links and display options.
$page = new admin_settingpage('theme_nice_social_media', get_string('theme_nice_social_media_settings', 'theme_nice'));

// Social media link target setting (same page or new window).
$name = 'theme_nice/social_media_link_target';
$title = get_string('social_media_link_target', 'theme_nice');
$description = get_string('social_media_link_target_desc', 'theme_nice');
$setting = new admin_setting_configselect(
    $name,
    $title,
    $description,
    null,
    [
        '0' => 'Open URLs in the same page',
        '1' => 'Open URLs in a new window',
    ]
);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Title text for the social media links section.
$name = 'theme_nice/social_media_title';
$title = get_string('social_media_title', 'theme_nice');
$description = get_string('social_media_title_desc', 'theme_nice');
$default = 'Follow Us';
$setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_RAW);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// URL for the Facebook link in the social media section.
$name = 'theme_nice/facebook_url';
$title = get_string('facebook_url', 'theme_nice');
$description = get_string('facebook_url_desc', 'theme_nice');
$default = '#';
$setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_RAW);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// URL for the X (Twitter) link in the social media section.
$name = 'theme_nice/x_url';
$title = get_string('x_url', 'theme_nice');
$description = get_string('x_url_desc', 'theme_nice');
$default = '#';
$setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_RAW);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// URL for the Instagram link in the social media section.
$name = 'theme_nice/instagram_url';
$title = get_string('instagram_url', 'theme_nice');
$description = get_string('instagram_url_desc', 'theme_nice');
$default = '#';
$setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_RAW);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// URL for the YouTube link in the social media section.
$name = 'theme_nice/youtube_url';
$title = get_string('youtube_url', 'theme_nice');
$description = get_string('youtube_url_desc', 'theme_nice');
$default = '#';
$setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_RAW);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// URL for the LinkedIn link in the social media section.
$name = 'theme_nice/linkedin_url';
$title = get_string('linkedin_url', 'theme_nice');
$description = get_string('linkedin_url_desc', 'theme_nice');
$default = '#';
$setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_RAW);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Must add the page after defining all the settings!
$settings->add($page);
