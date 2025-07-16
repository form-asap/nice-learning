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
 * Standard functions for block_nice_hero_2.
 *
 * @package     block_nice_hero_2
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function block_nice_hero_2_pluginfile(
    $course,
    $birecordorcm,
    $context,
    $filearea,
    $args,
    $forcedownload,
    array $options = []
) {
    global $DB, $CFG;

    // Ensure the context of the file is the block's context.
    if ($context->contextlevel != CONTEXT_BLOCK) {
        send_file_not_found();
    }

    // If the block is in a course context, check if the user has the capability to access the course.
    if ($context->get_course_context(false)) {
        require_course_login($course);
    } else if ($CFG->forcelogin) {
        // If site-wide login is enforced, require the user to be logged in.
        require_login();
    } else {
        // If block isn't in a course or site-wide context, it might be in a category context.
        $parentcontext = $context->get_parent_context();
        if ($parentcontext->contextlevel === CONTEXT_COURSECAT) {
            $category = $DB->get_record(
                'course_categories',
                ['id' => $parentcontext->instanceid],
                '*',
                MUST_EXIST
            );
            // If the category isn't visible, check if the user has the capability to view hidden categories.
            if (!$category->visible) {
                require_capability('moodle/category:viewhiddencategories', $parentcontext);
            }
        }
    }

    // Ensure that the file area is 'content'.
    if ($filearea !== 'content') {
        send_file_not_found();
    }

    $fs = get_file_storage();

    // Extract the file name and build the file path.
    $filename = array_pop($args);
    $filepath = $args ? '/' . implode('/', $args) . '/' : '/';

    // Check if the file exists and isn't a directory.
    $file = $fs->get_file(
        $context->id,
        'block_nice_hero_2',
        'content',
        0,
        $filepath,
        $filename
    );

    if (!$file || $file->is_directory()) {
        send_file_not_found();
    }

    // If the block's parent context is a user, force the download of the file.
    $parentcontext = context::instance_by_id($birecordorcm->parentcontextid, IGNORE_MISSING);
    if ($parentcontext) {
        if ($parentcontext->contextlevel == CONTEXT_USER) {
            $forcedownload = true;
        }
    } else {
        $forcedownload = true;
    }

    // Close the current user's session to free up resources.
    \core\session\manager::write_close();

    // Send the file for download or viewing.
    send_stored_file($file, 60 * 60, 0, $forcedownload, $options);
}
