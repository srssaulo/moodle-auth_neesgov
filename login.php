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
 * Nees GOV.BR authentication. This file is a simple login entry point for GOV.BR identity providers.
 *
 * @package auth_neesgov
 * @copyright 2023 Saulo Sá <srssaulo@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

require_once('../../config.php');

//$issuerid = required_param('id', PARAM_INT);
$wantsurl = new moodle_url(optional_param('wantsurl', '', PARAM_URL));

$PAGE->set_context(context_system::instance());

//$PAGE->set_url(new moodle_url('/auth/neesgov/login.php', ['id' => $issuerid]));
$PAGE->set_url(new moodle_url('/auth/neesgov/login.php'));
$PAGE->set_pagelayout('popup');
//require_sesskey(); //TODO reactivate


//$issuer = new \core\oauth2\issuer($issuerid); //delete
//if (!$issuer->is_available_for_login()) {
//    throw new \moodle_exception('issuernologin', 'auth_oauth2');
//}

$returnparams = ['wantsurl' => $wantsurl, 'sesskey' => sesskey()];
$returnurl = new moodle_url('/auth/neesgov/login.php', $returnparams); //provável que não seja necessário

//$client = \core\oauth2\api::get_user_oauth_client($issuer, $returnurl);//TODO criar auth client
$neesflow = new \auth_neesgov\neesflow();

$neesflow->handleRedirect();
