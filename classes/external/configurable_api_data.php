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

namespace local_configurable_api\external;
defined('MOODLE_INTERNAL') || die;

require_once("$CFG->libdir/externallib.php");
require_once("$CFG->dirroot/webservice/externallib.php");

use external_api;
use external_function_parameters;
use external_value;
use external_single_structure;
use local_configurable_api\configurable_api_data_layer;

/**
 * Class configurable_api_data.
 * A class to for moodle webservice call.
 */
class configurable_api_data extends external_api {

    public static function configurable_api_data_parameters() {
        return new external_function_parameters(
            array(
                'configurable_api_id' => new external_value(PARAM_INT, '', false),
                'configurable_api_secret' => new external_value(PARAM_TEXT, '', false)
            )
        );
    }

    public static function configurable_api_data_is_allowed_from_ajax() {
        return true;
    }

    public static function configurable_api_data_returns() {
        return new external_single_structure(
            array(
                'data' => new external_value(PARAM_TEXT, 'Configurable api data')
            )
        );
    }

    public static function configurable_api_data($configurable_api_id = null, $configurable_api_secret = null) {
        $params = self::validate_parameters(
            self::configurable_api_data_parameters(),
            array(
                'configurable_api_id' => $configurable_api_id,
                'configurable_api_secret' => $configurable_api_secret
            )
        );

        $result = array(
            'data' => null
        );

        $result['data'] = configurable_api_data_layer::get_configurable_api_data_json($params);
        return $result;
    }
}
