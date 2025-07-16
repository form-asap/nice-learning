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
 * Page handler class for theme_nice.
 *
 * Provides helper methods for working with page-level data,
 * e.g. determining the correct page title for various contexts.
 *
 * @package     theme_nice
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/course/lib.php');

/**
 * Class nicePageHandler
 *
 * Provides utilities for retrieving page-level data for theme_nice.
 */
class nicePageHandler {

    /**
     * Determines the most appropriate title for the current page.
     *
     * This method:
     * - uses the activity name if on the front page and viewing a module
     * - uses the blog string if on the blog index page
     * - falls back to the default page heading otherwise
     *
     * @return string The calculated page title.
     */
    public function nicegetpagetitle() {
        global $PAGE, $COURSE, $DB, $CFG;

        // Start with the default page heading.
        $nicereturn = $PAGE->heading;

        // Check if we're on the front page viewing a specific activity module.
        if (
            $DB->record_exists('course', ['id' => $COURSE->id]) &&
            $COURSE->format == 'site' &&
            $PAGE->cm &&
            $PAGE->cm->name !== null
        ) {
            $nicereturn = $PAGE->cm->name;

        } else if ($PAGE->pagetype == 'blog-index') {
            // If we're on the blog index page, use the blog string.
            $nicereturn = get_string("blog", "blog");
        }

        return $nicereturn;
    }
}
