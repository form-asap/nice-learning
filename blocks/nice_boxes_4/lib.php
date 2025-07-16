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
 * Standard functions for block_nice_boxes_4.
 *
 * @package     block_nice_boxes_4
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function block_nice_boxes_4_pluginfile(
    $course,
    $birecordorcm,
    $context,
    $filearea,
    $args,
    $forcedownload,
    array $options = []
) {
    global $DB, $CFG, $USER;

    if ($context->contextlevel != CONTEXT_BLOCK) {
        send_file_not_found();
    }

    // If the block is in a course context, check if the user has capability to access the course.
    if ($context->get_course_context(false)) {
        require_course_login($course);
    } else if ($CFG->forcelogin) {
        require_login();
    } else {
        // Get the parent context and check if the user has proper permission.
        $parentcontext = $context->get_parent_context();
        if ($parentcontext->contextlevel === CONTEXT_COURSECAT) {
            // Check if the category is visible and whether the user can view this category.
            $category = $DB->get_record(
                'course_categories',
                ['id' => $parentcontext->instanceid],
                '*',
                MUST_EXIST
            );
            if (!$category->visible) {
                require_capability('moodle/category:viewhiddencategories', $parentcontext);
            }
        } else if (
            $parentcontext->contextlevel === CONTEXT_USER &&
            $parentcontext->instanceid != $USER->id
        ) {
            // The block is in the context of a user. It is only visible to the user who it belongs to.
            send_file_not_found();
        }
        // At this point, there is no way to check SYSTEM context, so ignoring it.
    }

    if ($filearea !== 'items') {
        send_file_not_found();
    }

    $fs = get_file_storage();

    if (count($args) != 2) {
        send_file_not_found();
    }

    $file = $fs->get_file(
        $context->id,
        'block_nice_boxes_4',
        'items',
        $args[0],
        '/',
        $args[1]
    );

    if (!$file || $file->is_directory()) {
        send_file_not_found();
    }

    $parentcontext = context::instance_by_id($birecordorcm->parentcontextid, IGNORE_MISSING);
    if ($parentcontext) {
        if ($parentcontext->contextlevel == CONTEXT_USER) {
            // Force download on all personal pages including /my/,
            // because we do not have a reliable way to find out from where this is used.
            $forcedownload = true;
        }
    } else {
        // Weird. There should be a parent context. Better force download then.
        $forcedownload = true;
    }

    // Close the current user's session to free up resources.
    \core\session\manager::write_close();

    // Send the file for download or viewing.
    send_stored_file($file, null, 0, $forcedownload, $options);
}
