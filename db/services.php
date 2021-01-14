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
 * Plugin webservices are defined here.
 *
 * @package     local_configurable_api
 * @category    admin
 * @copyright   2021 Safat Shahin <safatshahin@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$functions = array(
    'configurable_api_data' => array(
        'classname'   => 'local_configurable_api\webservice\configurable_api_data',
        'methodname'  => 'configurable_api_data',
        'classpath'   => 'local/configurable_api/classes/webservice/configurable_api_data.php',
        'description' => 'Get configurable api data',
        'type'        => 'read',
        'ajax'        => true
    )
);

// We define the services to install as pre-build services. A pre-build service is not editable by administrator.
$services = array(
    'local_configurable_api' => array(
        'functions' => array(
            'configurable_api_data'
        ),
        'restrictedusers' => 0,
        'enabled' => 1
    )
);
