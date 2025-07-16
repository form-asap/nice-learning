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
 * Theme Nice Learning settings definitions.
 *
 * Nice Learning is a free Moodle theme.
 *
 * @package    theme_nice
 * @category   admin
 * @copyright  2025 Nice Learning <support@docs.nicelearning.org>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();

// Load theme settings only when displaying the admin settings pages for better performance.
// Including separate settings files for modularity and maintainability.
if ($ADMIN->fulltree) {

    // Load general theme settings (colors, logos, etc.).
    require_once('settings/general.php');

    // Load breadcrumb-related settings (breadcrumb background, colors).
    require_once('settings/breadcrumb.php');

    // Load social media settings (links, display options).
    require_once('settings/social_media.php');

    // Load Scroll to Top button settings (visibility, colors).
    require_once('settings/scroll_to_top.php');

    // Load footer settings (footer layout, logos, colors, content).
    require_once('settings/footer.php');

    // Load advanced settings (custom SCSS).
    require_once('settings/advanced_settings.php');
}
