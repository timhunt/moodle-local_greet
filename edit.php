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
 * Script for editing a standard greeting.
 *
 * @package   local_greet
 * @copyright 2014 Tim Hunt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/edit_form.php');

$id = optional_param('id', null, PARAM_INT);

require_login();
$context = context_system::instance();
require_capability('local/greet:begreeted', $context);

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/greet/edit.php'),
        array('id' => $id));
$PAGE->set_title(get_string('editgreeting', 'local_greet'));

$mform = new local_greet_edit_form();

if ($id) {
    $mform->set_data($DB->get_record('local_greet_greetings',
            array('id' => $id), '*', MUST_EXIST));
}

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/greet/index.php'));

} else if ($data = $mform->get_data()) {
    if ($id) {
        $DB->update_record('local_greet_greetings', $data);
    } else {
        $data->id = $DB->insert_record('local_greet_greetings', $data);
    }

    add_to_log(SITEID, 'local_greet', 'editgreeting',
            'local/greet/edit.php?id=' . $data->id);

    redirect(new moodle_url('/local/greet/index.php', array('name' => $data->name)));
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('editgreeting', 'local_greet'));
$mform->display();
echo $OUTPUT->footer();
