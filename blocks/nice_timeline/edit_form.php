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
 * Block edit form for block_nice_timeline.
 *
 * @package     block_nice_timeline
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_nice_timeline_edit_form extends block_edit_form {

    /**
     * Defines custom settings for the block.
     *
     * @param MoodleQuickForm $mform The form object.
     * @return void
     */
    protected function specific_definition($mform) {
        global $CFG;

        $mform->addElement(
            'header',
            'configheader',
            get_string('blocksettings', 'block')
        );

        $mform->addElement(
            'text',
            'config_main_title',
            get_string('config_main_title', 'block_nice_timeline')
        );
        $mform->setDefault('config_main_title', 'Timeline');
        $mform->setType('config_main_title', PARAM_TEXT);

        $titleplacementoptions = [
            0 => get_string('left', 'block_nice_timeline'),
            1 => get_string('right', 'block_nice_timeline'),
            2 => get_string('center', 'block_nice_timeline'),
        ];

        $mform->addElement(
            'select',
            'config_title_placement',
            get_string('config_title_placement', 'block_nice_timeline'),
            $titleplacementoptions
        );
        $mform->setDefault('config_title_placement', 0);

        $mform->addElement(
            'textarea',
            'config_main_description',
            get_string('config_main_description', 'block_nice_timeline')
        );
        $mform->setType('config_main_description', PARAM_TEXT);

        $editoroptions = [
            'maxfiles' => 50,
            'noclean'  => true,
            'context'  => $this->block->context,
        ];

        $filemanageroptions = [
            'maxbytes'       => $CFG->maxbytes,
            'subdirs'        => 0,
            'maxfiles'       => 1,
            'accepted_types' => ['.jpg', '.png', '.gif'],
        ];

        for ($i = 1; $i <= 10; $i++) {
            $mform->addElement(
                'header',
                'config_item' . $i,
                get_string('config_item', 'block_nice_timeline') . $i
            );

            $mform->addElement(
                'text',
                'config_date' . $i,
                get_string('config_date', 'block_nice_timeline', $i)
            );
            $mform->setType('config_date' . $i, PARAM_TEXT);

            $mform->addElement(
                'text',
                'config_title' . $i,
                get_string('config_title', 'block_nice_timeline', $i)
            );
            $mform->setType('config_title' . $i, PARAM_TEXT);

            $mform->addElement(
                'textarea',
                'config_description' . $i,
                get_string('config_description', 'block_nice_timeline', $i)
            );
            $mform->setType('config_description' . $i, PARAM_TEXT);

            $mform->addElement(
                'filemanager',
                'config_image' . $i,
                get_string('config_image', 'block_nice_timeline', $i),
                null,
                $filemanageroptions
            );
        }
    }

    /**
     * Sets default data for the form.
     *
     * @param stdClass|array $defaults Default data.
     * @return void
     */
    public function set_data($defaults) {
        if (!empty($this->block->config) && is_object($this->block->config)) {
            for ($i = 1; $i <= 4; $i++) {
                $field = 'image' . $i;
                $conffield = 'config_image' . $i;

                $draftitemid = file_get_submitted_draft_itemid($conffield);

                file_prepare_draft_area(
                    $draftitemid,
                    $this->block->context->id,
                    'block_nice_timeline',
                    'items',
                    $i,
                    ['subdirs' => false]
                );

                if (!isset($defaults->{$conffield})) {
                    $defaults->{$conffield} = [];
                }

                $defaults->{$conffield}['itemid'] = $draftitemid;
                $this->block->config->{$field} = $draftitemid;
            }
        }
        parent::set_data($defaults);
    }
}
