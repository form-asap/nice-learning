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
 * Plugin version and other meta-data are defined here.
 *
 * @package     theme_nice
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();

$plugin->component = 'theme_nice';

// This is the version of the plugin.
$plugin->version = 2025081000; // The current plugin version (Date: YYYYMMDDXX).

// This is the version of Moodle this plugin requires.
$plugin->requires = 2025041400;

// This is a list of plugins this plugin depends on (and their versions).
$plugin->dependencies = [
    'theme_boost' => 2025041400,
];

// This is the named version.
$plugin->release = '1.4';

// This version's maturity level.
$plugin->maturity = MATURITY_STABLE;
