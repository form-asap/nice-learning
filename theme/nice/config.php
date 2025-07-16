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
 * The configuration for theme_nice is defined here.
 *
 * @package    theme_nice
 * @copyright  2025 Nice Learning <support@docs.nicelearning.org>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Define the internal name of the theme.
$THEME->name = 'nice';

// List of CSS stylesheet files (without the .css extension) to be included in every page.
$THEME->sheets = [
    'owlcarousel',
    'vars',
    'bootstrapmoodle',
    'nice',
    'dashboard',
    'footer',
    'style',
];

// CSS stylesheets to be included specifically in the HTML editor.
$THEME->editor_sheets = [];

// Parent theme: this theme inherits from 'boost' which provides the base styles and layout.
$THEME->parents = ['boost'];

// Specify the renderer factory to allow theme to override core renderers with its own implementations.
$THEME->rendererfactory = 'theme_overridden_renderer_factory';

// Specify blocks that must always be present on all pages. Empty means no required blocks.
$THEME->requiredblocks = '';

// Define where the "Add a block" button appears. FLATNAV places it in the flat navigation drawer.
$THEME->addblockposition = BLOCK_ADDBLOCK_POSITION_FLATNAV;

$THEME->layouts = [
    // Minimal layout without blocks, used for simple pages needing maximum compatibility.
    'base' => [
        'file' => 'columns2.php',
        'regions' => [],
    ],
    // Standard page layout with multiple block regions, suitable for most pages with sidebars.
    'standard' => [
        'file' => 'columns2.php',
        'regions' => ['fullwidth-top', 'fullwidth-bottom', 'above-content', 'below-content', 'side-pre'],
        'defaultregion' => 'side-pre',
    ],
    // Main course page.
    'course' => [
        'file' => 'columns2.php',
        'regions' => ['fullwidth-top', 'fullwidth-bottom', 'above-content', 'below-content', 'side-pre'],
        'defaultregion' => 'side-pre',
        'options' => ['langmenu' => true],
    ],
    // Layout used for course category pages, supporting blocks and full-width regions.
    'coursecategory' => [
        'file' => 'columns2.php',
        'regions' => ['fullwidth-top', 'fullwidth-bottom', 'above-content', 'below-content', 'side-pre'],
        'defaultregion' => 'side-pre',
    ],
    // Layout for activity and resource pages within a course context.
    'incourse' => [
        'file' => 'incourse.php',
        'regions' => ['fullwidth-top', 'fullwidth-bottom', 'above-content', 'below-content', 'side-pre'],
        'defaultregion' => 'side-pre',
    ],
    // The site home page.
    'frontpage' => [
        'file' => 'columns2.php',
        'regions' => ['fullwidth-top', 'fullwidth-bottom', 'above-content', 'below-content', 'side-pre'],
        'defaultregion' => 'side-pre',
        'options' => ['nonavbar' => true],
    ],
    // Server administration scripts.
    'admin' => [
        'file' => 'nice_dashboard.php',
        'regions' => ['fullwidth-top', 'fullwidth-bottom', 'above-content', 'below-content', 'side-pre'],
        'defaultregion' => 'side-pre',
    ],
    // My courses page.
    'mycourses' => [
        'file' => 'columns2.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
        'options' => ['nonavbar' => true],
    ],
    // Layout for the user's personal dashboard page.
    'mydashboard' => [
        'file' => 'nice_my.php',
        'regions' => ['fullwidth-top', 'fullwidth-bottom', 'above-content', 'below-content', 'side-pre'],
        'defaultregion' => 'side-pre',
        'options' => [
            'nonavbar' => true,
            'langmenu' => true,
            'nocontextheader' => true,
        ],
    ],
    // My public page.
    'mypublic' => [
        'file' => 'columns2.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
    // Layout used for the login page.
    'login' => [
        'file' => 'login.php',
        'regions' => ['fullwidth-top', 'fullwidth-bottom', 'above-content', 'below-content', 'left', 'side-pre'],
        'defaultregion' => 'below-content',
        'options' => ['langmenu' => true],
    ],
    // Pop-up pages without navigation, blocks, or headers.
    'popup' => [
        'file' => 'columns1.php',
        'regions' => [],
        'options' => [
            'nofooter' => true,
            'nonavbar' => true,
            'activityheader' => [
                'notitle' => true,
                'nocompletion' => true,
                'nodescription' => true,
            ],
        ],
    ],
    // No blocks and minimal footer - used for legacy frame layouts only.
    'frametop' => [
        'file' => 'columns1.php',
        'regions' => [],
        'options' => [
            'nofooter' => true,
            'nocoursefooter' => true,
            'activityheader' => [
                'nocompletion' => true,
            ],
        ],
    ],
    // Embedded pages, like iframes or object embeds.
    'embedded' => [
        'file' => 'embedded.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
    // Used during upgrade, install, or maintenance mode.
    'maintenance' => [
        'file' => 'maintenance.php',
        'regions' => [],
    ],
    // Should display content and basic headers only.
    'print' => [
        'file' => 'columns1.php',
        'regions' => [],
        'options' => [
            'nofooter' => true,
            'nonavbar' => false,
            'noactivityheader' => true,
        ],
    ],
    // The pagelayout used when a redirection is occurring.
    'redirect' => [
        'file' => 'embedded.php',
        'regions' => [],
    ],
    // The pagelayout used for reports.
    'report' => [
        'file' => 'columns2.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
    // The pagelayout used for safebrowser and securewindow.
    'secure' => [
        'file' => 'secure.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
];

$THEME->scss = function($theme) {
    return theme_boost_get_main_scss_content($theme);
};

// Enable the edit switch (gear icon) in page headers for editing course content or blocks.
$THEME->haseditswitch = true;

// Enable the new course index feature.
$THEME->usescourseindex = true;

// Define a function to post-process compiled CSS.
$THEME->csspostprocess = 'theme_nice_process_css';

// Specify the path to the theme's favicon icon.
$THEME->favicon = $CFG->wwwroot . '/theme/nice/pix/favicon.ico';

// List of JavaScript files to be included at the end of the page for better loading performance.
$THEME->javascripts_footer = [
    'jquery',
    'dashboard',
    'footer',
    'owl.carousel.min',
    'main',
];
