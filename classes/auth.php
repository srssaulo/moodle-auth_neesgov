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
 * Anobody can login with any password.
 *
 * @package auth_neesgov
 * @copyright 2023 Saulo Sá <srssaulo@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

namespace auth_neesgov;

use core_analytics\user;
use pix_icon;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/authlib.php');

use auth_neesgov\neesflow;

class auth extends \auth_plugin_base
{


    /**
     * Returns true if this authentication plugin is "internal".
     *
     * @return bool Whether the plugin uses password hashes from Moodle user table for authentication.
     */
    public function is_internal()
    {
        return false;
    }


    /**
     * Indicates if moodle should automatically update internal user
     * records with data from external sources using the information
     * from get_userinfo() method.
     *
     * @return bool true means automatically copy data from ext to user table
     */
    public function is_synchronised_with_external()
    {
        return true;
    }

    public function loginpage_idp_list($wantsurl)
    {
        $params = ['sesskey' => sesskey()];

        return [
            [
                'url' => new \moodle_url('/auth/neesgov/login.php', $params),
                'icon' => new pix_icon('neesgov', get_string('pluginname', 'auth_neesgov'), 'auth_neesgov'),
                'name' => strip_tags(format_text('Logar com o GOV.BR')),
            ]
        ];
    }

    /**
     * This is the primary method that is used by the authenticate_user_login() function in moodlelib.php.
     *
     * @param string $username The username (with system magic quotes)
     * @param string $password The password (with system magic quotes)
     * @return bool Authentication success or failure.
     */
    public function user_login($username, $password = null)
    {
        global $CFG, $DB;
        // Short circuit for guest user.
        if (!empty($CFG->guestloginbutton) && $username === 'guest' && $password === 'guest') {
            return false;
        }

        $code = optional_param('code', null, PARAM_RAW);
        $tokenrec = $DB->get_record('auth_neesgov_token', ['username' => $username]);

        $userExists =  $DB->record_exists('user', ['username' => $username]);

        if (
            $userExists &&
            !empty($code) &&
            $tokenrec->authcode === $code
        ) {
            return true;
        }
        return false;

    }

    public function logoutpage_hook()
    {
        global  $CFG, $redirect, $USER;

        // Only do this if the user is actually logged in via neesgov
        if ($USER->auth == 'neesgov') {
            $redirect = $CFG->wwwroot . '/auth/neesgov/logout.php?pass=1';
        }
    }

}