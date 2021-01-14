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
 * Plugin data handler class defined here.
 *
 * @package     local_configurable_api
 * @category    admin
 * @copyright   2021 Safat Shahin <safatshahin@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Class configure_api_page.
 * A class to help with data.
 */
class configure_api_page {

    public $name = null;
    public $configurablereportid = 0;
    public $apisecret = null;
    public $active = 0;
    public $usermodified = 0;

    /**
     * configure_api_page constructor.
     * Builds object if $id provided.
     * @param null $id
     * @throws dml_exception
     */
    public function __construct($id = null) {
        if (!empty($id)) {
            $this->load_configure_api_page($id);
        }
    }

    /**
     * Constructs the actual configure_api_page object given either a $DB object or Moodle form data.
     * @param $configure_api_page
     */
    public function construct_configure_api_page($configure_api_page) {
        if (!empty($configure_api_page)) {
            global $USER;
            $this->id = $configure_api_page->id;
            $this->name = $configure_api_page->name;
            $this->configurablereportid = $configure_api_page->configurablereportid;
            $this->apisecret = $configure_api_page->apisecret;
            $this->active = $configure_api_page->active;
            $this->usermodified = (int)$USER->id;
        }
    }

    /**
     * Gets the specified configure_api and loads it into the object.
     * @param $id
     * @throws dml_exception
     */
    private function load_configure_api_page($id) {
        global $DB;
        $configure_api_page = $DB->get_record('local_configurable_api', array('id' => $id));
        $this->construct_configure_api_page($configure_api_page);
    }

    /**
     * Delete the configure_api.
     * @return bool
     * @throws dml_exception
     */
    public function delete() {
        global $DB;
        if (!empty($this->id)) {
            return $DB->delete_records('local_configurable_api', array('id' => $this->id));
        }
        return false;
    }

    /**
     * Deactivate/activate the configure_api.
     * @return bool
     * @throws dml_exception
     */
    public function activate_deactivate() {
        global $DB;
        if (!empty($this->id)) {
            $this->timemodified = time();
            $savesuccess = $DB->update_record('local_configurable_api', $this);
            if ($savesuccess) {
                return true;
            }
        }
        return false;
    }

    /**
     * Save or create configure_api.
     * @return bool
     * @throws dml_exception
     */
    public function save() {
        global $DB;
        $savesuccess = false;
        if (!empty($this->id)) {
            $this->timemodified = time();
            $savesuccess = $DB->update_record('local_configurable_api', $this);
        } else {
            $this->timecreated = time();
            $this->timemodified = time();
            $this->id = $DB->insert_record('local_configurable_api', $this);
            if (!empty($this->id)) {
                $savesuccess = true;
            }
        }
        if ($savesuccess) {
            return true;
        }
        return false;
    }


    /**
     * Gets all the active configurable reports.
     * @return array
     * @throws dml_exception
     */
    public function get_active_configurable_reports() {
        global $DB;
        $reports = array();
        $configurable_reports = $DB->get_records_sql('SELECT cr.id, cr.name
                                                          FROM {block_configurable_reports} cr
                                                          WHERE cr.visible = :visible',
                                                            array('visible' => 1));
        if (empty($configurable_reports)) {
            $reports[0] = 'No active report found';
            return $reports;
        }
        foreach ($configurable_reports as $configurable_report) {
            $reports[$configurable_report->id] = $configurable_report->name;
        }
        return $reports;
    }
}
