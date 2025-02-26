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
 * Students notifications
 * @package   local_resourcenotif
 * @copyright 2012-2021 Silecs {@link http://www.silecs.info/societe}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_resourcenotif;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/user/lib.php');
require_once($CFG->libdir . '/externallib.php');

/**
 * Class notifstudents
 */
class notifstudents {
    /**
     * @var int Course id
     */
    public $courseid;
    /**
     * @var \stdClass coursemodule description
     */
    public $cm;

    /**
     * notifstudents constructor.
     * @param mixed $courseid
     * @param mixed $cm
     */
    public function __construct($courseid, $cm) {
        $this->courseid = $courseid;
        $this->cm = $cm;
    }

    /**
     * Renvoie les utilisateurs ayant le rôle 'rolename'
     * dans le cours courant
     *
     * @param string $rolename shortname du rôle
     * @return array [users...]
     */
    public function get_users_from_course($rolename) {
        global $DB;
        $coursecontext = \context_course::instance($this->courseid);
        $roletarget = $DB->get_record('role', ['shortname' => $rolename]);
        $targetcontext = get_users_from_role_on_context($roletarget, $coursecontext);

        if (count($targetcontext) == 0) {
            return $targetcontext;
        }
        $ids = [];
        foreach ($targetcontext as $sc) {
            $ids[] = (int) $sc->userid;
        }
        $sql = "SELECT * FROM {user} WHERE id IN (" . join(",", $ids) . ")";
        $students = $DB->get_records_sql($sql);

        $modinfo = \get_fast_modinfo($this->courseid)->get_cm($this->cm->id);
        $availableinfo = new \core_availability\info_module($modinfo);
        $notifiablestudents = $availableinfo->filter_user_list($students);
        return $notifiablestudents;
    }

    /**
     * Renvoie tous les groupes d'un cours
     *
     * @return array groups
     */
    public function get_all_groups() {
        $groups = [];
        $allgroups = groups_get_all_groups($this->courseid);
        if (count($allgroups)) {
            foreach ($allgroups as $id => $group) {
                $groups[$id] = $group->name;
            }
        }
        return $groups;
    }

    /**
     * Retourne tous les groupements d'un cours
     *
     * @return array $groupings
     */
    public function get_all_groupings() {
        $groupings = [];
        $allgroupings = groups_get_all_groupings($this->courseid);
        if (count($allgroupings)) {
            foreach ($allgroupings as $id => $grouping) {
                $groupings[$id] = $grouping->name;
            }
        }
        return $groupings;
    }

    /**
     * Renvoie le tableau des étudiants inscrit à un cours
     *
     * @return array [id => student_fullname]
     **/
    public function get_list_students() {
        $liststudent = [];
        $students = $this->get_users_from_course('student');
        if (!empty($students)) {
            foreach ($students as $id => $student) {
                $liststudent[$id] = user_get_user_details($student)['fullname'];
            }
        }
        return $liststudent;
    }

    /**
     * Renvoie le tableau des utilisateurs appartenant aux groupes $groups
     * ou aux groupements $groupings
     *
     * @param array $groups
     * @param array $groupings
     * @return array users
     */
    public static function get_users_recipients($groups, $groupings) {
        $users = [];
        if (count($groups)) {
            foreach ($groups as $groupid) {
                $userg = groups_get_members($groupid);
                foreach ($userg as $id => $u) {
                    if (!isset($users[$id])) {
                        $users[$id] = $u;
                    }
                }
            }
        }
        if (count($groupings)) {
            foreach ($groupings as $groupingid) {
                $usergp = groups_get_grouping_members($groupingid);
                foreach ($usergp as $id => $u) {
                    if (!isset($users[$id])) {
                        $users[$id] = $u;
                    }
                }
            }
        }
        return $users;
    }
}
