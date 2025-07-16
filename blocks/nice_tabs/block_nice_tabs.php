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

defined('MOODLE_INTERNAL') || die();

global $CFG;

/**
 * Class definition for the block_nice_tabs.
 *
 * @package     block_nice_tabs
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_nice_tabs extends block_base {

    /**
     * Initialization method.
     *
     * @return void
     */
    public function init(): void {
        $this->title = get_string('pluginname', 'block_nice_tabs');
    }

    /**
     * Specialization method to process block-specific configurations.
     *
     * @return void
     */
    public function specialization(): void {
        global $CFG;

        include($CFG->dirroot . '/theme/nice/inc/block_handler/specialization.php');

        if (empty($this->config)) {
            $this->config = new stdClass();
            $this->config->main_title = 'Tabs';

            $defaulttitles = [
                1 => "Description",
                2 => "Learning outcomes",
            ];

            for ($i = 1; $i <= 2; $i++) {
                $titlefield = "title{$i}";
                if (!isset($this->config->$titlefield)) {
                    $this->config->$titlefield = $defaulttitles[$i];
                }
            }

            $defaultdescription = [
                1 => '<p>Online learning has revolutionized the educational landscape, democratizing access to knowledge in an unprecedented manner. '
                    . 'In an age where information is at our fingertips, e-learning platforms like Moodle empower individuals from all corners of the globe '
                    . 'to embark on their educational journeys from the comfort of their homes. It provides flexibility, allowing learners to pace themselves '
                    . 'according to their schedules and commitments. Beyond the convenience, online learning fosters a culture of self-discipline, as '
                    . 'students take greater ownership of their progress.</p>',
                2 => '<div class="nice-tabs-learning-outcomes-container">'
                    . '<ol class="nice-ordered-list nice-ordered-list-rounded mt-3">'
                    . '<li>Students will be proficient in data analysis using Python. They will know how to manipulate data frames.</li>'
                    . '<li>After finishing this course, learners will understand the foundations of machine learning algorithms.</li>'
                    . '<li>Students will grasp the essentials of effective communication, from crafting compelling narratives.</li>'
                    . '<li>By the end of the course, participants will acquire practical skills in web development.</li>'
                    . '<li>This course aims to equip students with the tools to think critically. They will be able to evaluate arguments.</li>'
                    . '</ol></div>'
            ];

            for ($i = 1; $i <= 2; $i++) {
                $descriptionfield = "description{$i}";
                if (!isset($this->config->$descriptionfield)) {
                    $this->config->$descriptionfield = [
                        'text' => $defaultdescription[$i],
                        'format' => FORMAT_HTML
                    ];
                }
            }
        }
    }

    /**
     * Generate the block's content.
     *
     * @return stdClass
     */
    public function get_content(): stdClass {
        global $CFG;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();

        $this->content->main_title = !empty($this->config->main_title)
            ? $this->config->main_title : '';

        $this->content->title_placement = !empty($this->config->title_placement)
            ? $this->config->title_placement : 0;

        $formattedtitle = format_text(
            $this->content->main_title,
            FORMAT_HTML,
            ['filter' => true]
        );

        $alignmentclass = 'text-start';
        switch ($this->content->title_placement) {
            case 1:
                $alignmentclass = 'text-end';
                break;
            case 2:
                $alignmentclass = 'text-center';
                break;
        }

        $this->content->text = '
<section class="nice-tabs-container">
    <div class="nice-tabs">
        <div class="container">
            <div class="nice-tabs-title-container">
                <h5 class="' . $alignmentclass . '">' . $formattedtitle . '</h5>
            </div>
        </div>
        <div class="container">
            <div class="tabs">
                <ul class="nav nav-tabs" role="tablist">
';

        $tabcount = 0;
        for ($i = 1; $i <= 5; $i++) {
            if (!empty($this->config->{"title{$i}"})) {
                $tabcount++;
            }
        }
        $tabcount = max($tabcount, 2);

        for ($i = 1; $i <= $tabcount; $i++) {
            $title = isset($this->config->{"title{$i}"})
                ? format_text(
                    $this->config->{"title{$i}"},
                    FORMAT_HTML,
                    ['filter' => true]
                )
                : '';

            $this->content->text .= '
        <li class="nav-item">
            <a class="nav-link ' . ($i == 1 ? 'active' : '') . '" data-toggle="tab" href="#tabs-' . $i . '" role="tab">'
            . $title . '</a>
        </li>';
        }

        $this->content->text .= '
                </ul>
                <div class="tab-content">';

        for ($i = 1; $i <= $tabcount; $i++) {
            $descriptioneditor = $this->config->{"description{$i}"} ?? ['text' => ''];
            $maindescription = $descriptioneditor['text'];

            $panelclass = ($i == 1) ? 'active' : '';

            $this->content->text .= '
                    <div class="tab-pane ' . $panelclass . '" id="tabs-' . $i . '" role="tabpanel">
                        <div class="nice-tab-container">' . $maindescription . '</div>
                    </div>';
        }

        $this->content->text .= '
                </div>
            </div>
        </div>
    </div>
</section>';

        $this->content->footer = '';

        return $this->content;
    }

    /**
     * Whether this block type allows multiple instances on a page.
     *
     * @return bool
     */
    public function instance_allow_multiple(): bool {
        return true;
    }

    /**
     * Whether this block has global configuration settings.
     *
     * @return bool
     */
    public function has_config(): bool {
        return true;
    }

    /**
     * Saves the block's instance configuration.
     *
     * @param object $data The data being saved.
     * @param bool $nolongerused Previously used, but no longer has a purpose.
     * @return bool
     */
    public function instance_config_save($data, $nolongerused = false) {
        return parent::instance_config_save($data, $nolongerused);
    }

    /**
     * Specifies where this block can be added.
     *
     * @return array Allowed formats.
     */
    public function applicable_formats(): array {
        return [
            'all' => true,
            'my' => false,
            'admin' => false,
            'course-view' => true,
            'course' => true,
        ];
    }
}
