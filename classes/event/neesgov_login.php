<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Plugin for gov br authentication.
 * Login event class
 * @package     auth_neesgov
 * @copyright   2023 NEES/UFAL https://www.nees.ufal.br/
 * @author      Saulo Sá (srssaulo@gmail.com)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace auth_neesgov\event;

use core\event\base;

/**
 * neesgov login event class
 */
class neesgov_login extends base {

    /**
     * implements
     * @return void
     * @throws \dml_exception
     */
    protected function init() {
        $this->data['crud'] = 'r';
        $this->data['edulevel'] = self::LEVEL_OTHER;
        $this->data['objecttable'] = 'user';
        $this->context = \context_system::instance();
    }

    /**
     * Overrides
     * @return \lang_string|string
     * @throws \coding_exception
     */
    public static function get_name() {
        return get_string('evt_neesgov_login', 'auth_neesgov');
    }

    /**
     * Overrides
     * @return \lang_string|string|null
     * @throws \coding_exception
     */
    public function get_description() {
        return get_string(
            'evt_neesgov_login_description',
            'auth_neesgov',
            ['userid' => $this->userid, 'objectid' => $this->objectid]);
    }
}
