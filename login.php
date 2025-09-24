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
 * Login flow
 * @package     auth_neesgov
 * @copyright   2023 NEES/UFAL https://www.nees.ufal.br/
 * @author      Saulo Sá (srssaulo@gmail.com)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');

require_once($CFG->dirroot."/auth/neesgov/classes/Connect.php");
require_once($CFG->dirroot."/auth/neesgov/classes/OpenIDConnectClient.php");

use auth_neesgov\Connect;
use auth_neesgov\neesflow;

$wantsurl = new moodle_url(optional_param('wantsurl', '', PARAM_URL));

$PAGE->set_context(context_system::instance());

$PAGE->set_url(new moodle_url('/auth/neesgov/login.php'));
$PAGE->set_pagelayout('popup');

$returnparams = ['wantsurl' => $wantsurl, 'sesskey' => sesskey()];

$cn = new Connect();

$cn->openidauthenticate();

$neesflow = new neesflow();

$neesflow->handleRedirect($cn->getuserinfo());
