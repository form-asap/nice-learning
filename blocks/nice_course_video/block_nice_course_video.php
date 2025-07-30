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
 * Class definition for the block_nice_course_video.
 *
 * @package     block_nice_course_video
 * @copyright   2025 Nice Learning <support@docs.nicelearning.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_nice_course_video extends block_base {

    /**
     * Initialization method.
     *
     * @return void
     */
    public function init(): void {
        $this->title = get_string('pluginname', 'block_nice_course_video');
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

        if (empty($this->config)) {
            $this->config = new \stdClass();
            $this->config->main_title = 'Course overview';
            $this->config->main_description = [
                'text' => '<p>
                            Online learning has revolutionized the educational landscape, democratizing access to knowledge
                            in an unprecedented manner. In an age where information is at our fingertips, e-learning
                            platforms like Moodle empower individuals from all corners of the globe to embark
                            on their educational journeys from the comfort of their homes.
                            It provides flexibility, allowing learners to pace themselves according
                            to their schedules and commitments. Beyond the convenience, online
                            learning fosters a culture of self-discipline, as students take
                            greater ownership of their progress.
                           </p>',
                'format' => FORMAT_HTML
            ];

            $this->config->youtube_url = 'https://www.youtube.com/watch?v=PkZNo7MFNFg';
        }
    }

    /**
     * Generate the block's content.
     *
     * @return stdClass
     */
    public function get_content(): ?stdClass {
        global $CFG, $COURSE, $DB, $USER;

        require_once($CFG->libdir . '/filelib.php');

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();

        if (empty($this->config->main_description)) {
            $this->config->main_description = [
                'text' => '<p>
                            Online learning has revolutionized the educational landscape, democratizing access to knowledge
                            in an unprecedented manner. In an age where information is at our fingertips
                            e-learning platforms like Moodle empower individuals from all corners of the globe to
                            embark on their educational journeys from the comfort of their homes. 
                            It provides flexibility, allowing learners to pace themselves according
                            to their schedules and commitments.
                            Beyond the convenience, online learning fosters a culture of self-discipline
                            as students take greater ownership of their progress.
                           </p>',
                'format' => FORMAT_HTML
            ];
        }

        $this->content->main_title = !empty($this->config->main_title)
            ? $this->config->main_title
            : '';

        $this->content->main_description = !empty($this->config->main_description)
            ? $this->config->main_description
            : '';

        $this->content->title_placement = !empty($this->config->title_placement)
            ? $this->config->title_placement
            : 0;

        $formattedtitle = format_text(
            $this->content->main_title,
            FORMAT_HTML,
            ['filter' => true]
        );

        $maindescriptioneditor = $this->config->main_description;
        $maindescription = $maindescriptioneditor['text'] ?? '';

        $alignmentclass = 'text-start';
        switch ($this->content->title_placement) {
            case 1:
                $alignmentclass = 'text-end';
                break;
            case 2:
                $alignmentclass = 'text-center';
                break;
        }

        $youtubeurl = $this->config->youtube_url ?? '';
        $videoid = '';

        if (preg_match('/youtube\.com.*v=([^&]+)/', $youtubeurl, $matches)) {
            $videoid = $matches[1];
        } else if (preg_match('/youtu\.be\/([^&]+)/', $youtubeurl, $matches)) {
            $videoid = $matches[1];
        }

        $embedurl = "https://www.youtube.com/embed/" . $videoid;
        $iframe = '<iframe title="Youtube video" src="' .
            htmlspecialchars($embedurl) .
            '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';

        $nicecoursehandler = new theme_nice_course_handler();
        $nicecourse = $nicecoursehandler->theme_nice_get_course_details($COURSE->id);

        $context = context_course::instance($COURSE->id);
        $userfields = "u.id, u.picture, u.firstname, u.lastname, u.firstnamephonetic,
                       u.lastnamephonetic, u.middlename, u.alternatename, u.email, u.imagealt";
        $sql = "SELECT $userfields
                  FROM {user} u
                 INNER JOIN {role_assignments} ra ON ra.userid = u.id
                 WHERE ra.contextid = :contextid AND ra.roleid = :roleid";

        $roleid = 4;
        $params = ['contextid' => $context->id, 'roleid' => $roleid];
        $teachers = $DB->get_records_sql($sql, $params);

        $studentstext = get_string('students', 'block_nice_course_video');
        $teachertext = get_string('teacher', 'block_nice_course_video');
        $categorytext = get_string('category', 'block_nice_course_video');

        $this->config->show_teacher = $this->config->show_teacher ?? 1;
        $this->config->show_category = $this->config->show_category ?? 1;
        $this->config->show_students = $this->config->show_students ?? 1;

        $this->content->text = '
            <div class="nice-course-video-container">
                <div class="container">
                    <div class="nice-course-video-title-container">
                        <h5 class="d-inline ' . $alignmentclass . '">'
                        . $formattedtitle .
                        '</h5>
                    </div>
                    <div class="nice-course-video-container mt-3">
                        <div class="ratio ratio-16x9 rounded">' .
                            $iframe .
                        '</div>
                    </div>
                    <div class="nice-course-video-description mt-3">' .
                        $maindescription .
                    '</div>';

        $classname = "nice-course-content-container d-flex align-items-center";

        if (!$this->config->show_teacher &&
            !$this->config->show_category &&
            !$this->config->show_students) {
            $classname .= " no-content";
        }

        $this->content->text .= "\n<div class='$classname'>\n";

        if (!empty($teachers) && $this->config->show_teacher) {
            $this->content->text .= '
                <div class="nice-course-video-teacher-container">
                    <span class="nice-course-video-content-title">'
                        . $teachertext . '</span>';
            foreach ($teachers as $teacher) {
                $profileurl = new moodle_url('/user/view.php', [
                    'id' => $teacher->id,
                    'course' => $COURSE->id
                ]);
                $userpicture = new user_picture($teacher);
                $userpicture->size = 1;
                $pictureurl = $userpicture->get_url($this->page)->out(false);
                $fullname = $teacher->firstname . " " . $teacher->lastname;

                $this->content->text .= "
                    <a href='$profileurl'>
                        <div class='position-relative'>
                            <img src='$pictureurl' />
                            <div class='nice-course-video-teacher-image-overlay'></div>
                        </div>
                        <div>
                            <span class='fw-bold'>$fullname</span>
                        </div>
                    </a>";
            }
            $this->content->text .= '</div>';
        }

        if ($this->config->show_category) {
            $this->content->text .= '
                <div class="nice-course-video-category-container">
                    <span class="nice-course-video-content-title">'
                        . $categorytext . '</span>
                    <a href="' . $nicecourse->category_url . '">
                        <div class="nice-course-video-category-icon">
                            <i class="fa-solid fa-book-open"></i>
                        </div>
                        <span class="fw-bold">'
                            . $nicecourse->category_name . '</span>
                    </a>
                </div>';
        }

        if ($this->config->show_students) {
            $this->content->text .= '
                <div class="nice-course-video-enrollment-container">
                    <span class="nice-course-video-content-title">'
                        . $studentstext . '</span>
                    <a href="' . $CFG->wwwroot .
                        '/user/index.php?id=' . $nicecourse->course_id . '">
                        <div class="nice-course-video-enrollment-icon">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <span class="fw-bold">'
                            . $nicecourse->enrolments . '</span>
                    </a>
                </div>';
        }

        $this->content->text .= '
                </div>
            </div>
        </div>';

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
