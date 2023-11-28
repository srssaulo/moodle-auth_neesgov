<?php

namespace auth_neesgov;

use core\event\user_login_failed;

/**
 * @package auth_neesgov
 * @copyright 2023 Saulo Sá <srssaulo@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */
class neesflow
{




    /**
     * @param object $userInfo {'id'=>$oidc->requestUserInfo('sub'),
                                'email'=>$oidc->requestUserInfo('email'),
                                'name'=>$oidc->requestUserInfo('name'),
                                'picture}
     * @return void
     */
    private function handlelogin($userInfo)
    {
        global $DB;


        // Do not continue if auth plugin is not enabled.
        if (!is_enabled_auth('neesgov')) {
            throw new moodle_exception('erroroidcnotenabled', 'auth_neesgov', null, null, '1');
        }


        $mdlUser = $DB->get_record('user', ['username' => trim($userInfo->id), 'deleted' => 0]);

        if (!$mdlUser) {
            throw new \moodle_exception('User doesn\'t created in moodle', 'auth_neesgov');
        }

//        if ($mdlUser->auth != 'neesgov') {//change user auth type to neesgov
            $mdlUser->auth = ''; //if empty them manual is set
            $DB->update_record('user', $mdlUser);
//        }

        if($userInfo->email != $mdlUser->email){ //user\'s email update
            $mdlUser->email = $userInfo->email;
        }

        //updating first and last name gov.br
        $gov_firstname = strtok($userInfo->name, " ");
        $gov_lastname = strtok(null);

        if($mdlUser->firstname != $gov_firstname){
            $mdlUser->firstname = $gov_firstname;
        }
        if($mdlUser->lastname != $gov_lastname){
            $mdlUser->lastname = $gov_lastname;
        }

        //updating mdl user
        $DB->update_record('user', $mdlUser);


        $user = authenticate_user_login($mdlUser->username, null, true);
        if (!empty($user)) {
            if(get_user_preferences('auth_forcepasswordchange', null, $user)){
                set_user_preference('auth_forcepasswordchange', 0, $user);
            }
            complete_user_login($user);
        } else {

            $eventdata = ['other' => ['username' => $mdlUser->username, 'reason' => AUTH_LOGIN_NOUSER]];
            $event = user_login_failed::create($eventdata);
            $event->trigger();

            // There was a problem in authenticate_user_login.
            throw new \moodle_exception('errorauthgeneral', 'auth_neesgov', null, null, '2');
        }

    }


    /**
     * @param object $userInfo {'id'=>$oidc->requestUserInfo('sub'),
                                'email'=>$oidc->requestUserInfo('email'),
                                'name'=>$oidc->requestUserInfo('name'),
                                'picture}
     * @return void
     * @throws \moodle_exception
     */
    public function handleRedirect($userInfo)
    {

        if(is_null($userInfo)){
            //if null  didn't make login correctly
            //return to login page
            redirect(new \moodle_url('/login'), 'login fail');
        }

            $this->handlelogin($userInfo);

            //its all right and user is redirected to dashboardo Moodle
            redirect(new \moodle_url('/my'));

        }


}