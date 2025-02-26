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
 * Resource Notification version information
 * @package   local_resourcenotif
 * @copyright 2012-2021 Silecs {@link http://www.silecs.info/societe}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version   = 2025022600;
$plugin->release   = "3.3.0";
$plugin->requires  = 2023100900; // 4.3
$plugin->component = 'local_resourcenotif';

// Moodle versions that are outside of this range will produce a message notifying at install time, but will allow for installation.
$plugin->supported = [403, 405];     // Moodle 4.3.x to 4.5.x are supported.

$plugin->maturity = MATURITY_STABLE;
