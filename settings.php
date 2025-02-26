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
 * Resource Notification admin settings and defaults
 * @package   local_resourcenotif
 * @copyright 2012-2021 Silecs {@link http://www.silecs.info/societe}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if (has_capability('moodle/site:config', context_system::instance())) {
    $settings = new admin_settingpage('local_resourcenotif', get_string('pluginname', 'local_resourcenotif'));
    $ADMIN->add('localplugins', $settings);

    $defaultmsg = '[[sender]] would like to draw your attention to the activity/resource '
        . '[[linkactivity]] available within the course [[linkcourse]].';
    $description = get_string('descriptionmsg', 'local_resourcenotif');
    $message = new admin_setting_configtextarea(
        'message_body',
        get_string('body', 'local_resourcenotif'),
        $description,
        $defaultmsg
    );
    $message->plugin = 'local_resourcenotif';
    $settings->add($message);
}
