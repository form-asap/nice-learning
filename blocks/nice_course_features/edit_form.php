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
 * Block edit form for block_nice_course_features
 *
 * @package     block_nice_course_features
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Extend the block_edit_form class to customize the edit form of the block.
class block_nice_course_features_edit_form extends block_edit_form {

    // This function defines the custom settings for our block.
    protected function specific_definition($mform) {
        global $CFG; // Get global configuration.

        // Add a header to the form for block settings.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        // Add a text input field for the main title.
        $mform->addElement('text', 'config_main_title', get_string('config_main_title', 'block_nice_course_features'));
        // Set default value for the main title.
        $mform->setDefault('config_main_title', 'Course features');
        // Define the data type for main title as text.
        $mform->setType('config_main_title', PARAM_TEXT);

        // Loop for creating settings of four items.
        for ($i = 1; $i <= 10; $i++) {
            // Add a header for each item.
            $mform->addElement('header', 'config_item' . $i , get_string('config_item', 'block_nice_course_features') . $i);

            // Add a text input field for each item icon.
            $mform->addElement('text', 'config_icon' . $i, get_string('config_icon', 'block_nice_course_features', $i));
            // Define the data type for each item icon as text.
            $mform->setType('config_icon' . $i, PARAM_RAW);

            // Add a text input field for each item title.
            $mform->addElement('text', 'config_title' . $i, get_string('config_title', 'block_nice_course_features', $i));
            // Define the data type for each item title as text.
            $mform->setType('config_title' . $i, PARAM_TEXT);

            // Add a text input field for each item value.
            $mform->addElement('text', 'config_value' . $i, get_string('config_value', 'block_nice_course_features', $i));
            // Define the data type for each item value as text.
            $mform->setType('config_value' . $i, PARAM_TEXT);

        }
    }


}
