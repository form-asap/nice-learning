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
 * Theme scroll to top settings to be loaded.
 *
 * @package     theme_nice
 * @category    admin
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/*
* --------------------
* Scroll to top settings tab
* --------------------
*/

// Create the settings page for configuring the Scroll to Top feature appearance and behavior.
$page = new admin_settingpage('theme_nice_scrolltotop', get_string('scrolltotopsettings', 'theme_nice'));

// Show or hide the Scroll to Top button.
$name = 'theme_nice/scroll_to_top_visibility';
$title = get_string('scroll_to_top_visibility', 'theme_nice');
$description = get_string('scroll_to_top_visibility_desc', 'theme_nice');
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

// Background color for the Scroll to Top button.
$name = 'theme_nice/scroll_to_top_background';
$title = get_string('scroll_to_top_background', 'theme_nice');
$description = get_string('scroll_to_top_background_desc', 'theme_nice');
$default = '#f5f6f7';
$setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Must add the page after defining all the settings!
$settings->add($page);
