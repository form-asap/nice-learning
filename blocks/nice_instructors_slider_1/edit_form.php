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
 * Block edit form for block_nice_instructors_slider_1.
 *
 * @package     block_nice_instructors_slider_1
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_nice_instructors_slider_1_edit_form extends block_edit_form {

    /**
     * Defines the form fields for editing the block configuration.
     *
     * @param MoodleQuickForm $mform The Moodle form object.
     * @return void
     */
    protected function specific_definition($mform) {
        global $CFG, $DB;

        // Add a header element to the form. This is a visual element to segregate sections in the form.
        $mform->addElement(
            'header',
            'config_header',
            get_string('blocksettings', 'block')
        );

        // Add a text input element for configuring the block main title.
        $mform->addElement(
            'text',
            'config_main_title',
            get_string('config_main_title', 'block_nice_instructors_slider_1')
        );
        $mform->setDefault('config_main_title', 'All Instructors');
        $mform->setType('config_main_title', PARAM_RAW);

        // Add a dropdown (select element) for title placement.
        $titleplacementoptions = [
            0 => get_string('left', 'block_nice_instructors_slider_1'),
            1 => get_string('right', 'block_nice_instructors_slider_1'),
            2 => get_string('center', 'block_nice_instructors_slider_1'),
        ];
        $mform->addElement(
            'select',
            'config_title_placement',
            get_string('config_title_placement', 'block_nice_instructors_slider_1'),
            $titleplacementoptions
        );
        $mform->setDefault('config_title_placement', 0);

        // Add a textarea input element for configuring the block description.
        $mform->addElement(
            'textarea',
            'config_main_description',
            get_string('config_main_description', 'block_nice_instructors_slider_1')
        );
        $mform->setType('config_main_description', PARAM_RAW);

        // Add a text input element for Link.
        $mform->addElement(
            'text',
            'config_link',
            get_string('config_link', 'block_nice_instructors_slider_1')
        );
        $mform->setType('config_link', PARAM_URL);

        // Set conditions to exclude admin (ID=1) and guest (ID=2) users from the query.
        $conditions = "id != 1 AND id != 2";

        // Fetch users from the database that meet the specified conditions.
        // The query sorts users by their last name in ascending order (lastname ASC).
        // The fields selected are the user ID and the concatenated first and last names.
        $users = $DB->get_records_select_menu(
            'user',
            $conditions,
            null,
            'lastname ASC',
            'id, CONCAT(firstname, " ", lastname)'
        );

        // Configure options for the multi-select dropdown.
        $options = [
            'multiple' => true, // Allow multiple selections.
            'noselectionstring' => get_string('select_users', 'block_nice_instructors_slider_1'),
        ];

        // Add an autocomplete element for user selection to the form.
        // 'config_users' is the name of the form element, and it will store the selected user IDs.
        // $users is the list of available users to choose from.
        // $options specify additional configurations for this element.
        $mform->addElement(
            'autocomplete',
            'config_users',
            get_string('users'),
            $users,
            $options
        );
        $mform->setType('config_users', PARAM_RAW);
    }
}
