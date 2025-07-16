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
 * A two column layout for the boost theme.
 *
 * @package   theme_nice
 * @copyright Based on 2016 Damyon Wiese theme_boost
 * @copyright 2025 Nice Learning <support@docs.nicelearning.org>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Include custom theme helper functions.
require_once($CFG->dirroot . '/theme/nice/inc/nice_themehandler.php');

// Optionally include Behat library for testing purposes.
require_once($CFG->libdir . '/behat/lib.php');

// Define additional CSS classes to apply to the <body> tag.
$extraclasses = [];

// Generate HTML attributes for the <body> tag (id, class, dir, lang).
$bodyattributes = $OUTPUT->body_attributes($extraclasses);

// Render any blocks that may appear in the side-pre region.
$blockshtml = $OUTPUT->blocks('side-pre');

// Check if there are any blocks rendered.
$hasblocks = strpos($blockshtml, 'data-block=') !== false;

// Include code to build the $templatecontext array for Mustache templates.
require_once($CFG->dirroot . '/theme/nice/inc/nice_themehandler_context.php');

// Render the page template using the provided context data.
echo $OUTPUT->render_from_template('theme_boost/columns2', $templatecontext);
