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
 * Block edit form for block_nice_course_details.
 *
 * @package     block_nice_course_details
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Extend the block_edit_form class to customize the edit form of the block.
class block_nice_course_details_edit_form extends block_edit_form {

    /**
     * Defines the custom settings for this block.
     *
     * @param MoodleQuickForm $mform The form object.
     * @return void
     */
    protected function specific_definition($mform) {
        global $CFG; // Get global configuration.

        // Add a header to the form for block settings.
        $mform->addElement(
            'header',
            'configheader',
            get_string('blocksettings', 'block')
        );

        // Add a text input field for the main title.
        $mform->addElement(
            'text',
            'config_main_title',
            get_string('config_main_title', 'block_nice_course_details')
        );
        // Set default value for the main title.
        $mform->setDefault('config_main_title', 'Course details');
        // Define the data type for main title as text.
        $mform->setType('config_main_title', PARAM_TEXT);

        // Option for showing course price.
        $showpriceoptions = [
            0 => get_string('no'),
            1 => get_string('yes'),
        ];
        $mform->addElement(
            'select',
            'config_show_price',
            get_string('config_show_price', 'block_nice_course_details'),
            $showpriceoptions
        );
        $mform->setDefault('config_show_price', 1); // Default to show.

        // Option for showing course short name.
        $showshortnameoptions = [
            0 => get_string('no'),
            1 => get_string('yes'),
        ];
        $mform->addElement(
            'select',
            'config_show_shortname',
            get_string('config_show_shortname', 'block_nice_course_details'),
            $showshortnameoptions
        );
        $mform->setDefault('config_show_shortname', 1); // Default to show.

        // Option for showing course category name.
        $showcategorynameoptions = [
            0 => get_string('no'),
            1 => get_string('yes'),
        ];
        $mform->addElement(
            'select',
            'config_show_categoryname',
            get_string('config_show_categoryname', 'block_nice_course_details'),
            $showcategorynameoptions
        );
        $mform->setDefault('config_show_categoryname', 1); // Default to show.
    }
}
