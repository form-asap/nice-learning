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
 * Block edit form for block_nice_categories_slider_2.
 *
 * @package     block_nice_categories_slider_2
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_nice_categories_slider_2_edit_form extends block_edit_form {

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

        // Add a text input element to the form for configuring the block main title.
        // The label for this element is fetched from the language file associated with your custom block.
        $mform->addElement(
            'text',
            'config_main_title',
            get_string('config_main_title', 'block_nice_categories_slider_2')
        );
        // Set a default value for the block main title text input that you've just added.
        $mform->setDefault('config_main_title', 'All Categories');
        // Define the data type for the block main title text input.
        $mform->setType('config_main_title', PARAM_RAW);

        // Add a dropdown (select element) for "Title placement".
        $titleplacementoptions = [
            0 => get_string('left', 'block_nice_categories_slider_2'),
            1 => get_string('right', 'block_nice_categories_slider_2'),
            2 => get_string('center', 'block_nice_categories_slider_2'),
        ];
        $mform->addElement(
            'select',
            'config_title_placement',
            get_string('config_title_placement', 'block_nice_categories_slider_2'),
            $titleplacementoptions
        );
        // Set default value for title placement as "left".
        $mform->setDefault('config_title_placement', 0);

        // Add a text input element to the form for configuring the block main description.
        // The label for this element is fetched from the language file associated with your custom block.
        $mform->addElement(
            'textarea',
            'config_main_description',
            get_string('config_main_description', 'block_nice_categories_slider_2')
        );
        // Define the data type for the block main description text input.
        $mform->setType('config_main_description', PARAM_RAW);

        // Option for showing students enrolled in the course.
        $showcountoptions = [
            0 => get_string('no'),
            1 => get_string('yes'),
        ];
        $mform->addElement(
            'select',
            'config_show_count',
            get_string('config_show_count', 'block_nice_categories_slider_2'),
            $showcountoptions
        );
        $mform->setDefault('config_show_count', 1); // Default to show.

        // Option for showing description.
        $showdescriptionoptions = [
            0 => get_string('no'),
            1 => get_string('yes'),
        ];
        $mform->addElement(
            'select',
            'config_show_description',
            get_string('config_show_description', 'block_nice_categories_slider_2'),
            $showdescriptionoptions
        );
        $mform->setDefault('config_show_description', 1); // Default to show.

        // Add a text input element for "Link".
        $mform->addElement(
            'text',
            'config_link',
            get_string('config_link', 'block_nice_categories_slider_2')
        );
        $mform->setDefault('config_link', $CFG->wwwroot . '/course');
        $mform->setType('config_link', PARAM_URL);

        // Fetch all categories for the category selection field.
        $categories = core_course_category::make_categories_list();

        $options = [
            'multiple' => true,
            'noselectionstring' => get_string('select_categories', 'block_nice_categories_slider_2'),
        ];

        // Add the categories as an autocomplete form element.
        $mform->addElement(
            'autocomplete',
            'config_categories',
            get_string('categories'),
            $categories,
            $options
        );
        $mform->setType('config_categories', PARAM_INT);
    }
}
