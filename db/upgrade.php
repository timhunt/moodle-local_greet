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
 * Upgrade script for the example script.
 *
 * @package    local_greet
 * @copyright  2014 Tim Hunt
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


/**
 * Local_greet upgrade function.
 * @param string $oldversion the version we are upgrading from.
 */
function xmldb_local_greet_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2014022400) {

        // Define table local_greet_greetings to be created.
        $table = new xmldb_table('local_greet_greetings');

        // Adding fields to table local_greet_greetings.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table local_greet_greetings.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Adding indexes to table local_greet_greetings.
        $table->add_index('name', XMLDB_INDEX_UNIQUE, array('name'));

        // Conditionally launch create table for local_greet_greetings.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Greet savepoint reached.
        upgrade_plugin_savepoint(true, 2014022400, 'local', 'greet');
    }

    return true;
}
