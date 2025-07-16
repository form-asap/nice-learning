<?php
// This file is part of Moodle - http://smoodle.org/
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
 * Blog renderer for the Nice Learning theme.
 *
 * This renderer overrides core_blog_renderer to customize
 * the display of blog entries for the theme.
 *
 * @package    theme_nice
 * @copyright  2025 Nice Learning <support@docs.nicelearning.org>
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @see core_blog_renderer
 */


defined('MOODLE_INTERNAL') || die();
global $CFG;

require_once($CFG->dirroot . "/blog/renderer.php");

/**
 * Blog renderer
 */
class theme_nice_core_blog_renderer  extends core_blog_renderer {

    /**
     * Renders a blog entry
     * This function takes a blog entry object and returns it as HTML.
     *
     * @param blog_entry $entry The blog entry object.
     * @return string The HTML for rendering the blog entry.
     */
    public function render_blog_entry(blog_entry $entry) {

        global $CFG;
        // Determine the protocol and current URL.
        $currentlink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
            . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        // Get the global system context.
        $systemcontext = context_system::instance();

        $stringedit = get_string('edit');
        $stringdelete = get_string('delete');

        if (strpos($currentlink, '/index.php?entryid') == false ):

                // Header.
                $mainclass = 'single-blog-moodle col-xl-3 col-lg-4 col-md-6 col-sm-6 ';
            if ($entry->renderable->unassociatedentry) {
                $mainclass .= 'draft';
            } else {
                $mainclass .= $entry->publishstate;
            }

                $innercontent = '';
                $niceoutput = '';
                $url = new moodle_url('/blog/index.php', ['entryid' => $entry->id]);

                $niceoutput .= '<div class="' . $mainclass . '" id="blog-' . $entry->id . '">';
                $niceoutput .= '<a href="' . $url . '">';

                $innercontent .= $this->output->container_start('single-blog-post');

                $innercontent .= $this->output->container_start('image');
            if ($entry->renderable->attachments) {
                foreach ($entry->renderable->attachments as $attachment) {
                    $innercontent .= $this->render($attachment, false);
                }
            }

                $innercontent .= $this->output->container_end(); // End image.

                $innercontent .= $this->output->container_start('single-blog-wave-container position-relative');

                $svg = <<<SVG
                <svg class="single-blog-card-wave" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                    viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
                    <defs>
                        <path id="gentle-wave"
                            d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z">
                        </path>
                    </defs>
                    <g class="parallax">
                        <use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(255,255,255,0.7)"></use>
                        <use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.5)"></use>
                        <use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.3)"></use>
                        <use xlink:href="#gentle-wave" x="48" y="7" fill="#fff"></use>
                    </g>
                </svg>
                SVG;

                $innercontent .= $this->output->container(
                    $svg,
                    'single-blog-wave-dynamic'
                );

                $innercontent .= $this->output->container_end();

                $innercontent .= $this->output->container_start('content');
                $by = new stdClass();
                $fullname = fullname($entry->renderable->user, has_capability('moodle/site:viewfullnames', $systemcontext));
                $by->name = $fullname;

                $shortsummary = strip_tags($entry->summary);
                $wordsarray = str_word_count($shortsummary, 1);

            if (count($wordsarray) > 14) {
                $shortsummary = implode(' ', array_slice($wordsarray, 0, 14)) . ' ...';
            } else {
                $shortsummary = implode(' ', $wordsarray);
            }

                    $innercontent .= $this->output->container_start('single-blog-card-date-container');

                    $innercontent .= $this->output->container(
                        '<span>
                            <i class="fa-solid fa-calendar-days"></i>
                        </span>
                        <span>' . date('j F Y', $entry->created) . '</span>',
                        'single-blog-card-date'
                    );

                    $innercontent .= $this->output->container_end();
                                        $innercontent .= $this->output->container_start('single-blog-card-user-container');

                    $innercontent .= $this->output->container(
                        '
                        <span>
                            <i class="fa-regular fa-circle-user"></i>
                        </span>

                        <span>' . $by->name . '</span>
                        ',
                        'single-blog-card-user'
                    ); // Add content.

                    $innercontent .= $this->output->container_end();
                                        $innercontent .= $this->output->container_start('single-blog-card-title-container');

                    $subject = format_string($entry->subject);

                    $innercontent .= $this->output->container(<<<HTML
                        <h5>{$subject}</h5>
                    HTML
                    , 'single-blog-card-title');

                    $innercontent .= $this->output->container_end();

                    $innercontent .= $this->output->container_start('single-blog-card-description-container');

                    $innercontent .= $this->output->container(
                        '<p>' . format_string($shortsummary) . '</p>',
                        'single-blog-card-description'
                    ); // Add content.

                    $innercontent .= $this->output->container_end();

                    $innercontent .= $this->output->container_start('tag-list');
                    $innercontent .= $this->output->tag_list(core_tag_tag::get_item_tags('core', 'post', $entry->id));
                    $innercontent .= $this->output->container_end(); // End tag-list.

                $innercontent .= $this->output->container_end(); // End content.
                $innercontent .= $this->output->container_end(); // End single-blog-post.

                $niceoutput .= $innercontent;

                $niceoutput .= '</a>';  // End of anchor tag.

                $niceoutput .= '</div>';  // End of main div.

        else: // Blog details
            // Header.
            $mainclass = 'col-lg-12 blog-details-area blog-details-desc ';
            if ($entry->renderable->unassociatedentry) {
                $mainclass .= 'draft';
            } else {
                $mainclass .= $entry->publishstate;
            }
            $niceoutput = $this->output->container_start($mainclass, 'b' . $entry->id);
                $niceoutput .= $this->output->container_start('single-blog-post', '' . $entry->id);

                    $niceoutput .= $this->output->container_start('single-blog-name-container', '' . $entry->id);

                        $niceoutput .= $this->output->container($entry->subject, 'single-blog-name'); // Add content.

                    $niceoutput .= $this->output->container_end(); // Close sub-container.

                    $niceoutput .= $this->output->container_start('article-image');
                        // Attachments.
                        $attachmentsoutputs = [];
            if ($entry->renderable->attachments) {
                foreach ($entry->renderable->attachments as $attachment) {
                    $niceoutput .= $this->render($attachment, false);
                }
            }
                    $niceoutput .= $this->output->container_end();
                    $niceoutput .= $this->output->container_start('content');
                    $niceoutput .= $this->output->container_start('post-info');

                    // Post by.
                    $by = new stdClass();
                    $viewfullnames = has_capability('moodle/site:viewfullnames', $systemcontext);
                    $fullname = fullname($entry->renderable->user, $viewfullnames);
                    $userurlparams = ['id' => $entry->renderable->user->id, 'course' => $this->page->course->id];
                    $by->name = html_writer::link(new moodle_url('/user/view.php', $userurlparams), $fullname);

                    $by->date = userdate($entry->created);

                        $niceoutput .= $this->output->container(get_string('bynameondate', 'forum', $by), 'author');
                            // Adding external blog link.
            if (!empty($entry->renderable->externalblogtext)) {
                $niceoutput .= $this->output->container($entry->renderable->externalblogtext, 'externalblog');
            }
                        $niceoutput .= $this->output->container_end();
                        // Commands.
                        $niceoutput .= $this->output->container_start('commands');
            if ($entry->renderable->usercanedit) {

                // External blog entries should not be edited.
                if (empty($entry->uniquehash)) {
                    $editurl = new moodle_url(
                        '/blog/edit.php',
                        [
                            'action' => 'edit',
                            'entryid' => $entry->id,
                        ]
                    );

                    $niceoutput .= html_writer::link(
                        $editurl,
                        $stringedit,
                        ['class' => 'btn btn-secondary']
                    );
                }

                $niceoutput .= '<a class="btn btn-secondary" href="' .
                new moodle_url('/blog/edit.php', ['action' => 'delete', 'entryid' => $entry->id]) .
                '">' . $stringdelete . '</a>';
            }
                        $niceoutput .= $this->output->container_end();
                    $niceoutput .= $this->output->container_end();
                $niceoutput .= $this->output->container_end();

                $niceoutput .= $this->output->container_start('article-content');
                // Body.
                $niceoutput .= format_text($entry->summary, $entry->summaryformat, ['overflowdiv' => true]);
                // Add associations.
            if (!empty($CFG->useblogassociations) && !empty($entry->renderable->blogassociations)) {

                // First find and show the associated course.
                $assocstr = '';
                $coursesarray = [];
                foreach ($entry->renderable->blogassociations as $assocrec) {
                    if ($assocrec->contextlevel == CONTEXT_COURSE) {
                        $coursesarray[] = $this->output->action_icon($assocrec->url, $assocrec->icon, null, [], true);
                    }
                }
                if (!empty($coursesarray)) {
                    $assocstr .= get_string('associated', 'blog', get_string('course')) . ': ' . implode(', ', $coursesarray);
                }

                // Now show mod association.
                $modulesarray = [];
                foreach ($entry->renderable->blogassociations as $assocrec) {
                    if ($assocrec->contextlevel == CONTEXT_MODULE) {
                        $str = get_string('associated', 'blog', $assocrec->type) . ': ';
                        $str .= $this->output->action_icon($assocrec->url, $assocrec->icon, null, [], true);
                        $modulesarray[] = $str;
                    }
                }

                // Adding the asociations to the output.
                $niceoutput .= $this->output->container($assocstr, 'tags');
            }
            if ($entry->renderable->unassociatedentry) {
                $niceoutput .= $this->output->container(get_string('associationunviewable', 'blog'), 'noticebox');
            }

            // Comments.
            if (!empty($entry->renderable->comment)) {

                global $DB, $CFG, $USER;

                $cmt = new stdClass();
                $cmt->context = context_user::instance($entry->userid);
                $cmt->courseid = $this->page->course->id;
                $cmt->area = 'format_blog';
                $cmt->itemid = $entry->id;
                $cmt->notoggle = true;
                $cmt->showcount = $CFG->blogshowcommentscount;
                $cmt->component = 'blog';
                $cmt->autostart = true;
                $cmt->displaycancel = false;

                $nicecomments = new comment($cmt);
                $nicecomments->set_view_permission(true);
                $nicecomments->set_fullwidth();

                $niceoutput .= $nicecomments->output(true);
            }

            $niceoutput .= $this->output->container_end();
            // Closing maincontent div.
            $niceoutput .= $this->output->container('', 'side options');
            $niceoutput .= $this->output->container_end();

        endif;
        return $niceoutput;
    }

    /**
     * Renders an entry attachment.
     * This function takes a blog entry attachment and returns it as HTML.
     *
     * @param blog_entry_attachment $attachment The blog entry attachment object.
     * @return string The HTML for rendering the blog entry attachment.
     */
    public function render_blog_entry_attachment(blog_entry_attachment $attachment) {

        $systemcontext = context_system::instance();

        // Image attachments don't get printed as links.
        $attrs = ['src' => $attachment->url, 'alt' => ''];
        $niceoutput = html_writer::empty_tag('img', $attrs);
        $class = 'attachedimages';

        // Return the image wrapped in a div container with class 'attachedimages'.
        return $this->output->container($niceoutput, $class);
    }
}
