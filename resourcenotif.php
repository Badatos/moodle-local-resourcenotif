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
 * Resource Notification main page
 * @package   local_resourcenotif
 * @copyright 2012-2021 Silecs {@link http://www.silecs.info/societe}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
use local_resourcenotif\notification;
use local_resourcenotif\notifstudents;
use local_resourcenotif\resourcenotif_form;

require_once("../../config.php");

$id = required_param('id', PARAM_INT);

if (! $cm = get_coursemodule_from_id('', $id)) {
    throw new \moodle_exception('invalidcoursemodule');
}

if (! $moduletype = $DB->get_field('modules', 'name', ['id' => $cm->module], MUST_EXIST)) {
    throw new \moodle_exception('invalidmodule');
}

if (! $course = $DB->get_record('course', ['id' => $cm->course])) {
    throw new \moodle_exception('coursemisconf');
}

if (! $module = $DB->get_record($moduletype, ['id' => $cm->instance])) {
    throw new \moodle_exception('invalidcoursemodule');
}

require_login($course, false, $cm);
$modcontext = context_module::instance($cm->id);
require_capability('moodle/course:manageactivities', $modcontext);

$url = new moodle_url('/local/resourcenotif/resourcenotif.php', ['id' => $id]);
$PAGE->set_url($url);

$msgresult = '';

$urlcourse = $CFG->wwwroot . '/course/view.php?id=' . $course->id;

$notificationprocess = new notification($course, $cm, $moduletype);
$notificationprocess->set_message_body_info();

$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_title(format_string($module->name));
$PAGE->requires->css(new moodle_url('/local/resourcenotif/resourcenotif.css'));

$notifrecipients = new notifstudents($course->id, $cm);
$notifiablestudents = $notifrecipients->get_users_from_course('student');

$formcustomdata = $notificationprocess->get_form_customdata($notifiablestudents);
$mform = new resourcenotif_form(null, $formcustomdata);

$newformdata = ['id' => $id, 'mod' => $moduletype, 'courseid' => $course->id];
$mform->set_data($newformdata);
$formdata = $mform->get_data();

if ($mform->is_cancelled()) {
    redirect($urlcourse);
}

if ($formdata) {
    $notificationprocess->set_notification_message($formdata->complement);

    switch ($formdata->send) {
        case 'all':
            if ($notifiablestudents) {
                $msgresult = $notificationprocess->send_notifications($notifiablestudents);
            }
            break;
        case 'selection':
            $groups = [];
            if (isset($formdata->groups) && count($formdata->groups)) {
                $groups = $formdata->groups;
            }
            $groupings = [];
            if (isset($formdata->groupings) && count($formdata->groupings)) {
                $groupings = $formdata->groupings;
            }
            $grpnotifiedstudents = notifstudents::get_users_recipients($groups, $groupings);
            if (count($grpnotifiedstudents)) {
                $msgresult = $notificationprocess->send_notifications($grpnotifiedstudents);
            }
            break;
        case 'selectionstudents':
            $listidstudents = $formdata->students;
            if (count($listidstudents)) {
                $notifieds = [];
                foreach ($listidstudents as $id) {
                    $notifieds[$id] = $notifiablestudents[$id];
                }
                $msgresult = $notificationprocess->send_notifications($notifieds);
            }
            break;
    }
}

echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('sendnotification', 'local_resourcenotif'));

if ($msgresult != '') {
    echo $OUTPUT->box_start('info');
    echo $msgresult;
    echo html_writer::tag('p', html_writer::link($urlcourse, get_string('returncourse', 'local_resourcenotif')));
    echo $OUTPUT->box_end();
} else {
    $mform->display();
}

echo $OUTPUT->footer();
