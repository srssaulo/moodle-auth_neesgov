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
 * gov br connect steps
 * @package     auth_neesgov
 * @copyright   2023 NEES/UFAL https://www.nees.ufal.br/
 * @author      Saulo Sá (srssaulo@gmail.com)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace auth_neesgov;

use core\event\user_login_failed;
use auth_neesgov\event\neesgov_login;

/**
 * implements nees connection flow
 * @package auth_neesgov
 * @copyright 2023 Saulo Sá (srssaulo@gmail.com)
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */
class neesflow {

    /**
     * stores previous auth method
     * @var string;
     */
    private $backtoauthmethod;

    /**
     * handle user login
     * @param object $userInfo {'id'=>$oidc->requestUserInfo('sub'),
     * 'email'=>$oidc->requestUserInfo('email'),
     * 'name'=>$oidc->requestUserInfo('name'),
     * 'picture}
     * @return void
     */
    private function handlelogin($userinfo) {
        global $DB, $CFG, $USER;

        // Do not continue if auth plugin is not enabled.
        if (!is_enabled_auth('neesgov')) {
            throw new moodle_exception('erroroidcnotenabled', 'auth_neesgov', null, null, '1');
        }

        $mdluser = $DB->get_record('user', ['username' => trim($userinfo->id), 'deleted' => 0]);

        if (!$mdluser) {
            redirect($CFG->wwwroot . '/auth/neesgov/logout.php?pass=1', get_string('user_not_registred', 'auth_neesgov'), 3);
        }

        if ($mdluser->auth != 'neesgov') {// Change user auth type to neesgov.
            $this->backtoauthmethod =  $mdluser->auth;
            $mdluser->auth = 'neesgov';
        }

        if ($userinfo->email != $mdluser->email) { // User\'s email update.
            $mdluser->email = $userinfo->email;
        }

        // Updating first and last name gov.br.
        $govfirstname = strtok($userinfo->name, " ");
        $govlastname = strtok(null);

        if ($mdluser->firstname != $govfirstname) {
            $mdluser->firstname = $govfirstname;
        }
        if ($mdluser->lastname != $govlastname) {
            $mdluser->lastname = $govlastname;
        }

        // Updating mdl user.
        $DB->update_record('user', $mdluser);

        $user = authenticate_user_login($mdluser->username, $mdluser->password, true);
        if (!empty($user)) {
            if (get_user_preferences('auth_forcepasswordchange', 0, $user)) {
                set_user_preference('auth_forcepasswordchange', 0, $user);
            }

            complete_user_login($user);
            $user->password = $mdluser->password;
            $DB->update_record('user', $user);
            // Trigger neesgov event login.
            $event = neesgov_login::create(
                [
                    'userid' => $USER->id,
                    'objectid' => $USER->id,
                    'other' => [
                        'username' => $USER->username,
                    ],
                ]
            );
            $event->trigger();

        } else {

            $eventdata = ['other' => ['username' => $mdluser->username, 'reason' => AUTH_LOGIN_NOUSER]];
            $event = user_login_failed::create($eventdata);
            $event->trigger();

            // There was a problem in authenticate_user_login.
            throw new \moodle_exception('login_fail', 'auth_neesgov', null, null, '2');
        }

    }

    /**
     * handle redirect flow
     * @param object $userinfo {'id'=>$oidc->requestUserInfo('sub'),
     * 'email'=>$oidc->requestUserInfo('email'),
     * 'name'=>$oidc->requestUserInfo('name'),
     * 'picture}
     * @return void
     * @throws \moodle_exception
     */
    public function handleredirect($userinfo) {
        global $DB, $USER;

        $authtypechange = get_config('auth_neesgov', 'auth_type_change');

        if (is_null($userinfo)) {
            // If null  didn't make login correctly.
            // Return to login page.
            redirect(new \moodle_url('/login'), get_string('login_fail', 'auth_neesgov'));
        }

        $this->handlelogin($userinfo);

        if ($authtypechange) { // If this conf is true, change auth type to previous after login with neesgov.
            $USER->auth = $this->backtoauthmethod;
            $DB->update_record('user', $USER); // Force manual auth change!
        }

        // Its all right and user is redirected to dashboard Moodle.
        redirect(new \moodle_url('/my'));
    }

}
