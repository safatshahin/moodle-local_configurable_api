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
 * Plugin strings are defined here.
 *
 * @package     local_configurable_api
 * @category    string
 * @copyright   2021 Safat Shahin <safatshahin@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Configurable API';
$string['privacy:metadata'] = 'The Configurable API allows to get the data of configurable reports via an API.';

//form
$string['name'] = 'Name';
$string['maximum_character_255'] = 'Maximum 255 characters.';
$string['configurablereportid'] = 'Select configurable report';
$string['apisecret'] = 'Secret key for the API instance';
$string['yes'] = 'Yes';
$string['no'] = 'No';
$string['apisecret_help'] = 'This secret will help to differentiate between the instances you create. 
For example, all the instances will have the same token and endpoint, 
but one wont be able access another by changing the id as each of them will have a unique secret for each of them.';

//table
$string['create_instance'] = 'Create configurable API instances';
$string['id'] = 'ID';
$string['status'] = 'Status';
$string['samplerequest'] = 'Sample request';
$string['timemodified'] = 'Time modified';
$string['actions'] = 'Actions';
$string['active'] = 'Active';
$string['inactive'] = 'Inactive';
$string['activate'] = 'Activate';
$string['deactivate'] = 'Deactivate';
$string['deactive'] = 'Deactive';
$string['createnew'] = 'Create new';

//notification
$string['configure_api_saved'] = 'Configurable API successfully saved.';
$string['configure_api_save_error'] = 'Error saving configurable API.';
$string['configurable_api_deleted'] = 'Configurable api instance: {$a->name} is successfully deleted.';
$string['configurable_api_delete_failed'] = 'Configurable api instance: {$a->name} had error while deleting, please check database record.';
$string['delete_configurable_api'] = 'Delete configurable api instance';
$string['delete_configurable_api_confirmation'] = 'Are you sure you want to delete configurable api instance: {$a->name} ?';
$string['configurable_api_active'] = 'Configurable api: {$a->name} activated';
$string['configurable_api_active_error'] = 'Error activating configurable api: {$a->name}';
$string['configurable_api_deactive'] = 'Configurable api: {$a->name} deactivated';
$string['configurable_api_deactive_error'] = 'Error deactivating configurable api: {$a->name}';
