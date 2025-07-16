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
  * Layout file for the "My home" page in theme_nice.
  *
  * @package     theme_nice
  * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
  * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
  */

defined('MOODLE_INTERNAL') || die();

// Include custom theme helper functions.
require_once($CFG->dirroot . '/theme/nice/inc/nice_themehandler.php');

// Optionally include Behat library for testing purposes.
require_once($CFG->libdir . '/behat/lib.php');

// Initialize extra body classes for this layout.
array_push($extraclasses, "nice_context_frontend");

// Convert the array of classes into a space-separated string.
$bodyclasses = implode(" ", $extraclasses);

// Generate body attributes string (e.g. id, class, dir, lang).
$bodyattributes = $OUTPUT->body_attributes($bodyclasses);

// Render any blocks that may appear in the side-pre region.
$blockshtml = $OUTPUT->blocks('side-pre');

// Check if there are any blocks rendered.
$hasblocks = strpos($blockshtml, 'data-block=') !== false;

// Include code to build the $templatecontext array for Mustache templates.
require_once($CFG->dirroot . '/theme/nice/inc/nice_themehandler_context.php');

// Render the page template using the provided context data.
echo $OUTPUT->render_from_template('theme_nice/nice_my', $templatecontext);
