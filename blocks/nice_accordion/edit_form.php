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
 * Block edit form for block_nice_accordion.
 *
 * @package     block_nice_accordion
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Extend the block_edit_form class to customize the edit form of the block.
class block_nice_accordion_edit_form extends block_edit_form {

    /**
     * Defines the custom settings for our block.
     *
     * @param MoodleQuickForm $mform The form object.
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
            get_string('config_main_title', 'block_nice_accordion')
        );
        // Set default value for the main title.
        $mform->setDefault('config_main_title', 'Frequently Asked Questions');
        // Define the data type for main title as text.
        $mform->setType('config_main_title', PARAM_TEXT);

        // Add a dropdown (select element) for "Title placement".
        $titleplacementoptions = [
            0 => get_string('left', 'block_nice_accordion'),
            1 => get_string('right', 'block_nice_accordion'),
            2 => get_string('center', 'block_nice_accordion'),
        ];
        $mform->addElement(
            'select',
            'config_title_placement',
            get_string('config_title_placement', 'block_nice_accordion'),
            $titleplacementoptions
        );
        // Set default value for title placement as "left".
        $mform->setDefault('config_title_placement', 0);

        // Add a text input field for the main description.
        $mform->addElement(
            'textarea',
            'config_main_description',
            get_string('config_main_description', 'block_nice_accordion')
        );
        // Define the data type for main description as text.
        $mform->setType('config_main_description', PARAM_TEXT);

        // Option for showing the first item.
        $showfirstoptions = [
            0 => get_string('no'),
            1 => get_string('yes'),
        ];
        $mform->addElement(
            'select',
            'config_show_first',
            get_string('config_show_first', 'block_nice_accordion'),
            $showfirstoptions
        );
        $mform->setDefault('config_show_first', 1); // Default to show.

        // Option for enabling link collapse.
        $showcollapseoptions = [
            0 => get_string('no'),
            1 => get_string('yes'),
        ];
        $mform->addElement(
            'select',
            'config_link_collapse',
            get_string('config_link_collapse', 'block_nice_accordion'),
            $showcollapseoptions
        );
        $mform->setDefault('config_link_collapse', 1); // Default to show.

        // Loop for creating settings of twenty items.
        for ($i = 1; $i <= 20; $i++) {
            // Add a header for each item.
            $mform->addElement(
                'header',
                'config_item' . $i,
                get_string('config_item', 'block_nice_accordion') . $i
            );

            // Add a text input field for each item title.
            $mform->addElement(
                'text',
                'config_title' . $i,
                get_string('config_title', 'block_nice_accordion', $i)
            );
            // Define the data type for each item title as text.
            $mform->setType('config_title' . $i, PARAM_TEXT);

            // Add a textarea input field for each item description.
            $mform->addElement(
                'textarea',
                'config_description' . $i,
                get_string('config_description', 'block_nice_accordion', $i)
            );
            // Define the data type for each item description as text.
            $mform->setType('config_description' . $i, PARAM_TEXT);
        }
    }
}
