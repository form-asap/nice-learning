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
 * Block edit form for block_nice_course_description.
 *
 * @package     block_nice_course_description
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Extend the block_edit_form class to customize the edit form of the block.
class block_nice_course_description_edit_form extends block_edit_form {

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
            get_string('config_main_title', 'block_nice_course_description')
        );
        $mform->setDefault('config_main_title', 'Course overview');
        $mform->setType('config_main_title', PARAM_TEXT);

        // Add a dropdown (select element) for "Title placement".
        $titleplacementoptions = [
            0 => get_string('left', 'block_nice_course_description'),
            1 => get_string('right', 'block_nice_course_description'),
            2 => get_string('center', 'block_nice_course_description'),
        ];
        $mform->addElement(
            'select',
            'config_title_placement',
            get_string(
                'config_title_placement',
                'block_nice_course_description'
            ),
            $titleplacementoptions
        );
        $mform->setDefault('config_title_placement', 0);

        // Define editor options for the main description.
        $editoroptions = [
            'maxfiles' => 50,
            'noclean' => true,
            'context' => $this->block->context
        ];

        // HTML for the default value, formatted and escaped to avoid trailing whitespace.
        $defaulttext = trim(
            '<p>Online learning has revolutionized the educational landscape, ' .
            'democratizing access to knowledge in an unprecedented manner. In an age ' .
            'where information is at our fingertips, e-learning platforms like Moodle ' .
            'empower individuals from all corners of the globe to embark on their ' .
            'educational journeys from the comfort of their homes. It provides ' .
            'flexibility, allowing learners to pace themselves according to their ' .
            'schedules and commitments. Beyond the convenience, online learning fosters ' .
            'a culture of self-discipline, as students take greater ownership of their progress.</p>' .
            '<div class="nice-course-description-learning-outcomes-container">' .
            '<h5 class="d-inline text-left">Learning outcomes</h5>' .
            '<ol class="nice-ordered-list nice-ordered-list-rounded">' .
            '<li>Students will be proficient in data analysis using Python. They will know how to manipulate data frames.</li>' .
            '<li>After finishing this course, learners will understand the foundations of machine learning algorithms.</li>' .
            '<li>Students will grasp the essentials of effective communication, from crafting compelling narratives.</li>' .
            '<li>By the end of the course, participants will acquire practical skills in web development.</li>' .
            '<li>This course aims to equip students with the tools to think critically. They will evaluate arguments.</li>' .
            '</ol><!--End ol--></div>'
        );

        $mform->addElement(
            'editor',
            'config_main_description',
            get_string('config_main_description', 'block_nice_course_description'),
            null,
            $editoroptions
        );
        $mform->setDefault(
            'config_main_description',
            ['text' => $defaulttext, 'format' => FORMAT_HTML]
        );

        // Define options for the file manager.
        $filemanageroptions = [
            'maxbytes' => $CFG->maxbytes,
            'subdirs' => 0,
            'maxfiles' => 1,
            'accepted_types' => ['.jpg', '.png', '.gif']
        ];
    }
}
