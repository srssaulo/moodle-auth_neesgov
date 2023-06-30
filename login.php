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
require_once  "{$CFG->dirroot}'/auth/neesgov/classes/Connect.php"; //TODO put in general place


use auth_neesgov\Connect;

//$issuerid = required_param('id', PARAM_INT);
$wantsurl = new moodle_url(optional_param('wantsurl', '', PARAM_URL));

$PAGE->set_context(context_system::instance());

//$PAGE->set_url(new moodle_url('/auth/neesgov/login.php', ['id' => $issuerid]));
$PAGE->set_url(new moodle_url('/auth/neesgov/login.php'));
$PAGE->set_pagelayout('popup');
//require_sesskey(); //TODO reactivate


$returnparams = ['wantsurl' => $wantsurl, 'sesskey' => sesskey()];
$returnurl = new moodle_url('/auth/neesgov/login.php', $returnparams); //provável que não seja necessário


//testing
echo 'path to Connect class: '."{$CFG->dirroot}'/auth/neesgov/classes/Connect.php"; //TODO put in general place
$cn = new Connect();


$cn->OpenIDAuthenticate();


//TODO code new flow to authenticate with gov.br
//$neesflow = new \auth_neesgov\neesflow();
//
//$neesflow->handleRedirect();
