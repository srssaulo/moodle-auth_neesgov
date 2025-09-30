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
 * English translation.
 * @package     auth_neesgov
 * @copyright   2023 NEES/UFAL https://www.nees.ufal.br/
 * @author      Saulo Sá (srssaulo@gmail.com)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
$string['auth_neesgovdescription'] = 'Nees Gov.br connect plugin';
$string['auth_type_change'] = 'Change to Manual auth type';
$string['auth_type_change_desc'] = 'After login, change auth type to manual';
$string['btn_gov_login'] = "Login with gov.br";
$string['client_id'] = 'Client ID';
$string['client_id_desc'] = 'Client ID previously agreed with gov.br.';
$string['client_secret'] = 'Client secret';
$string['client_secret_desc'] = 'Client secret previously agreed with gov.br.';
$string['evt_neesgov_login'] = 'Login with Gov.br';
$string['evt_neesgov_login_description'] = 'User id \'{$a->userid}\' Logged in with Gov.br';
$string['generaldesc'] = 'Nees Gov.br auth general config vars';
$string['login_fail'] = 'Login fail';
$string['moduleid'] = 'Nees module id';
$string['moduleid_desc'] = 'Nees app repository module id';
$string['plugindescription'] = 'This authentication plugin  allows users to login with their credentials from Gov.br provider.';
$string['pluginname'] = 'Nees Gov.br';
$string['post_logout_uri'] = 'After logout, redirect URI';
$string['post_logout_uri_desc'] = 'Previously agreed with Gov.br. Must be the agreed URI.';
$string['privacy:metadata:auth_neesgov_token'] = 'Stores temporary access tokens and user identification data obtained from the NEESGov service during the authentication process.';
$string['privacy:metadata:auth_neesgov_token:authcode'] = 'The authorization code (Auth Code) obtained from the neesgov service. This is a temporary, single-use code exchanged for the access token.';
$string['privacy:metadata:auth_neesgov_token:expiry'] = 'The timestamp (Unix time) indicating when the authorization code or the associated access token expires.';
$string['privacy:metadata:auth_neesgov_token:idtoken'] = 'The ID Token, which is a JSON Web Token (JWT) containing basic **user identity claims** (such as name and email) from the external service.';
$string['privacy:metadata:auth_neesgov_token:picture'] = 'The URL for the user\'s profile picture** provided by the NEESGov service.';
$string['privacy:metadata:auth_neesgov_token:userid'] = 'The Moodle user ID associated with the token. This field links the external account data to a specific user within Moodle.';
$string['privacy:metadata:auth_neesgov_token:username'] = 'The username or ID (typically the Gov.br identifier) used to obtain the token from the external service. It acts as a unique identifier for the user in the gov.br system.';
$string['redirect_uri'] = 'After authorize, redirect URI';
$string['redirect_uri_desc'] = 'Previously agreed with Gov.br. Must be the agreed URI.';
$string['uri_provider'] = 'Provider URI';
$string['uri_provider_desc'] = 'Provider base URL. Compose Authorize and Logout requests. <b>Obs</b>: default value is <b>staging</b>';
$string['user_not_registred'] = 'User not registered in Moodle. You need to be registered in Moodle first';
