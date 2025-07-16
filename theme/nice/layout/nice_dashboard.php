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
 * Layout file for the Nice dashboard page.
 *
 * This layout renders the theme_nice custom dashboard template.
 *
 * @package     theme_nice
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Output the HTML doctype declaration.
echo $OUTPUT->doctype();

// Include custom theme helper functions.
require_once($CFG->dirroot . '/theme/nice/inc/nice_themehandler.php');

// Generate body attributes string (e.g. id, class, dir).
$bodyattributes = $OUTPUT->body_attributes();

// Include code to build the $templatecontext array for Mustache templates.
require_once($CFG->dirroot . '/theme/nice/inc/nice_themehandler_context.php');

// Render the page template using the provided context data.
echo $OUTPUT->render_from_template('theme_nice/nice_dashboard', $templatecontext);
