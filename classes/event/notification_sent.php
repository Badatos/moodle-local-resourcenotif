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
 * Notification sent event.
 *
 * @package    local_resourcenotif
 * @copyright  2017 Mario Wehr <m.wehr@fh-kaernten.at>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_resourcenotif\event;

/**
 * Summary of notification_send
 */
class notification_send extends \core\event\base {
    /**
     * Summary of init
     * @return void
     */
    protected function init() {
        $this->data['crud'] = 'w'; // One of c(reate), r(ead), u(pdate), d(elete).
        $this->data['edulevel'] = self::LEVEL_OTHER;
    }

    /**
     * Summary of get_name
     */
    public static function get_name() {
        return get_string('event', 'local_resourcenotif');
    }

    /**
     * Summary of get_description
     */
    public function get_description() {
        return $this->other['message'];
    }
}
