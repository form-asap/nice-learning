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
 * Theme advanced settings to be loaded.
 *
 * @package     theme_nice
 * @category    admin
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/*
* ----------------------
* Advanced settings tab
* ----------------------
*/

// Create the settings page for advanced theme customization options.
$page = new admin_settingpage('theme_nice_advanced', get_string('advancedsettings', 'theme_nice'));

// Raw SCSS code to prepend before main styles.
$setting = new admin_setting_configtextarea('theme_nice/scsspre',
    get_string('rawscsspre', 'theme_nice'), get_string('rawscsspre_desc', 'theme_nice'), '', PARAM_RAW);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Raw SCSS code to append after main styles.
$setting = new admin_setting_configtextarea(
    'theme_nice/scss',
    get_string('rawscss', 'theme_nice'),
    get_string('rawscss_desc', 'theme_nice'),
    '',
    PARAM_RAW
);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Must add the page after defining all the settings!
$settings->add($page);
