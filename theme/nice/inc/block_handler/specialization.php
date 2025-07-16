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
 * Defines default block region assignments for custom blocks in theme_nice.
 *
 * This file configures where custom blocks from the Nice theme
 * should appear on the page layout (e.g. fullwidth, sidebar, above content).
 *
 * @package     theme_nice
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Get the name of the block instance being configured.
$niceblocktype = $this->instance->blockname;

// Define collections of block types and their default regions.
$nicecollectionfullwidthtop = [
    "nice_hero_1",
    "nice_hero_2",
    "nice_hero_3",
    "nice_hero_4",

    "nice_boxes_1",
    "nice_boxes_2",
    "nice_boxes_3",
    "nice_boxes_4",

    "nice_about_us_1",
    "nice_about_us_2",
    "nice_about_us_3",
    "nice_about_us_4",

    "nice_courses_slider_1",
    "nice_courses_slider_2",
    "nice_courses_slider_3",
    "nice_courses_slider_4",
    "nice_courses_slider_5",

    'nice_categories_slider_1',
    'nice_categories_slider_2',

    'nice_blogs_slider_1',
    'nice_blogs_slider_2',

    'nice_testimonials_slider_1',
    'nice_testimonials_slider_2',

    'nice_timeline',
    'nice_accordion',

    'nice_categories_page_1',
    'nice_courses_page_1',

    'nice_subscribe_with_us',
    'nice_contact_us_page_1',

    'nice_instructors_page_1',
    'nice_instructors_slider_1',

    'nice_featured_course',
];

$nicecollectionsidebar = [
    'nice_course_features',
    'nice_course_need_help',
    'nice_course_details',
];

$nicecollectionabovecontent = [
    'nice_tabs',
    'nice_course_description',
    'nice_course_video',
];

// Merge all block types into one array.
$nicecollection = array_merge(
    $nicecollectionfullwidthtop,
    $nicecollectionsidebar,
    $nicecollectionabovecontent
);

// If the block has no saved configuration yet,
// assign it to a default region based on its block type.
if (empty($this->config)) {
    if (in_array($niceblocktype, $nicecollectionfullwidthtop)) {
        $this->instance->defaultregion = 'fullwidth-top';
        $this->instance->region = 'fullwidth-top';
        $DB->update_record('block_instances', $this->instance);
    }

    if (in_array($niceblocktype, $nicecollectionsidebar)) {
        $this->instance->defaultregion = 'side-pre';
        $this->instance->region = 'side-pre';
        $DB->update_record('block_instances', $this->instance);
    }

    if (in_array($niceblocktype, $nicecollectionabovecontent)) {
        $this->instance->defaultregion = 'above-content';
        $this->instance->region = 'above-content';
        $DB->update_record('block_instances', $this->instance);
    }
}
