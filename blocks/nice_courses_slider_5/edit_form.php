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
 * Block edit form for block_nice_courses_slider_5.
 *
 * @package     block_nice_courses_slider_5
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_nice_courses_slider_5_edit_form extends block_edit_form {

    /**
     * Defines the custom settings for this block.
     *
     * @param MoodleQuickForm $mform The form object.
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

        // Add a text input element for the block main title.
        $mform->addElement(
            'text',
            'config_main_title',
            get_string('config_main_title', 'block_nice_courses_slider_5')
        );
        $mform->setDefault('config_main_title', 'All Courses');
        $mform->setType('config_main_title', PARAM_RAW);

        // Add a dropdown for title placement.
        $titleplacementoptions = [
            0 => get_string('left', 'block_nice_courses_slider_5'),
            1 => get_string('right', 'block_nice_courses_slider_5'),
            2 => get_string('center', 'block_nice_courses_slider_5'),
        ];
        $mform->addElement(
            'select',
            'config_title_placement',
            get_string('config_title_placement', 'block_nice_courses_slider_5'),
            $titleplacementoptions
        );
        $mform->setDefault('config_title_placement', 0);

        // Add a textarea input element for the block main description.
        $mform->addElement(
            'textarea',
            'config_main_description',
            get_string('config_main_description', 'block_nice_courses_slider_5')
        );
        $mform->setType('config_main_description', PARAM_RAW);

        // Option for showing students enrolled in the course.
        $showstudentsoptions = [
            0 => get_string('no'),
            1 => get_string('yes'),
        ];
        $mform->addElement(
            'select',
            'config_show_students',
            get_string('config_show_students', 'block_nice_courses_slider_5'),
            $showstudentsoptions
        );
        $mform->setDefault('config_show_students', 1);

        // Option for showing description in the course.
        $showdescriptionoptions = [
            0 => get_string('no'),
            1 => get_string('yes'),
        ];
        $mform->addElement(
            'select',
            'config_show_description',
            get_string('config_show_description', 'block_nice_courses_slider_5'),
            $showdescriptionoptions
        );
        $mform->setDefault('config_show_description', 1);

        // Option for showing price in the course.
        $showpriceoptions = [
            0 => get_string('no'),
            1 => get_string('yes'),
        ];
        $mform->addElement(
            'select',
            'config_show_price',
            get_string('config_show_price', 'block_nice_courses_slider_5'),
            $showpriceoptions
        );
        $mform->setDefault('config_show_price', 1);

        // Add a text input element for the block link URL.
        $mform->addElement(
            'text',
            'config_link',
            get_string('config_link', 'block_nice_courses_slider_5')
        );
        $mform->setDefault('config_link', $CFG->wwwroot . '/course');
        $mform->setType('config_link', PARAM_URL);

        // Add a multi-select element for selecting courses.
        $options = [
            'multiple' => true,
            'noselectionstring' => get_string(
                'select_courses',
                'block_nice_courses_slider_5'
            ),
        ];
        $mform->addElement(
            'course',
            'config_courses',
            get_string('courses'),
            $options
        );
    }
}
