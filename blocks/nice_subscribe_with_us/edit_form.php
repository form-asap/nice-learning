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
 * Block edit form for block_nice_subscribe_with_us.
 *
 * @package     block_nice_subscribe_with_us
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_nice_subscribe_with_us_edit_form extends block_edit_form {

    /**
     * Defines the block form fields.
     *
     * @param MoodleQuickForm $mform The Moodle form object.
     * @return void
     */
    protected function specific_definition($mform) {
        global $CFG;

        // Add a header element to the form. This is a visual element to segregate sections in the form.
        $mform->addElement(
            'header',
            'config_header',
            get_string('blocksettings', 'block')
        );

        // Add a text input element to the form for configuring the block title.
        $mform->addElement(
            'text',
            'config_main_title',
            get_string('config_main_title', 'block_nice_subscribe_with_us')
        );
        $mform->setDefault('config_main_title', 'Subscribe With US');
        $mform->setType('config_main_title', PARAM_RAW);

        // Add a textarea input element for the block main description.
        $mform->addElement(
            'textarea',
            'config_main_description',
            get_string('config_main_description', 'block_nice_subscribe_with_us')
        );
        $mform->setDefault(
            'config_main_description',
            'Subscribe with us today to unlock a world of endless learning opportunities'
        );
        $mform->setType('config_main_description', PARAM_RAW);

        // Add a dropdown (select element) for title and description placement.
        $titleanddescriptionplacementoptions = [
            0 => get_string('left', 'block_nice_subscribe_with_us'),
            1 => get_string('right', 'block_nice_subscribe_with_us'),
            2 => get_string('center', 'block_nice_subscribe_with_us'),
        ];
        $mform->addElement(
            'select',
            'config_title_and_description_placement',
            get_string(
                'config_title_and_description_placement',
                'block_nice_subscribe_with_us'
            ),
            $titleanddescriptionplacementoptions
        );
        $mform->setDefault('config_title_and_description_placement', 0);
    }
}
