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
 * Block edit form for block_nice_about_us_1.
 *
 * @package     block_nice_about_us_1
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_nice_about_us_1_edit_form extends block_edit_form {

    protected function specific_definition($mform) {
        global $CFG;

        // Add a header element to the form. This is a visual element to segregate sections in the form.
        // The header title is fetched from the language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // Add a text input element to the form for configuring the block main title.
        // The label for this element is fetched from the language file associated with your custom block.
        $mform->addElement(
            'text',
            'config_main_title',
            get_string('config_main_title', 'block_nice_about_us_1')
        );
        // Set a default value for the block main title text input that you've just added.
        $mform->setDefault('config_main_title', 'About Us');
        // Define the data type for the block main title text input.
        // In this case, it's set to accept raw data, which means Moodle won't perform additional cleaning or validation.
        $mform->setType('config_main_title', PARAM_RAW);

        // Add a dropdown (select element) for "Title placement".
        $titleplacementoptions = [
            0 => get_string('left', 'block_nice_about_us_1'),
            1 => get_string('right', 'block_nice_about_us_1'),
            2 => get_string('center', 'block_nice_about_us_1'),
        ];
        $mform->addElement(
            'select',
            'config_title_placement',
            get_string('config_title_placement', 'block_nice_about_us_1'),
            $titleplacementoptions
        );
        // Set default value for title placement as "left".
        $mform->setDefault('config_title_placement', 0);

        // Add a textarea input element to the form for configuring the block main description.
        // The label for this element is fetched from the language file associated with your custom block.
        $mform->addElement(
            'textarea',
            'config_main_description',
            get_string('config_main_description', 'block_nice_about_us_1')
        );
        // Set a default value for the block main description textarea that you've just added.
        $mform->setDefault(
            'config_main_description',
            'In an ever-evolving world, learning stands as the beacon that guides individuals towards personal and '
            . 'professional growth. It is not merely the absorption of facts, but the cultivation of a mindset that '
            . 'is curious, adaptable, and open to new experiences. Whether we are diving deep into academic subjects '
            . 'or picking up life skills, the process of learning enriches our understanding, broadens our horizons, '
            . 'and equips us with tools to navigate the challenges and opportunities life presents.'
        );
        // Define the data type for the block main description textarea.
        // As with the title, it's set to accept raw data.
        $mform->setType('config_main_description', PARAM_RAW);

        // Add a text input element for main link.
        $mform->addElement(
            'text',
            'config_main_link',
            get_string('config_main_link', 'block_nice_about_us_1')
        );
        $mform->setType('config_main_link', PARAM_URL);

        // Add a file manager element to the form to allow the user to upload and manage images associated with the block.
        // The label for this element is fetched from the language file associated with your custom block.
        // Various settings such as maximum file size, maximum area bytes, and accepted file types are defined.
        $mform->addElement(
            'filemanager',
            'config_image',
            get_string('config_image', 'block_nice_about_us_1'),
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
     * Set data for the block form.
     *
     * @param mixed $defaults Default data for the form.
     */
    public function set_data($defaults) {
        // Check if an entry ID exists. If not, create a new stdClass object for the entry.
        if (empty($entry->id)) {
            $entry = new stdClass();
            $entry->id = null;
        }

        // Retrieve the draft item ID for a submitted file.
        // This is usually done when files are being managed in a form in Moodle.
        // Draft items represent temporary storage for files before they're permanently saved.
        $draftitemid = file_get_submitted_draft_itemid('config_image');

        // Prepare the draft area for the files. This makes sure the draft area is ready to receive and manage files.
        file_prepare_draft_area(
            $draftitemid, // The ID of the draft item.
            $this->block->context->id, // The context ID associated with the block.
            'block_nice_about_us_1', // The component name.
            'content', // The file area.
            0, // The ID of the item within the file area.
            ['subdirs' => true] // Allow subdirectories within the draft area.
        );

        // Store the draft item ID within the entry object.
        // This is useful for keeping track of which files belong to which draft item.
        $entry->attachments = $draftitemid;

        // Call the parent class's set_data function with the provided defaults.
        // This typically sets form data for rendering.
        parent::set_data($defaults);

        // After setting the data, check if there's data to retrieve from the form.
        if ($data = parent::get_data()) {
            // If there's data to retrieve, save the files from the draft area to their final location.
            file_save_draft_area_files(
                $data->config_image, // The data associated with the 'config_image' form field.
                $this->block->context->id, // The context ID associated with the block.
                'block_nice_about_us_1', // The component name.
                'content', // The file area.
                0, // The ID of the item within the file area.
                ['subdirs' => true] // Allow subdirectories.
            );
        }
    }
}
