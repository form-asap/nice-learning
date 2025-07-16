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
  * Theme general settings to be loaded.
  *
  * @package     theme_nice
  * @category    admin
  * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
  * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
  */

defined('MOODLE_INTERNAL') || die();

// Boost provides a nice setting page which splits settings onto separate tabs. We want to use it here.
$settings = new theme_boost_admin_settingspage_tabs('themesettingnice', get_string('configtitle', 'theme_nice'));

/*
* ----------------------
* General setting
* ----------------------
*/
$page = new admin_settingpage('theme_nice_general', get_string('generalsettings', 'theme_nice'));

// Define the primary brand color used across theme elements.
$name = 'theme_nice/brandcolor';
$title = get_string('brandcolor', 'theme_nice');
$description = get_string('brandcolor_desc', 'theme_nice');
$default = '#1F5F73';
$setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Define the secondary color used for accents and complementary elements in the theme.
$name = 'theme_nice/secondarycolor';
$title = get_string('secondarycolor', 'theme_nice');
$description = get_string('secondarycolor_desc', 'theme_nice');
$default = '#374C50';
$setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Define the CSS box-shadow style applied to theme elements for visual depth.
$name = 'theme_nice/box';
$title = get_string('boxshadow', 'theme_nice');
$description = get_string('boxshadow_desc', 'theme_nice');
$default = '0 0 40px rgba(0, 0, 0, 0.1);';
$setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_RAW);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Border radius for rounded corners in the theme.
$name = 'theme_nice/borderradius';
$title = get_string('borderradius', 'theme_nice');
$description = get_string('borderradius_desc', 'theme_nice');
$default = '5';
$setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_RAW);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Upload and store the logo image displayed in the navigation bar.
$name = 'theme_nice/navbar_logo';
$title = get_string('navbar_logo', 'theme_nice');
$description = get_string('navbar_logo_desc', 'theme_nice');
$setting = new admin_setting_configstoredfile($name, $title, $description, 'navbar_logo');
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Define the width of the navbar logo in pixels.
$name = 'theme_nice/navbar_logo_width';
$title = get_string('navbar_logo_width', 'theme_nice');
$description = get_string('navbar_logo_width_desc', 'theme_nice');
$default = '';
$setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_RAW);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Define the CSS overlay gradient applied on card images for visual effects or readability.
$name = 'theme_nice/card_image_overlay';
$title = get_string('card_image_overlay', 'theme_nice');
$description = get_string('card_image_overlay_desc', 'theme_nice');
$default = 'linear-gradient(0deg,rgba(0,0,0,62%) 0,transparent 100%)';
$setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_RAW);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Define where the user is redirected when clicking the site logo (Dashboard or Home page).
$name = 'theme_nice/logourl';
$title = get_string('logo_url', 'theme_nice');
$description = get_string('logo_url_desc', 'theme_nice');
$setting = new admin_setting_configselect(
    $name,
    $title,
    $description,
    null,
    [
        '1' => 'Dashboard',
        '0' => 'Home',
    ]
);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Show or hide the Home page link in navigation.
$name = 'theme_nice/homepagelink';
$title = get_string('home_page_link', 'theme_nice');
$description = get_string('home_page_link_desc', 'theme_nice');
$setting = new admin_setting_configselect(
    $name,
    $title,
    $description,
    null,
    [
        '1' => 'Show',
        '0' => 'Hide',
    ]
);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Show or hide the Dashboard link in navigation.
$name = 'theme_nice/dashboardlink';
$title = get_string('dashboard_link', 'theme_nice');
$description = get_string('dashboard_link_desc', 'theme_nice');
$setting = new admin_setting_configselect(
    $name,
    $title,
    $description,
    null,
    [
        '1' => 'Show',
        '0' => 'Hide',
    ]
);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Must add the page after defining all the settings!
$settings->add($page);
