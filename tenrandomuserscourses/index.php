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
require_once('../../config.php');
require_login();

if (!is_siteadmin()) {
    throw new moodle_exception('You do not have permission to view this report.');
}

require_once($CFG->dirroot. '/report/tenrandomuserscourses/lib.php');
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/report/tenrandomuserscourses/index.php'));
$PAGE->set_pagelayout('standard');
$PAGE->set_title($SITE->fullname);
$PAGE->set_heading(get_string('pluginname', 'report_tenrandomuserscourses'));
echo $OUTPUT->header();

$userfields = \core_user\fields::for_name()->with_identity($context);
$userfieldssql = $userfields->get_sql('u');

$sql = "SELECT * FROM (SELECT ue.userid {$userfieldssql->selects},
        GROUP_CONCAT( DISTINCT c.fullname ORDER BY e.courseid SEPARATOR '<br/>') AS coursenames
        FROM {user_enrolments} ue
        LEFT JOIN {enrol} e ON e.id = ue.enrolid
        LEFT JOIN {user} u ON u.id = ue.userid
        LEFT JOIN {course} c ON c.id = e.courseid
        GROUP BY ue.userid
        ORDER BY RAND()
        LIMIT 10) t1
        ORDER BY lastname";

$enrollments = $DB->get_records_sql($sql);

echo '<table class="table">
<thead>
  <tr>
    <th scope="col">Name</th>
    <th scope="col">Course</th>
  </tr>
</thead>
<tbody>';

foreach ($enrollments as $enrollment) {
    $firstname = $enrollment->firstname;
    $lastname = $enrollment->lastname;
    $coursenames = $enrollment->coursenames;

    echo '<tr>';
    echo "<td class=\"align-middle\" >$firstname $lastname</td>";
    echo "<td>$coursenames</td>";
    echo '</tr>';
}

echo '</tbody>
</table>';

echo $OUTPUT->footer();
