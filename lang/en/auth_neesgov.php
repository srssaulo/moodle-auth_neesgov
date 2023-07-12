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
 * English translation.
 *
 * @package    local_neestools
 * @author     Saulo Sá <srssaulo@gmail.com>
 * @copyright  2023 MEC
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Nees GOVBR';
$string['auth_neesgovdescription'] = 'Nees Gov Br connect plugin';
$string['generaldesc'] = 'Nees GOVBR auth general config vars';
$string['moduleid'] = 'Nees module id';
$string['moduleid_desc'] = 'Nees app repository module id';

$string['uri_provider'] = 'Provider URI';
$string['uri_provider_desc'] = "Provider base URL. Compose Authorize and Logout requests. <b>Obs</b>: default value is <b>staging</b>";

$string['redirect_uri'] = 'After authorize, redirect URI';
$string['redirect_uri_desc'] = "Previously agreed with gov.br. Must be the agreed URI.";

$string['post_logout_uri'] = 'After logout, redirect URI';
$string['post_logout_uri_desc'] = "Previously agreed with gov.br. Must be the agreed URI.";

$string['client_id'] = 'Client ID';
$string['client_id_desc'] = "Client ID previously agreed with gov.br.";

$string['client_secret'] = 'Client secret';
$string['client_secret_desc'] = "Client secret previously agreed with gov.br.";

