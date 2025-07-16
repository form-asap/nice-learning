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
 * Block edit form for block_nice_course_need_help
 *
 * @package     block_nice_course_need_help
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_nice_course_need_help_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        global $CFG;

        // Add a header element to the form. This is a visual element to segregate sections in the form.
        // The header title is fetched from the language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // Add a text input element to the form for configuring the block title.
        // The label for this element is fetched from the language file associated with your custom block.
        $mform->addElement('text', 'config_title', get_string('config_title', 'block_nice_course_need_help'));
        // Set a default value for the block title text input that you've just added.
        $mform->setDefault('config_title', 'Need help?');
        // Define the data type for the block title text input.
        // In this case, it's set to accept raw data, which means Moodle won't perform any additional cleaning or validation.
        $mform->setType('config_title', PARAM_RAW);

        // Add a textarea input element to the form for configuring the block title.
        // The label for this element is fetched from the language file associated with your custom block.
        $mform->addElement('textarea', 'config_description', get_string('config_description', 'block_nice_course_need_help'));
        // Set a default value for the block title textarea input that you've just added.
        $mform->setDefault('config_description', 'Are you experiencing issues accessing our course components? Email us at:');
        // Define the data type for the block title textarea input.
        // In this case, it's set to accept raw data, which means Moodle won't perform any additional cleaning or validation.
        $mform->setType('config_description', PARAM_RAW);

        // Add a text input element to the form for configuring the block title.
        // The label for this element is fetched from the language file associated with your custom block.
        $mform->addElement('text', 'config_email', get_string('config_email', 'block_nice_course_need_help'));
        // Set a default value for the block title text input that you've just added.
        $mform->setDefault('config_email', 'email@example.com');
        // Define the data type for the block title text input.
        // In this case, it's set to accept raw data, which means Moodle won't perform any additional cleaning or validation.
        $mform->setType('config_email', PARAM_RAW);

    }



}
