<?php

namespace auth_neesgov;

use auth_neesgov\httpclient;
class neesflow
{

    private httpclient $httpClient;
    private \stdClass $params;


    private function getTokenAndUserProfile(){
        return (object)[
            'accessToken'=>optional_param('accessToken', null, PARAM_RAW_TRIMMED),
            'userProfile'=>optional_param('userProfile', 0, PARAM_INT),
        ];
    }

    public function handleGetUserNeesDataResults(){
        if(empty($this->params)){
            $this->params = $this->getTokenAndUserProfile();
        }
        $this->httpClient =  new httpclient();
        $userNees = $this->httpClient->getUserNeesData($this->params); //in moodle cpf is username //TODO PAREI AQUI
        if($userNees->status_code){
            throw new \moodle_exception("status {$userNees->status_code}: $userNees->detail", 'auth_neesgov');
        }

        unset($userNees->dados);

        //return cpf only number and fullname
        return $userNees;
    }

    /**
     * @param \stdClass $params atts: accesstoken, userProfile
     * @return void
     */
    private function handlelogin(){
        global $DB;
        //look oidc authcode.php->handlelogin()

        // Do not continue if auth plugin is not enabled.
        if (!is_enabled_auth('neesgov')) {
            throw new moodle_exception('erroroidcnotenabled', 'auth_neesgov', null, null, '1');
        }

        $neesUser = $this->handleGetUserNeesDataResults();
        $mdlUser = $DB->get_record('user', ['username'=>trim($neesUser->cpf), 'deleted'=>0]);

        if(!$mdlUser){
            throw new \moodle_exception('User doesn\'t created in moodle', 'auth_neesgov');
        }

        if($mdlUser->auth!='neesgov'){//change user auth type to neesgov
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

    public function handleRedirect(){

        $redirect_url = trim(get_config('auth_neesgov', 'redirecturl'));

        if(!empty($redirect_url)){
            $dev_login_redirect = new \moodle_url($redirect_url);
        }else{
            $dev_login_redirect = new \moodle_url("https://develop-login-integracao-dot-scanner-prova.rj.r.appspot.com/login");
        }


        $this->params = $this->getTokenAndUserProfile();
        if(!is_null($this->params->accessToken) and  $this->params->userProfile!==0) {

            $this->handlelogin();

            //its all right and user is redirected to dashboardo Moodle
            redirect(new \moodle_url('/my'));

        }else{
            redirect($dev_login_redirect);
        }
    }


}