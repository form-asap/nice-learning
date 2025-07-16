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
 * Block edit form for block_nice_hero_1.
 *
 * @package     block_nice_hero_1
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_nice_hero_1_edit_form extends block_edit_form {

    /**
     * Defines the block settings form.
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

        // Add a text input element for the block title.
        $mform->addElement(
            'text',
            'config_title',
            get_string('config_title', 'block_nice_hero_1')
        );
        $mform->setDefault('config_title', 'Start learning from the world’s best institutions');
        $mform->setType('config_title', PARAM_RAW);

        // Add a textarea element for the block description.
        $mform->addElement(
            'textarea',
            'config_description',
            get_string('config_description', 'block_nice_hero_1')
        );
        $mform->setDefault('config_description', 'Start learning from the world’s best institutions');
        $mform->setType('config_description', PARAM_RAW);

        // Add a filemanager element for an image.
        $mform->addElement(
            'filemanager',
            'config_image',
            get_string('config_image', 'block_nice_hero_1'),
            null,
            [
                'subdirs' => 0,
                'maxbytes' => 2 * 1024 * 1024,
                'areamaxbytes' => 10485760,
                'maxfiles' => 1,
                'accepted_types' => ['.png', '.jpg', '.gif', '.jpeg', '.webp'],
            ]
        );
    }

    /**
     * Sets data for the block form.
     *
     * @param mixed $defaults Default data for the form.
     * @return void
     */
    public function set_data($defaults) {
        // Check if an entry object exists; if not, create it.
        if (empty($entry->id)) {
            $entry = new stdClass();
            $entry->id = null;
        }

        // Retrieve the draft item ID for a submitted file.
        // Draft items represent temporary storage for files before they're permanently saved.
        $draftitemid = file_get_submitted_draft_itemid('config_image');

        // Prepare the draft area for file management.
        file_prepare_draft_area(
            $draftitemid, // The ID of the draft item.
            $this->block->context->id, // The context ID for the block.
            'block_nice_hero_1', // The component name.
            'content', // The file area.
            0, // The ID of the item in the file area.
            ['subdirs' => true] // Allow subdirectories in the draft area.
        );

        // Store the draft item ID for the image field.
        $entry->attachments = $draftitemid;

        // Call the parent class's set_data() method.
        parent::set_data($defaults);

        // After setting data, check if there is data to retrieve from the form.
        if ($data = parent::get_data()) {
            // Save files from the draft area to the permanent file area.
            file_save_draft_area_files(
                $data->config_image, // The data from the config_image field.
                $this->block->context->id, // The context ID for the block.
                'block_nice_hero_1', // The component name.
                'content', // The file area.
                0, // The item ID in the file area.
                ['subdirs' => true] // Allow subdirectories.
            );
        }
    }
}
