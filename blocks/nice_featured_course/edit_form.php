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
 * Block edit form for block_nice_featured_course.
 *
 * @package     block_nice_featured_course
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_nice_featured_course_edit_form extends block_edit_form {

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
            get_string('config_main_title', 'block_nice_featured_course')
        );
        $mform->setDefault('config_main_title', 'All Courses');
        $mform->setType('config_main_title', PARAM_RAW);

        // Add a dropdown for title placement.
        $titleplacementoptions = [
            0 => get_string('left', 'block_nice_featured_course'),
            1 => get_string('right', 'block_nice_featured_course'),
            2 => get_string('center', 'block_nice_featured_course'),
        ];
        $mform->addElement(
            'select',
            'config_title_placement',
            get_string('config_title_placement', 'block_nice_featured_course'),
            $titleplacementoptions
        );
        $mform->setDefault('config_title_placement', 0);

        // Add a textarea input element for the block main description.
        $mform->addElement(
            'textarea',
            'config_main_description',
            get_string('config_main_description', 'block_nice_featured_course')
        );
        $mform->setType('config_main_description', PARAM_RAW);

        // Option for showing course short name.
        $showshortnameoptions = [
            0 => get_string('no'),
            1 => get_string('yes'),
        ];
        $mform->addElement(
            'select',
            'config_show_shortname',
            get_string('config_show_shortname', 'block_nice_featured_course'),
            $showshortnameoptions
        );
        $mform->setDefault('config_show_shortname', 1);

        // Option for showing course end date.
        $showenddateoptions = [
            0 => get_string('no'),
            1 => get_string('yes'),
        ];
        $mform->addElement(
            'select',
            'config_show_enddate',
            get_string('config_show_enddate', 'block_nice_featured_course'),
            $showenddateoptions
        );
        $mform->setDefault('config_show_enddate', 1);

        // Option for showing students enrolled in the course.
        $showstudentsoptions = [
            0 => get_string('no'),
            1 => get_string('yes'),
        ];
        $mform->addElement(
            'select',
            'config_show_students',
            get_string('config_show_students', 'block_nice_featured_course'),
            $showstudentsoptions
        );
        $mform->setDefault('config_show_students', 1);

        // Option for showing price in the course.
        $showpriceoptions = [
            0 => get_string('no'),
            1 => get_string('yes'),
        ];
        $mform->addElement(
            'select',
            'config_show_price',
            get_string('config_show_price', 'block_nice_featured_course'),
            $showpriceoptions
        );
        $mform->setDefault('config_show_price', 1);

        // Add a single-course selector.
        $options = [
            'noselectionstring' => get_string(
                'select_course',
                'block_nice_featured_course'
            ),
        ];
        $mform->addElement(
            'course',
            'config_course',
            get_string('course'),
            $options
        );

        // Option for choosing section direction (reverse or normal).
        $sectiondirectionsoptions = [
            0 => get_string('reverse', 'block_nice_featured_course'),
            1 => get_string('normal'),
        ];
        $mform->addElement(
            'select',
            'config_section_direction',
            get_string(
                'config_section_direction',
                'block_nice_featured_course'
            ),
            $sectiondirectionsoptions
        );
        $mform->setDefault('config_section_direction', 1);
    }
}
