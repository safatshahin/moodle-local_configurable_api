<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin table page defined here.
 *
 * @package     local_configurable_api
 * @category    admin
 * @copyright   2021 Safat Shahin <safatshahin@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require($CFG->dirroot.'/local/configurable_api/classes/configure_api_page.php');
require($CFG->dirroot.'/local/configurable_api/classes/output/tables/configure_api_table.php');

use \core\output\notification;

require_login();
admin_externalpage_setup('local_configurable_api');

$id = optional_param('id', null, PARAM_INT);
$action = optional_param('action', '', PARAM_TEXT);
$confirm = optional_param('confirm', 0, PARAM_BOOL);

$context = context_system::instance();
$title = get_string("pluginname", 'local_configurable_api');
$PAGE->set_url('/local/configurable_api/configure_api_page.php');
$PAGE->set_pagelayout('admin');
$PAGE->navbar->add($title);
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_context($context);
$returnurl = new moodle_url($CFG->wwwroot . '/local/configurable_api/configure_api_page.php');

$configure_api =  new configure_api_page($id);

//table logic
if (!empty($configure_api->id)) {
    if ($action == 'delete') {
        $PAGE->url->param('action', 'delete');
        $a = new stdClass();
        $a->name = $configure_api->name;
        if ($confirm and confirm_sesskey()) {
            if ($configure_api->delete()) {
                $message = get_string('configurable_api_deleted', 'local_configurable_api', $a);
                $messagestyle = notification::NOTIFY_SUCCESS;
            } else {
                $message = get_string('configurable_api_delete_failed', 'local_configurable_api', $a);
                $messagestyle = notification::NOTIFY_ERROR;
            }
            redirect($returnurl, $message, null, $messagestyle);
        }
        $strheading = get_string('delete_configurable_api', 'local_configurable_api');
        $PAGE->navbar->add($strheading);
        $PAGE->set_title($strheading);
        $PAGE->set_heading($strheading);

        echo $OUTPUT->header();

        $yesurl = new moodle_url($CFG->wwwroot . '/local/configurable_api/configure_api_page.php', array(
            'id' => $configure_api->id, 'action' => 'delete', 'confirm' => 1, 'sesskey' => sesskey()
        ));
        $message = get_string('delete_configurable_api_confirmation', 'local_configurable_api', $a);
        echo $OUTPUT->confirm($message, $yesurl, $returnurl);
        echo $OUTPUT->footer();
        die;
    }
    if (confirm_sesskey()) {
        if ($action == 'show') {
            $a = new stdClass();
            $a->name = $configure_api->name;
            $message = get_string('configurable_api_active', 'local_configurable_api', $a);
            $messagestyle = notification::NOTIFY_SUCCESS;
            if (!$configure_api->active) {
                $configure_api->active = 1;
                if (!$configure_api->save()) {
                    $message = get_string('configurable_api_active_error', 'local_configurable_api', $a);
                    $messagestyle = notification::NOTIFY_ERROR;
                }
            }
            redirect($returnurl, $message, null, $messagestyle);
        } else if ($action == 'hide') {
            $a = new stdClass();
            $a->name = $configure_api->name;
            $message = get_string('configurable_api_deactive', 'local_configurable_api', $a);
            $messagestyle = notification::NOTIFY_SUCCESS;
            // Don't bother doing anything if it's already inactive.
            if ($configure_api->active) {
                $configure_api->active = 0;
                if (!$configure_api->save()) {
                    $message = get_string('configurable_api_deactive_error', 'local_configurable_api', $a);
                    $messagestyle = notification::NOTIFY_ERROR;
                }
            }
            redirect($returnurl, $message, null, $messagestyle);
        }
    }
}

$configurable_api_table = new configure_api_table('configure_api_table');

$params = [
    'title' => $title,
    'editurl' => new moodle_url($CFG->wwwroot . '/local/configurable_api/configure_api_form_page.php'),
    'tablehtml' => $configurable_api_table->export_for_template()
];

echo $OUTPUT->header();

$renderer = $PAGE->get_renderer('local_configurable_api');

echo $renderer->render_table_page($params);

echo $OUTPUT->footer();
