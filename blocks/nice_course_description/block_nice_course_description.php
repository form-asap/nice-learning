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
 * Class definition for the block_nice_course_description.
 *
 * @package    block_nice_course_description
 * @copyright  2025 Nice Learning <support@docs.nicelearning.org>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_nice_course_description extends block_base {

    /**
     * Initialization method.
     *
     * @return void
     */
    public function init(): void {
        $this->title = get_string('pluginname', 'block_nice_course_description');
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
            $this->config->main_title = 'Course overview';

            $this->config->main_description = [
                'text' => $this->get_default_description(),
                'format' => FORMAT_HTML
            ];
        }
    }

    /**
     * Generate the block's content.
     *
     * @return stdClass
     */
    public function get_content(): stdClass {
        global $CFG;

        require_once($CFG->libdir . '/filelib.php');

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();

        if (empty($this->config->main_description)) {
            $this->config->main_description = [
                'text' => $this->get_default_description(),
                'format' => FORMAT_HTML
            ];
        }

        $this->content->main_title = $this->config->main_title ?? '';
        $this->content->main_description = $this->config->main_description ?? '';
        $this->content->title_placement = $this->config->title_placement ?? 0;

        $formattedtitle = format_text(
            $this->content->main_title,
            FORMAT_HTML,
            ['filter' => true]
        );

        $maindescriptioneditor = $this->config->main_description;
        $maindescription = $maindescriptioneditor['text'];

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
            <div class="nice-course-description-container">
                <div class="container">
                    <div class="nice-course-description-title-container">
                        <h5 class="d-inline ' . $alignmentclass . '">'
                            . $formattedtitle . '
                        </h5>
                    </div><!-- End nice-course-description-title-container -->
                    <div class="nice-course-description mt-3">'
                        . $maindescription . '
                    </div>
                </div>
            </div><!-- End nice-course-description-container -->';

        return $this->content;
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

    /**
     * Returns the default HTML description text for the block.
     *
     * @return string
     */
    private function get_default_description(): string {
        return '
            <p>
                Online learning has revolutionized the educational landscape, democratizing access
                to knowledge in an unprecedented manner. In an age where information is at our fingertips,
                e-learning platforms like Moodle empower individuals worldwide to embark on their
                educational journeys from the comfort of their homes. It provides flexibility,
                allowing learners to pace themselves according to their schedules and commitments.
                Beyond the convenience, online learning fosters a culture of self-discipline,
                as students take greater ownership of their progress.
            </p>
            <div class="nice-course-description-learning-outcomes-container">
                <h5 class="d-inline text-start">
                    Learning outcomes
                </h5>
                <ol class="nice-ordered-list nice-ordered-list-rounded mt-3">
                    <li>Students will be proficient in data analysis using Python.</li>
                    <li>After finishing this course, learners will understand the foundations of machine learning algorithms.</li>
                    <li>Students will grasp the essentials of effective communication, from crafting compelling narratives.</li>
                    <li>By the end of the course, participants will acquire practical skills in web development.</li>
                    <li>This course aims to equip students with tools to think critically.</li>
                </ol><!-- End ol -->
            </div>';
    }
}
