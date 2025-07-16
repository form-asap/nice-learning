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
 * Block edit form for block_nice_tabs.
 *
 * @package     block_nice_tabs
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Extend the block_edit_form class to customize the edit form of the block.
class block_nice_tabs_edit_form extends block_edit_form {

    /**
     * Defines the custom settings for the block.
     *
     * @param MoodleQuickForm $mform The Moodle form object.
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
            get_string('config_main_title', 'block_nice_tabs')
        );
        $mform->setDefault('config_main_title', 'Frequently Asked Questions');
        $mform->setType('config_main_title', PARAM_TEXT);

        // Add a dropdown (select element) for "Title placement".
        $titleplacementoptions = [
            0 => get_string('left', 'block_nice_tabs'),
            1 => get_string('right', 'block_nice_tabs'),
            2 => get_string('center', 'block_nice_tabs'),
        ];
        $mform->addElement(
            'select',
            'config_title_placement',
            get_string('config_title_placement', 'block_nice_tabs'),
            $titleplacementoptions
        );
        $mform->setDefault('config_title_placement', 0);

        // Define editor options for the text editor.
        $editoroptions = [
            'maxfiles' => 50,
            'noclean' => true,
            'context' => $this->block->context,
        ];

        // Loop for creating settings of five items.
        for ($i = 1; $i <= 5; $i++) {
            // Add a header for each item.
            $mform->addElement(
                'header',
                'config_item' . $i,
                get_string('config_item', 'block_nice_tabs') . $i
            );

            // Add a text input field for each item title.
            $mform->addElement(
                'text',
                'config_title' . $i,
                get_string('config_title', 'block_nice_tabs', $i)
            );
            $mform->setType('config_title' . $i, PARAM_TEXT);

            // Add an editor input field for each item description.
            $mform->addElement(
                'editor',
                'config_description' . $i,
                get_string('config_description', 'block_nice_tabs'),
                null,
                $editoroptions
            );
        }
    }
}
