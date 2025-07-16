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
 * Theme footer settings to be loaded.
 *
 * @package     theme_nice
 * @category    admin
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/*
* --------------------
* Footer settings tab
* --------------------
*/

// Create the settings page for configuring footer layout, colors, and content.
$page = new admin_settingpage('theme_nice_footer', get_string('footersettings', 'theme_nice'));

// Footer logo image upload setting.
$name = 'theme_nice/footer_logo';
$title = get_string('footer_logo', 'theme_nice');
$description = get_string('footer_logo_desc', 'theme_nice');
$setting = new admin_setting_configstoredfile($name, $title, $description, 'footer_logo');
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Footer logo width setting.
$name = 'theme_nice/footer_logo_width';
$title = get_string('footer_logo_width', 'theme_nice');
$description = get_string('footer_logo_width_desc', 'theme_nice');
$default = '';
$setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_RAW);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Footer description text setting.
$name = 'theme_nice/footer_description';
$title = get_string('footer_description', 'theme_nice');
$description = get_string('footer_description_desc', 'theme_nice');
$default = 'Nice learning it is a free Moodle theme';
$setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_RAW);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Footer background color setting.
$name = 'theme_nice/footer_background';
$title = get_string('footer_background', 'theme_nice');
$description = get_string('footer_background_desc', 'theme_nice');
$default = '#211f1f';
$setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Footer text color setting.
$name = 'theme_nice/footer_color';
$title = get_string('footer_color', 'theme_nice');
$description = get_string('footer_color_desc', 'theme_nice');
$default = 'white';
$setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Add a horizontal rule as a visual separator between footer settings sections.
$name = 'theme_nice/hr_separator_0';
$title = '<hr class="nice-background-grey">';
$description = '';
$setting = new admin_setting_heading($name, $title, $description);
$page->add($setting);

// Footer column one heading label.
$name = 'theme_nice/footer_column_1';
$title = '<div>Footer Column One</div>';
$description = '';
$setting = new admin_setting_heading($name, $title, $description);
$page->add($setting);

// Title text for footer column one.
$name = 'theme_nice/footer_column_title_one';
$title = get_string('footer_column_title_one', 'theme_nice');
$description = get_string('footer_column_title_one_desc', 'theme_nice');
$default = 'Nice Learning';
$setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_RAW);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Body text for footer column one.
$name = 'theme_nice/footer_column_body_one';
$title = get_string('footer_column_body_one', 'theme_nice');
$description = get_string('footer_column_body_one_desc', 'theme_nice');
$default = 'Nice Learning';
$setting = new admin_setting_configtextarea($name, $title, $description, $default, PARAM_RAW);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Horizontal separator after footer column one.
$name = 'theme_nice/hr_separator_1';
$title = '<hr class="nice-background-grey">';
$description = '';
$setting = new admin_setting_heading($name, $title, $description);
$page->add($setting);

// Footer column two heading label.
$name = 'theme_nice/footer_column_2';
$title = '<div>Footer Column Two</div>';
$description = '';
$setting = new admin_setting_heading($name, $title, $description);
$page->add($setting);

// Title text for footer column two.
$name = 'theme_nice/footer_column_title_two';
$title = get_string('footer_column_title_two', 'theme_nice');
$description = get_string('footer_column_title_two_desc', 'theme_nice');
$default = 'Nice Learning';
$setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_RAW);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Body text for footer column two.
$name = 'theme_nice/footer_column_body_two';
$title = get_string('footer_column_body_two', 'theme_nice');
$description = get_string('footer_column_body_two_desc', 'theme_nice');
$default = 'Nice Learning';
$setting = new admin_setting_configtextarea($name, $title, $description, $default, PARAM_RAW);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Horizontal separator after footer column two.
$name = 'theme_nice/hr_separator_2';
$title = '<hr class="nice-background-grey">';
$description = '';
$setting = new admin_setting_heading($name, $title, $description);
$page->add($setting);

// Footer column three heading label.
$name = 'theme_nice/footer_column_3';
$title = '<div>Footer Column Three</div>';
$description = '';
$setting = new admin_setting_heading($name, $title, $description);
$page->add($setting);

// Title text for footer column three.
$name = 'theme_nice/footer_column_title_three';
$title = get_string('footer_column_title_three', 'theme_nice');
$description = get_string('footer_column_title_three_desc', 'theme_nice');
$default = 'Nice Learning';
$setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_RAW);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Body text for footer column three.
$name = 'theme_nice/footer_column_body_three';
$title = get_string('footer_column_body_three', 'theme_nice');
$description = get_string('footer_column_body_three_desc', 'theme_nice');
$default = 'Nice Learning';
$setting = new admin_setting_configtextarea($name, $title, $description, $default, PARAM_RAW);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Horizontal separator after footer column three.
$name = 'theme_nice/hr_separator_3';
$title = '<hr class="nice-background-grey">';
$description = '';
$setting = new admin_setting_heading($name, $title, $description);
$page->add($setting);

// Footer copyright heading label.
$name = 'theme_nice/footer_copy';
$title = '<div>Footer Copyright</div>';
$description = '';
$setting = new admin_setting_heading($name, $title, $description);
$page->add($setting);

// Footer copyright background color setting.
$name = 'theme_nice/footer_copy_background';
$title = get_string('footer_copyright_background', 'theme_nice');
$description = get_string('footer_copyright_background_desc', 'theme_nice');
$default = '#111718';
$setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Footer copyright text color setting.
$name = 'theme_nice/footer_copy_color';
$title = get_string('footer_copyright_color', 'theme_nice');
$description = get_string('footer_copyright_color_desc', 'theme_nice');
$default = 'white';
$setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Footer copyright text setting.
$name = 'theme_nice/footer_copyright_text';
$title = get_string('footer_copyright_text', 'theme_nice');
$description = get_string('footer_copyright_text_desc', 'theme_nice');
$default = 'Nice Learning';
$setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_RAW);
$setting->set_updatedcallback('theme_reset_all_caches');
$page->add($setting);

// Must add the page after defining all the settings!
$settings->add($page);
