<?php

namespace auth_neesgov;

use auth_neesgov\httpclient;
class neesflow
{

    private neesflow $httpClient;


    private function getTokenAndUserProfile(){
        return (object)[
            'accesstoken'=>optional_param('accesstoken', null, PARAM_RAW_TRIMMED),
            'userProfile'=>optional_param('userProfile', 0, PARAM_INT),
        ];
    }


    /**
     * @param \stdClass $params atts: accesstoken, userProfile
     * @return void
     */
    public function handlelogin($params){
        //look oidc authcode.php->handlelogin()

        // Do not continue if auth plugin is not enabled.
        if (!is_enabled_auth('neesgov')) {
            throw new moodle_exception('erroroidcnotenabled', 'auth_oidc', null, null, '1');
        }



        $this->httpClient =  new httpclient();
        $username = $this->httpClient->getUserCPF($params); //in moodle cpf is username //TODO PAREI AQUI


//        $username = $user->username;
//        $this->updatetoken($tokenrec->id, $authparams, $tokenparams);
//        $user = authenticate_user_login($username, null, true);
//
//        if (!empty($user)) {
//            complete_user_login($user);
//        } else {
//            // There was a problem in authenticate_user_login.
//            throw new moodle_exception('errorauthgeneral', 'auth_oidc', null, null, '2');
//        }
//
//
    }


    public function handleRedirect(){

        //TODO deve ser colocado no settings do plugin
        $dev_redirect_original = "https://develop-login-integracao-dot-scanner-prova.rj.r.appspot.com/login";


        //URL de testes direto para autenticar. accesstoken e userProfile
        //Este link deve ser enviado de volta (callback) para esta url do plugin com os atributos accesstoken e userProfile
        $params = $this->getTokenAndUserProfile();
        if(!is_null($params->accesstoken) and  $params->userProfile!==0) {
            //TODO token validation. HTTP REQUEST
//            $this->httpClient =  new httpclient();

            $this->handlelogin($params);

            $dev_redirect_tests = new \moodle_url("http://localhost:8080/auth/neestools/login.php", $params);
            redirect($dev_redirect_tests);
        }else{
            redirect($dev_redirect_original);
        }
    }


}