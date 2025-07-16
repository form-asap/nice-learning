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
 * Block edit form for block_nice_contact_us_page_1.
 *
 * @package     block_nice_contact_us_page_1
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_nice_contact_us_page_1_edit_form extends block_edit_form {

    /**
     * Defines the custom settings for our block.
     *
     * @param MoodleQuickForm $mform The form object.
     * @return void
     */
    protected function specific_definition($mform) {
        global $CFG;

        // Add a header element to the form. This is a visual element to segregate sections in the form.
        // The header title is fetched from the language file.
        $mform->addElement(
            'header',
            'config_header',
            get_string('blocksettings', 'block')
        );

        // Add a text input element to the form for configuring the block title.
        // The label for this element is fetched from the language file associated with your custom block.
        $mform->addElement(
            'text',
            'config_main_title',
            get_string('config_main_title', 'block_nice_contact_us_page_1')
        );
        // Set a default value for the block main title text input that you've just added.
        $mform->setDefault('config_main_title', 'Subscribe With US');
        // Define the data type for the block main title text input.
        $mform->setType('config_main_title', PARAM_RAW);

        // Add a textarea input element to the form for configuring the block main description.
        // The label for this element is fetched from the language file associated with your custom block.
        $mform->addElement(
            'textarea',
            'config_main_description',
            get_string('config_main_description', 'block_nice_contact_us_page_1')
        );
        // Define the data type for the block main description textarea.
        $mform->setType('config_main_description', PARAM_RAW);

        // Add a dropdown (select element) for "Title placement".
        $titleplacementoptions = [
            0 => get_string('left', 'block_nice_contact_us_page_1'),
            1 => get_string('right', 'block_nice_contact_us_page_1'),
            2 => get_string('center', 'block_nice_contact_us_page_1'),
        ];
        $mform->addElement(
            'select',
            'config_title_placement',
            get_string('config_title_placement', 'block_nice_contact_us_page_1'),
            $titleplacementoptions
        );
        // Set default value for placement as "left".
        $mform->setDefault('config_title_placement', 0);

        // Add a text input element to the form for configuring the block phone.
        $mform->addElement(
            'text',
            'config_phone',
            get_string('config_phone', 'block_nice_contact_us_page_1')
        );
        $mform->setDefault('config_phone', '+14 888 3421 0213');
        $mform->setType('config_phone', PARAM_RAW);

        // Add a text input element to the form for configuring the block email.
        $mform->addElement(
            'text',
            'config_email',
            get_string('config_email', 'block_nice_contact_us_page_1')
        );
        $mform->setDefault('config_email', 'example@example.com');
        $mform->setType('config_email', PARAM_RAW);

        // Add a text input element to the form for configuring the block working hours.
        $mform->addElement(
            'text',
            'config_working_hours',
            get_string('config_working_hours', 'block_nice_contact_us_page_1')
        );
        $mform->setDefault('config_working_hours', '(9 AM - 6 PM, Sunday - Friday)');
        $mform->setType('config_working_hours', PARAM_RAW);

        // Add a text input element to the form for configuring the block location.
        $mform->addElement(
            'text',
            'config_location',
            get_string('config_location', 'block_nice_contact_us_page_1')
        );
        $mform->setDefault(
            'config_location',
            'John Doe, 123 Sydney Street, Suite 4B, Sydney, NSW 2000, Australia'
        );
        $mform->setType('config_location', PARAM_RAW);

        // Add a text input element to the form for configuring the block form title.
        $mform->addElement(
            'text',
            'config_form_title',
            get_string('config_form_title', 'block_nice_contact_us_page_1')
        );
        $mform->setDefault(
            'config_form_title',
            'Send us your message and we will contact you the soonest.'
        );
        $mform->setType('config_form_title', PARAM_RAW);
    }
}
