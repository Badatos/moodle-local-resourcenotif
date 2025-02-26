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
 * Resource Notification library
 * @package   local_resourcenotif
 * @copyright 2012-2021 Silecs {@link http://www.silecs.info/societe}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Summary of local_resourcenotif_extend_navigation_course
 * @return void
 */
function local_resourcenotif_extend_navigation_course() {
    global $OUTPUT, $PAGE;

    $linkitem = '<a class="dropdown-item editing_notifications menu-action cm-edit-action"'
        . 'data-action="notifications" role="menuitem" href="'
        . htmlspecialchars(
            new moodle_url(
                '/local/resourcenotif/resourcenotif.php',
                ['id' => '123XYZ321']
            )
        ) . '" title="' . htmlspecialchars(get_string("notifications")) . '">'
        . $OUTPUT->pix_icon('t/email', get_string("notifications"))
        . '<span class="menu-action-text">' . htmlspecialchars(get_string("notifications")) . '</span>'
        . '</a>';

    // Style Boost.
    $enc = json_encode($linkitem);
    $PAGE->requires->js_init_code(<<<EOJS
    var activities = document.querySelectorAll('.section-cm-edit-actions div[role="menu"]');
    if (activities) {
        for (var i = 0; i < activities.length; i++) {
            var ul = activities[i];
            var owner = ul.parentNode.parentNode.parentNode.getAttribute('data-owner');
            if (owner) {
                var id = owner.replace(/^#module-/, '');
                ul.insertAdjacentHTML('beforeend', $enc.replace('123XYZ321', id));
            }
        }
    }
EOJS
    , true);

    // Code style Clean.
    $enc = json_encode('<li role="presentation">' . $linkitem . '</li>');
    $PAGE->requires->js_init_code(<<<EOJS
    var activities = document.querySelectorAll('.section-cm-edit-actions ul[role="menu"]');
    if (activities) {
        for (var i = 0; i < activities.length; i++) {
            var ul = activities[i];
            var owner = ul.parentNode.getAttribute('data-owner');
            if (owner) {
                var id = owner.replace(/^#module-/, '');
                ul.insertAdjacentHTML('beforeend', $enc.replace('123XYZ321', id));
            }
        }
    }
EOJS
    , true);
}
