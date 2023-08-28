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
 * Plugin version info
 *
 * @package    report_competency
 * @copyright  2023 Stanisław Bielski <stanislaw.bielski@protonmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
/**
 * Insert a link to index.php on the site front page navigation menu.
 *
 * @param navigation_node $frontpage Node representing the front page in the navigation tree.
 */
function report_tenrandomuserscourses_extend_navigation_frontpage(navigation_node $frontpage) {
    require_login();

    if (is_siteadmin()) {
        $frontpage->add(
            get_string('pluginname', 'report_tenrandomuserscourses'),
            new moodle_url('/report/tenrandomuserscourses/index.php'),
            navigation_node::TYPE_CUSTOM,
        );
    }
}
