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
 * Plugin data layer is defined here.
 *
 * @package     local_configurable_api
 * @category    admin
 * @copyright   2021 Safat Shahin <safatshahin@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_configurable_api;

use dml_exception;

defined('MOODLE_INTERNAL') || die;

/**
 * Class configurable_api_data_layer.
 * A class to get the data for the api call.
 */
class configurable_api_data_layer {

    /**
     * get the api instance from the api call
     * @param $param
     * @return string
     * @throws dml_exception
     */
    public static function get_configurable_api_data_json($param) {
        global $DB;
        if (empty($param['configurable_api_id']) || empty($param['configurable_api_secret'])) {
            return 'Required parameters not supplied.';
        }
        $api_instance = $DB->get_record('local_configurable_api',array('id' => $param['configurable_api_id'],
            'apisecret' => $param['configurable_api_secret'], 'active' => 1), '*');
        if (!$api_instance) {
            return 'No active api instance found';
        }
        return self::get_api_json($api_instance);
    }

    /**
     * export report to json
     * @param $report
     * @return string
     */
    public static function export_report($report){
        $table = $report->table;
        $json = [];
        $headers = $table->head;
        foreach ($table->data as $data) {
            $jsonObject = [];
            foreach ($data as $index => $value) {
                $jsonObject[$headers[$index]] = $value;
            }
            $json[] = $jsonObject;
        }
        if(empty($json)) {
            return 'No date found';
        }
        return json_encode($json);
    }

    /**
     * get report json from the configurable report
     * @param $api_instance
     * @return string
     * @throws dml_exception
     */
    public static function get_api_json($api_instance) {
        global $DB, $CFG;
        $report = $DB->get_record('block_configurable_reports', array('id' => (int)$api_instance->configurablereportid), '*');
        if (!$report) {
            return 'No associated report found';
        }
        $courseid = $report->courseid;
        if (!$course = $DB->get_record('course', ['id' => $courseid])) {
            return 'Error found in configurable report database record';
        }
        require_once($CFG->dirroot.'/blocks/configurable_reports/report.class.php');
        require_once($CFG->dirroot.'/blocks/configurable_reports/locallib.php');
        require_once($CFG->dirroot.'/blocks/configurable_reports/reports/'.$report->type.'/report.class.php');

        $reportclassname = 'report_'.$report->type;
        $reportclass = new $reportclassname($report);
        $reportclass->create_report();
        return self::export_report($reportclass->finalreport);
    }
}
