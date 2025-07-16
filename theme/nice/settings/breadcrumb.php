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
 * Theme breadcrumb settings to be loaded.
 *
 * @package     theme_nice
 * @category    admin
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/*
* ----------------------
* Breadcrumb setting
* ----------------------
*/

// Create the settings page for configuring breadcrumb styles and colors.
$page = new admin_settingpage('theme_nice_breadcrumb', get_string('breadcrumbsettings', 'theme_nice'));

// Breadcrumb background color or gradient setting.
$name = 'theme_nice/breadcrumbbackground';
$title = get_string('breadcrumbbackground', 'theme_nice');
$description = get_string('breadcrumbbackground_desc', 'theme_nice');
$default = 'linear-gradient(90deg,#1F5F73 30%,#84868A 80%)';
$setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_RAW);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Breadcrumb text color setting.
$name = 'theme_nice/breadcrumbcolor';
$title = get_string('breadcrumbcolor', 'theme_nice');
$description = get_string('breadcrumbcolor_desc', 'theme_nice');
$default = 'white';
$setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Must add the page after defining all the settings!
$settings->add($page);
