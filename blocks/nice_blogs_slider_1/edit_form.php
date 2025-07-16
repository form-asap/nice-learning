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
 * Block edit form for block_nice_blogs_slider_1.
 *
 * @package     block_nice_blogs_slider_1
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_nice_blogs_slider_1_edit_form extends block_edit_form {

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
            get_string('config_main_title', 'block_nice_blogs_slider_1')
        );
        // Set a default value for the block main title text input that you've just added.
        $mform->setDefault('config_main_title', 'All Blogs');
        // Define the data type for the block main title text input.
        // In this case, it's set to accept raw data, which means Moodle won't perform any additional cleaning or validation.
        $mform->setType('config_main_title', PARAM_RAW);

        // Add a dropdown (select element) for "Title placement".
        $titleplacementoptions = [
            0 => get_string('left', 'block_nice_blogs_slider_1'),
            1 => get_string('right', 'block_nice_blogs_slider_1'),
            2 => get_string('center', 'block_nice_blogs_slider_1'),
        ];
        $mform->addElement(
            'select',
            'config_title_placement',
            get_string('config_title_placement', 'block_nice_blogs_slider_1'),
            $titleplacementoptions
        );
        // Set default value for title placement as "left".
        $mform->setDefault('config_title_placement', 0);

        // Add a text input element to the form for configuring the block main description.
        // The label for this element is fetched from the language file associated with your custom block.
        $mform->addElement(
            'textarea',
            'config_main_description',
            get_string('config_main_description', 'block_nice_blogs_slider_1')
        );
        // Define the data type for the block main description text input.
        // In this case, it's set to accept raw data, which means Moodle won't perform any additional cleaning or validation.
        $mform->setType('config_main_description', PARAM_RAW);

        // Option for showing blog date.
        $showdateoptions = [
            0 => get_string('no'),
            1 => get_string('yes'),
        ];
        $mform->addElement(
            'select',
            'config_show_date',
            get_string('config_show_date', 'block_nice_blogs_slider_1'),
            $showdateoptions
        );
        $mform->setDefault('config_show_date', 1); // Default to show.

        // Option for showing blog user.
        $showuseroptions = [
            0 => get_string('no'),
            1 => get_string('yes'),
        ];
        $mform->addElement(
            'select',
            'config_show_user',
            get_string('config_show_user', 'block_nice_blogs_slider_1'),
            $showuseroptions
        );
        $mform->setDefault('config_show_user', 1); // Default to show.

        // Option for showing description.
        $showdescriptionoptions = [
            0 => get_string('no'),
            1 => get_string('yes'),
        ];
        $mform->addElement(
            'select',
            'config_show_description',
            get_string('config_show_description', 'block_nice_blogs_slider_1'),
            $showdescriptionoptions
        );
        $mform->setDefault('config_show_description', 1); // Default to show.

        // Add a text input element for "Link".
        $mform->addElement(
            'text',
            'config_link',
            get_string('config_link', 'block_nice_blogs_slider_1')
        );
        $mform->setDefault('config_link', $CFG->wwwroot . '/blog');
        $mform->setType('config_link', PARAM_URL);

        // Initialize the blog listing to retrieve blog entries.
        $bloglistinginstance = new blog_listing();

        // Fetch all the blog entries.
        $allblogentries = $bloglistinginstance->get_entries();

        // Prepare an associative array to hold the blog entry IDs and their subjects.
        $blogentriesforform = [];

        // Populate the associative array with the blog entry IDs and subjects.
        foreach ($allblogentries as $blogentryid => $blogentry) {
            $blogentriesforform[$blogentry->id] = $blogentry->subject;
        }

        // Set form options. 'multiple' indicates multiple blog entries can be selected.
        $formoptions = [
            'multiple' => true,
        ];

        // Add the blog entries as an autocomplete form element.
        $mform->addElement(
            'autocomplete',
            'config_posts',
            get_string('posts'),
            $blogentriesforform,
            $formoptions
        );
    }
}
