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
 * This page handles deleting standard greetings.
 *
 * @package   local_greet
 * @copyright 2014 Tim Hunt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');

$id = required_param('id', PARAM_INT);
$confirm = optional_param('confirm', false, PARAM_BOOL);

$greeting = $DB->get_record('local_greet_greetings', array('id' => $id), '*', MUST_EXIST);

require_login();
$context = context_system::instance();
require_capability('local/greet:begreeted', $context);

$url = new moodle_url('/local/greet/delete.php', array('id' => $greeting->id));
$confirmurl = new moodle_url($url, array('confirm' => 1));
$cancelurl = new moodle_url('/local/greet/index.php', array('name' => $greeting->name));

if ($confirm) {
    require_sesskey();
    $DB->delete_records('local_greet_greetings', array('id' => $id));

    add_to_log(SITEID, 'local_greet', 'deletegreeting',
            'local/greet/index.php?name=' . urlencode($greeting->name));

    redirect($cancelurl);
}

// Prepare the page to show the confirmation form.
$title = get_string('deletegreeting', 'local_greet');

$PAGE->set_context($context);
$PAGE->set_url($url);
$PAGE->navbar->add($title);
$PAGE->set_title($title);

echo $OUTPUT->header();
echo $OUTPUT->heading($title);
echo $OUTPUT->confirm(get_string('areyousure', 'local_greet'), $confirmurl, $cancelurl);
echo $OUTPUT->footer();
