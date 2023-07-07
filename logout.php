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
 * Single Sign Out end point.
 *
 * @package auth_neesgov
 * @copyright 2023 Saulo Sá <srssaulo@gmail.com>
 * @author Saulo Sá <srssaulo@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require  $CFG->dirroot.'/auth/neesgov/classes/Connect.php'; //TODO put in general place


$PAGE->set_url('/auth/neesgov/logout.php');
$PAGE->set_context(context_system::instance());


if(isset($_REQUEST['MOODLEID1_'])){ //if true, we came back from gov.br
    redirect(new moodle_url('/'));
}



//must call before. Just return when user is not logged
require_logout();


echo \auth_neesgov\Connect::logout_govbr();//auto send form




die();
