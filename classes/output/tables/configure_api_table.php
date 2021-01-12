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

require_once($CFG->libdir.'/tablelib.php');

/**
 * Class configure_api_table.
 * An extension of your regular Moodle table.
 */
class configure_api_table extends table_sql {

    public $search = '';
    /**
     * configure_api_table constructor.
     * Sets the SQL for the table and the pagination.
     * @param $uniqueid
     * @throws coding_exception
     */
    public function __construct($uniqueid) {
        global $PAGE;
        parent::__construct($uniqueid);

        $columns = array('id', 'name', 'active', 'samplerequest', 'timemodified', 'actions');
        $headers = array(
            get_string('id', 'local_configurable_api'),
            get_string('name','local_configurable_api'),
            get_string('status','local_configurable_api'),
            get_string('samplerequest', 'local_configurable_api'),
            get_string('timemodified', 'local_configurable_api'),
            get_string('actions','local_configurable_api')
        );
        $this->no_sorting('actions');
        $this->no_sorting('samplerequest');
        $this->is_collapsible = false;
        $this->define_columns($columns);
        $this->define_headers($headers);
        $fields = "id,
        name,
        active,
        '' AS samplerequest,
        FROM_UNIXTIME(timemodified, '%d/%m/%Y %H:%i:%s') AS timemodified,
        timemodified as timemodified_raw,
        '' AS actions";
        $from = "{local_configurable_api}";
        $where = 'id > 0';
        $params = array();
        $this->set_sql($fields, $from, $where, $params);
        $this->set_count_sql("SELECT COUNT(id) FROM " . $from . " WHERE " . $where, $params);
        $this->define_baseurl($PAGE->url);
    }

    /**
     * @param $values
     * @return string
     * @throws moodle_exception
     */
    public function col_name($values) {
        $urlparams = array('id' => $values->id, 'sesskey' => sesskey());
        $editurl = new moodle_url('/local/configurable_api/configure_api_form_page.php', $urlparams);
        return '<a href = "' . $editurl . '">' . $values->name . '</a>';
    }

    /**
     * @param $values
     * @return string
     * @throws coding_exception
     */
    public function col_active($values) {
        $status = get_string('active', 'local_configurable_api');
        $css = 'success';
        if (!$values->active) {
            $status = get_string('inactive', 'local_configurable_api');
            $css = 'danger';
        }
        return '<div class = "text-' . $css . '"><i class = "fa fa-circle"></i>&nbsp;' . $status . '</div>';
    }

    /**
     * @param $values
     * @return string
     * @throws moodle_exception
     */
    public function col_samplerequest($values) {
        return 'will figure this out';
    }

    /**
     * convert invalid to '-'
     * @param $values
     * @return string
     */
    public function col_timemodified($values) {
        return !empty($values->timemodified_raw) ? $values->timemodified : '-';
    }

    /**
     * @param $values
     * @return string Renderer template
     * @throws coding_exception
     * @throws moodle_exception
     */
    public function col_actions($values) {
        global $PAGE;

        $urlparams = array('id' => $values->id, 'sesskey' => sesskey());
        $editurl = new moodle_url('/local/configurable_api/configure_api_form_page.php', $urlparams);
        $deleteurl = new moodle_url('/local/configurable_api/configure_api_page.php', $urlparams + array('action' => 'delete'));
        // Decide to activate or deactivate.
        if ($values->active) {
            $toggleurl = new moodle_url('/local/configurable_api/configure_api_page.php', $urlparams + array('action' => 'hide'));
            $togglename = get_string('deactivate', 'local_configurable_api');
            $toggleicon = 'fa fa-eye';
        } else {
            $toggleurl = new moodle_url('/local/configurable_api/configure_api_page.php', $urlparams + array('action' => 'show'));
            $togglename = get_string('activate', 'local_configurable_api');
            $toggleicon = 'fa fa-eye-slash';
        }

        $renderer = $PAGE->get_renderer('local_configurable_api');
        $params = array(
            'id' => $values->id,
            'buttons' => array(
                array(
                    'name' => get_string('edit'),
                    'icon' => 'fa fa-edit',
                    'url' => $editurl
                ),
                array(
                    'name' => get_string('delete'),
                    'icon' => 'fa fa-trash',
                    'url' => $deleteurl
                ),
                array(
                    'name' => $togglename,
                    'icon' => $toggleicon,
                    'url' => $toggleurl
                )
            )
        );

        return $renderer->render_action_buttons($params);
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @return string
     */
    public function export_for_template() {

        ob_start();
        $this->out(20, true);
        $tablehtml = ob_get_contents();
        ob_end_clean();

        return $tablehtml;
    }
}



