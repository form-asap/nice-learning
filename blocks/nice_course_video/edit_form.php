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
 * Block edit form for block_nice_course_video.
 *
 * @package     block_nice_course_video
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_nice_course_video_edit_form extends block_edit_form {

    /**
     * Defines the custom settings for the block.
     *
     * @param MoodleQuickForm $mform The form object.
     * @return void
     */
    protected function specific_definition($mform) {
        global $CFG;

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
            get_string('config_main_title', 'block_nice_course_video')
        );
        $mform->setDefault('config_main_title', 'Course overview');
        $mform->setType('config_main_title', PARAM_TEXT);

        // Add a dropdown (select element) for "Title placement".
        $titleplacementoptions = [
            0 => get_string('left', 'block_nice_course_video'),
            1 => get_string('right', 'block_nice_course_video'),
            2 => get_string('center', 'block_nice_course_video'),
        ];
        $mform->addElement(
            'select',
            'config_title_placement',
            get_string('config_title_placement', 'block_nice_course_video'),
            $titleplacementoptions
        );
        $mform->setDefault('config_title_placement', 0);

        // Add a text input element for the video URL.
        $mform->addElement(
            'text',
            'config_video_url',
            get_string('config_video_url', 'block_nice_course_video')
        );
        $mform->setDefault(
            'config_video_url',
            'https://www.youtube.com/watch?v=PkZNo7MFNFg'
        );
        $mform->setType('config_video_url', PARAM_URL);

        // Define editor options for the description.
        $editoroptions = [
            'maxfiles' => 50,
            'noclean' => true,
            'context' => $this->block->context,
        ];

        // Add an editor field for the main description.
        $mform->addElement(
            'editor',
            'config_main_description',
            get_string('config_main_description', 'block_nice_course_video'),
            null,
            $editoroptions
        );
        $mform->setDefault('config_main_description', [
            'text' => '<p>Online learning has revolutionized the educational 
            landscape, democratizing access to knowledge in an unprecedented 
            manner. In an age where information is at our fingertips, 
            e-learning platforms like Moodle empower individuals from all 
            corners of the globe to embark on their educational journeys 
            from the comfort of their homes. It provides flexibility, 
            allowing learners to pace themselves according to their 
            schedules and commitments. Beyond the convenience, online 
            learning fosters a culture of self-discipline, as students 
            take greater ownership of their progress.</p>',
            'format' => FORMAT_HTML
        ]);

        // Define options for the file manager (though not currently used).
        $filemanageroptions = [
            'maxbytes' => $CFG->maxbytes,
            'subdirs' => 0,
            'maxfiles' => 1,
            'accepted_types' => ['.jpg', '.png', '.gif'],
        ];

        // Option for showing course teacher.
        $showteacheroptions = [
            0 => get_string('no'),
            1 => get_string('yes'),
        ];
        $mform->addElement(
            'select',
            'config_show_teacher',
            get_string('config_show_teacher', 'block_nice_course_video'),
            $showteacheroptions
        );
        $mform->setDefault('config_show_teacher', 1);

        // Option for showing course category.
        $showcategoryoptions = [
            0 => get_string('no'),
            1 => get_string('yes'),
        ];
        $mform->addElement(
            'select',
            'config_show_category',
            get_string('config_show_category', 'block_nice_course_video'),
            $showcategoryoptions
        );
        $mform->setDefault('config_show_category', 1);

        // Option for showing course students.
        $showstudentsoptions = [
            0 => get_string('no'),
            1 => get_string('yes'),
        ];
        $mform->addElement(
            'select',
            'config_show_students',
            get_string('config_show_students', 'block_nice_course_video'),
            $showstudentsoptions
        );
        $mform->setDefault('config_show_students', 1);
    }
}
