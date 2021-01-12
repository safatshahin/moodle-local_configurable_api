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
 * Plugin renderer is defined here.
 *
 * @package     local_configurable_api
 * @category    string
 * @copyright   2021 Safat Shahin <safatshahin@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_configurable_api\output;
defined('MOODLE_INTERNAL') || die;

use plugin_renderer_base;
use stdClass;

/**
 * Standard HTML output renderer
 */
class renderer extends plugin_renderer_base {

    private $params = null;

    /**
     * renderer constructor.
     * Just sets the params.
     * @param null $params
     */
    public function __construct($params = null) {
        $this->params = $params;
    }

    /**
     * @param $params
     * @return string Template
     */
    public function render_action_buttons($params) {
        global $OUTPUT;
        $context = new stdClass();
        $context->id = $params['id'];
        $context->buttons = $params['buttons'];
        return $OUTPUT->render_from_template('local_configurable_api/action_buttons', $context);
    }

    /**
     * @param $params
     * @return string Template
     */
    public function render_form_page($params) {
        global $OUTPUT;
        $context = new stdClass();
        $context->title = $params['title'];
        $context->formhtml = $params['formhtml'];
        return $OUTPUT->render_from_template('local_configurable_api/api_form', $context);
    }

    /**
     * @param $params
     * @return string Template
     */
    public function render_table_page($params) {
        global $OUTPUT;
        $context = new stdClass();
        $context->title = $params['title'];
        $context->editurl = $params['editurl'];
        $context->tablehtml = $params['tablehtml'];
        return $OUTPUT->render_from_template('local_configurable_api/api_table', $context);
    }


}
