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

require_once($CFG->dirroot . '/config.php'); // Include Moodle config file if not already included.

require_login(); // Ensure the user is logged in.

require_once($CFG->libdir . '/enrollib.php'); // Include the enrolment library.

/**
 * Class definition for the block_nice_course_details.
 *
 * @package     block_nice_course_details
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_nice_course_details extends block_base {

    /**
     * Initialization method.
     *
     * @return void
     */
    public function init(): void {
        $this->title = get_string('pluginname', 'block_nice_course_details');
    }

    /**
     * Specialization method to process block-specific configurations.
     *
     * @return void
     */
    public function specialization(): void {
        global $CFG, $DB;

        // Include an external specialization handler file.
        include($CFG->dirroot . '/theme/nice/inc/block_handler/specialization.php');

        // If there's no configuration set yet, define default values.
        if (empty($this->config)) {
            $this->config = new \stdClass();

            // Set the default main title for the block.
            $this->config->main_title = 'Course details';
        }
    }

    /**
     * Check if a user is enrolled in a course.
     *
     * @param int $courseid The ID of the course.
     * @param int $userid The ID of the user.
     * @return bool True if the user is enrolled, otherwise false.
     */
    public function isuserenrolled(int $courseid, int $userid): bool {
        $context = context_course::instance($courseid);
        return is_enrolled($context, $userid);
    }

    /**
     * Generate the block's content.
     *
     * @return stdClass
     */
    public function get_content(): ?stdClass {
        global $CFG, $COURSE, $DB, $USER;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();

        $this->content->main_title = !empty($this->config->main_title) ? $this->config->main_title : '';
        $formattedtitle = format_text($this->content->main_title, FORMAT_HTML, ['filter' => true]);

        $nicecoursehandler = new theme_nice_course_handler();
        $nicecourse = $nicecoursehandler->theme_nice_get_course_details($COURSE->id);

        $startdatetime = DateTime::createFromFormat('d/m/y', $nicecourse->start_date);
        $enddatetime = DateTime::createFromFormat('d/m/y', $nicecourse->end_date);

        $formattedstartdate = $startdatetime ? $startdatetime->format('F, d Y') : '';
        $formattedenddate = $enddatetime ? $enddatetime->format('F, d Y') : '';

        $this->config->show_shortname = $this->config->show_shortname ?? 1;
        $this->config->show_categoryname = $this->config->show_categoryname ?? 1;
        $this->config->show_price = $this->config->show_price ?? 1;

        $courseid = $COURSE->id;
        $userid = $USER->id;
        $userisenrolled = $this->isuserenrolled($courseid, $userid);

        $buyandenrolltext = get_string('buyandenroll', 'block_nice_course_details');
        $pricetext = get_string('courseprice', 'block_nice_course_details');
        $courseshorttext = get_string('courseshortname', 'block_nice_course_details');
        $categorytext = get_string('categoryname', 'block_nice_course_details');
        $startdatetext = get_string('coursestartdate', 'block_nice_course_details');
        $enddatetext = get_string('courseenddate', 'block_nice_course_details');

        $showpricecontent = '';
        if ($this->config->show_price && !empty($nicecourse->course_price)) {
            $showpricecontent = '
              <div class="nice-course-details-price-container">
                  <span class="nice-course-details-price-title">' . $pricetext . '</span>
                  <span class="nice-course-details-price">$' . $nicecourse->course_price . '</span>
              </div>';
        }

        $shownamecontent = '';
        if ($this->config->show_shortname) {
            $shownamecontent = '
              <div class="nice-course-details-content">
                <div>
                  <span>' . $courseshorttext . '</span>
                </div>
                <span class="nice-course-details-content-wrap">' . $nicecourse->short_name . '</span>
              </div>';
        }

        $showcategorycontent = '';
        if ($this->config->show_categoryname) {
            $showcategorycontent = '
              <div class="nice-course-details-content">
                  <div>
                      <span>' . $categorytext . '</span>
                  </div>
                  <a href="' . $nicecourse->category_url . '">
                      <span class="nice-course-details-content-wrap">' . $nicecourse->category_name . '</span>
                  </a>
              </div>';
        }

        $showenrollbuttoncontent = '';
        if (!$userisenrolled) {
            $showenrollbuttoncontent = '
                <div class="nice-course-details-button-container text-center">
                    <a class="btn btn-primary" href="' . $nicecourse->enrolmentLink . '">
                        ' . $buyandenrolltext . '
                    </a>
                </div>';
        }

        $this->content->text = '
        <section class="nice-course-details-container">
            <div class="nice-course-details">
                <div class="container">
                    <div class="nice-course-details-title-container">
                        <h5>' . $formattedtitle . '</h5>
                    </div>
                    <div class="nice-course-details-image-container">
                        <img class="img-whp" src="' . $nicecourse->image_url . '" alt="' . $nicecourse->full_name . '" />
                        ' . $showpricecontent . '
                    </div>
                    <div class="nice-course-details-content-container">
                        ' . $shownamecontent . '
                        ' . $showcategorycontent . '
                        <div class="nice-course-details-content">
                            <div>' . $startdatetext . '</div>
                            <span class="nice-course-details-content-wrap">' . $formattedstartdate . '</span>
                        </div>
                        <div class="nice-course-details-content">
                            <div>' . $enddatetext . '</div>
                            <span class="nice-course-details-content-wrap">' . $formattedenddate . '</span>
                        </div>
                    </div>
                    ' . $showenrollbuttoncontent . '
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
            'all' => false,
            'my' => false,
            'admin' => false,
            'course-view' => true,
            'course' => false,
        ];
    }
}
