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
 * Admin settings and defaults.
 *
 * @package auth_neesgov
 * @copyright  2023 Saulo Sá <srssaulo@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    // Introductory explanation.
    $settings->add(new admin_setting_heading('auth_neesgov/pluginname', '',
        get_string('generaldesc', 'auth_neesgov')));



    $settings->add(new admin_setting_configtext('auth_neesgov/uri_provider',
       get_string('uri_provider', 'auth_neesgov'),  get_string('uri_provider_desc', 'auth_neesgov'), 'https://sso.staging.acesso.gov.br' , PARAM_URL));

    $settings->add(new admin_setting_configtext('auth_neesgov/redirect_uri',
        get_string('redirect_uri', 'auth_neesgov'),  get_string('redirect_uri_desc', 'auth_neesgov'), '[moodle_base_url]/auth/neesgov/login.php' , PARAM_URL));

    $settings->add(new admin_setting_configtext('auth_neesgov/post_logout_uri',
        get_string('post_logout_uri', 'auth_neesgov'),  get_string('post_logout_uri_desc', 'auth_neesgov'), '[moodle_base_url]/auth/neesgov/logout.php' , PARAM_URL));

    $settings->add(new admin_setting_configtext('auth_neesgov/client_id',
        get_string('client_id', 'auth_neesgov'),  get_string('client_id_desc', 'auth_neesgov'), '' , PARAM_URL));

    $settings->add(new admin_setting_configtext('auth_neesgov/client_secret',
        get_string('client_secret', 'auth_neesgov'),  get_string('client_secret_desc', 'auth_neesgov'), '' , PARAM_TEXT));



}
