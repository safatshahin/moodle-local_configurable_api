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
 * Plugin forms are defined here.
 *
 * @package     local_configurable_api
 * @category    admin
 * @copyright   2021 Safat Shahin <safatshahin@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/formslib.php');

/**
 * Class configure_api_form.
 * An extension of your usual Moodle form.
 */
class configure_api_form extends moodleform {
    /**
     * Defines the custom configure_api_form.
     * @throws dml_exception
     * @throws coding_exception
     */
    public function definition() {
        $mform = $this->_form;
        $data = $this->_customdata['data'];

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('text', 'name', get_string('name','local_configurable_api'));
        $mform->addRule('name', get_string('required'), 'required', null, 'client');
        $mform->addRule('name',get_string('maximum_character_255', 'local_configurable_api'), 'maxlength', 255, 'client');
        $mform->setType('name', PARAM_TEXT);

        $activeconfigurablereportid = (new configure_api_page())->get_active_configurable_reports();
        $mform->addElement('select', 'configurablereportid', get_string('configurablereportid', 'local_configurable_api'), $activeconfigurablereportid);
        $mform->addRule('configurablereportid', get_string('required'), 'required', null, 'client');
        $mform->setDefault('configurablereportid', 0);

        $mform->addElement('password', 'apisecret', get_string('apisecret','local_configurable_api'));
        $mform->addHelpButton('apisecret', 'apisecret', 'local_configurable_api');
        $mform->addRule('apisecret',get_string('maximum_character_255', 'local_configurable_api'), 'maxlength', 255, 'client');
        $mform->setType('apisecret', PARAM_TEXT);

        $encodejson = array();
        $encodejson[] = $mform->createElement('radio', 'encodejson', '', get_string('no', 'local_configurable_api'), 0);
        $encodejson[] = $mform->createElement('radio', 'encodejson', '', get_string('yes','local_configurable_api'), 1);
        $mform->addGroup($encodejson, 'encodejsongr', get_string('encodejson', 'local_configurable_api'), array(' '), false);
        $mform->setDefault('encodejson', 0);

        $mform->addElement('hidden', 'active');
        $mform->setType('active', PARAM_INT);

        $this->add_action_buttons();
        $this->set_data($data);
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @return string
     */
    public function export_for_template() {
        ob_start();
        $this->display();
        $formhtml = ob_get_contents();
        ob_end_clean();

        return $formhtml;
    }
}
