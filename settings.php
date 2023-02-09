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

    // Display locking / mapping of profile fields.
//    $authplugin = get_auth_plugin('none');
//    display_auth_lock_options($settings, $authplugin->authtype, $authplugin->userfields,
//        get_string('auth_fieldlocks_help', 'auth'), false, false);

    $settings->add(new admin_setting_configtext('auth_neesgov/neesmodid',
       get_string('moduleid', 'auth_neesgov'),  get_string('moduleid_desc', 'auth_neesgov'), '' , PARAM_INT));

    $settings->add(new admin_setting_configtext('auth_neesgov/redirecturl',
       get_string('redirecturl', 'auth_neesgov'),  get_string('redirecturl_desc', 'auth_neesgov'), 'https://develop-login-integracao-dot-scanner-prova.rj.r.appspot.com/login' , PARAM_URL));


}
