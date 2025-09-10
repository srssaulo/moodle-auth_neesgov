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
 * Plugin for gov.br authentication.
 *
 * @package     auth_neesgov
 * @copyright   2023 NEES/UFAL <https://www.nees.ufal.br/>
 * @author      Saulo Sá <srssaulo@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/authlib.php');

/**
 * Start auth_neesgov reference
 */
class auth_plugin_neesgov extends \auth_neesgov\auth {

    /**
     * define auth type name
     */
    public function __construct() {
        $this->authtype = 'neesgov';
    }

}


