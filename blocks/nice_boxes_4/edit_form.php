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
 * Block edit form for block_nice_boxes_4.
 *
 * @package     block_nice_boxes_4
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Extend the block_edit_form class to customize the edit form of the block.
class block_nice_boxes_4_edit_form extends block_edit_form {

    /**
     * Defines the custom settings for our block.
     *
     * @param MoodleQuickForm $mform The form object.
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
            get_string('config_main_title', 'block_nice_boxes_4')
        );
        // Set default value for the main title.
        $mform->setDefault('config_main_title', 'Why Choose Us');
        // Define the data type for main title as text.
        $mform->setType('config_main_title', PARAM_TEXT);

        // Add a dropdown (select element) for "Title placement".
        $titleplacementoptions = [
            0 => get_string('left', 'block_nice_boxes_4'),
            1 => get_string('right', 'block_nice_boxes_4'),
            2 => get_string('center', 'block_nice_boxes_4'),
        ];
        $mform->addElement(
            'select',
            'config_title_placement',
            get_string('config_title_placement', 'block_nice_boxes_4'),
            $titleplacementoptions
        );
        // Set default value for title placement as "left".
        $mform->setDefault('config_title_placement', 0);

        // Add a text input field for the main description.
        $mform->addElement(
            'textarea',
            'config_main_description',
            get_string('config_main_description', 'block_nice_boxes_4')
        );
        // Define the data type for main description as text.
        $mform->setType('config_main_description', PARAM_TEXT);

        // Define options for the file manager.
        $filemanageroptions = [
            'maxbytes'       => $CFG->maxbytes,
            'subdirs'        => 0,
            'maxfiles'       => 1,
            'accepted_types' => ['.jpg', '.png', '.gif'],
        ];

        // Loop for creating settings of four items.
        for ($i = 1; $i <= 4; $i++) {
            // Add a header for each item.
            $mform->addElement(
                'header',
                'config_item' . $i,
                get_string('config_item', 'block_nice_boxes_4') . $i
            );

            // Add a text input field for each item sub title.
            $mform->addElement(
                'text',
                'config_sub_title' . $i,
                get_string('config_sub_title', 'block_nice_boxes_4', $i)
            );
            // Set default title for each item.
            $mform->setDefault('config_sub_title' . $i, 'Sub title');
            // Define the data type for each item sub title as text.
            $mform->setType('config_sub_title' . $i, PARAM_TEXT);

            // Add a text input field for each item title.
            $mform->addElement(
                'text',
                'config_title' . $i,
                get_string('config_title', 'block_nice_boxes_4', $i)
            );
            // Set default title for each item.
            $mform->setDefault('config_title' . $i, 'Title');
            // Define the data type for each item title as text.
            $mform->setType('config_title' . $i, PARAM_TEXT);

            // Add a file manager for uploading images for each item.
            $mform->addElement(
                'filemanager',
                'config_image' . $i,
                get_string('config_image', 'block_nice_boxes_4', $i),
                null,
                $filemanageroptions
            );
        }
    }

    /**
     * Sets default values for the form elements.
     *
     * @param mixed $defaults Default values for the form.
     */
    public function set_data($defaults) {
        // Check if the block's config exists and is an object.
        if (!empty($this->block->config) && is_object($this->block->config)) {
            // Loop for setting default image data of four items.
            for ($i = 1; $i <= 4; $i++) {
                $field = 'image' . $i;
                $conffield = 'config_image' . $i;

                // Fetch the draft item id of the image.
                $draftitemid = file_get_submitted_draft_itemid($conffield);

                // Prepare the draft area for the image upload.
                file_prepare_draft_area(
                    $draftitemid,
                    $this->block->context->id,
                    'block_nice_boxes_4',
                    'items',
                    $i,
                    ['subdirs' => false]
                );

                // Assign the draft item id to the default data.
                $defaults->{$conffield} = ['itemid' => $draftitemid];

                // Update the block's config with the draft item id.
                $this->block->config->{$field} = $draftitemid;
            }
        }
        // Call the parent class's set_data function to process default values.
        parent::set_data($defaults);
    }
}
