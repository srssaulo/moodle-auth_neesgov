<?php

namespace auth_neesgov;

use auth_neesgov\httpclient;

class neesflow
{

    private httpclient $httpClient;
    private \stdClass $params;


    private function getTokenAndUserProfile()
    {
        return (object)[
            'accessToken' => optional_param('accessToken', null, PARAM_RAW_TRIMMED),
            'userProfile' => optional_param('userProfile', 0, PARAM_INT),
        ];
    }

    public function handleGetUserNeesDataResults()
    {
        if (empty($this->params)) {
            $this->params = $this->getTokenAndUserProfile();
        }
        $this->httpClient = new httpclient();
        $userNees = $this->httpClient->getUserNeesData($this->params); //in moodle cpf is username //TODO PAREI AQUI
        if ($userNees->status_code) {
            throw new \moodle_exception("status {$userNees->status_code}: $userNees->detail", 'auth_neesgov');
        }

        unset($userNees->dados);

        //return cpf only number and fullname
        return $userNees;
    }

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

        if ($mdlUser->auth != 'neesgov') {//change user auth type to neesgov
            $mdlUser->auth = 'neesgov';
            $DB->update_record('user', $mdlUser);
        }

        $user = authenticate_user_login($mdlUser->username, null, true);
        if (!empty($user)) {
            complete_user_login($user);
        } else {

            $eventdata = ['other' => ['username' => $mdlUser->username, 'reason' => AUTH_LOGIN_NOUSER]];
            $event = \core\event\user_login_failed::create($eventdata);
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