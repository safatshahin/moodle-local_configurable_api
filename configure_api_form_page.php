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
 * Plugin form page defined here.
 *
 * @package     local_configurable_api
 * @category    admin
 * @copyright   2021 Safat Shahin <safatshahin@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require($CFG->dirroot.'/local/configurable_api/classes/configure_api_page.php');
require($CFG->dirroot.'/local/configurable_api/classes/output/forms/configure_api_form.php');

use \core\output\notification;

require_login();
if (!is_siteadmin()) {
    print_error('nopermissions', 'error');
}

$context = context_system::instance();

$id = optional_param('id', null, PARAM_INT);

$data =  new configure_api_page($id);
$editoroptions = array(
    'subdirs' => 0,
    'noclean' => true,
    'context' => $context,
    'removeorphaneddrafts' => true,
);

$title = get_string("create_instance", 'local_configurable_api');
$PAGE->set_url('/local/configurable_api/configure_api_form_page.php');
$PAGE->set_pagelayout('admin');
$PAGE->set_context($context);
$PAGE->navbar->add($title);
$PAGE->set_title($title);
$PAGE->set_heading(get_string("pluginname", 'local_configurable_api'));

$returnurl = new moodle_url($CFG->wwwroot . '/local/configurable_api/configure_api_page.php');
$args = array(
    'editoroptions' => $editoroptions,
    'data' => $data
);

$configure_api_form = new configure_api_form(null, $args);


if ($configure_api_form->is_cancelled()) {
    redirect($returnurl);
} else if ($savedata = $configure_api_form->get_data()) {
    $new_configure_api =  new configure_api_page();
    if (empty($savedata->id)) {
        $savedata->active = 1;
    }
    $new_configure_api->construct_configure_api_page($savedata);
    if ($new_configure_api->save()) {
        $message = get_string('configure_api_saved', 'local_configurable_api');
        $messagestyle = notification::NOTIFY_SUCCESS;
        redirect($returnurl, $message, null, $messagestyle);
    } else {
        $message = get_string('configure_api_save_error', 'local_configurable_api');
        $messagestyle = notification::NOTIFY_ERROR;
        redirect($returnurl, $message, null, $messagestyle);
    }
    redirect($returnurl, $message, null, $messagestyle);
}

$params = [
    'title' => $title,
    'formhtml' => $configure_api_form->export_for_template()
];

echo $OUTPUT->header();

$renderer = $PAGE->get_renderer('local_configurable_api');

echo $renderer->render_form_page($params);

echo $OUTPUT->footer();
