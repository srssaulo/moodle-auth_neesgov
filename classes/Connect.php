<?php

namespace auth_neesgov;

use enrol_self\self_test;
use Exception;

/**
 * Implements 3 steps Gov.br authorization service.
 * Class was implemented based in following sample:
 * <https://manual-roteiro-integracao-login-unico.servicos.gov.br/pt/stable/arquivos/ExemploIntegracaoGovBr.java>
 * Steps docs:
 * <https://manual-roteiro-integracao-login-unico.servicos.gov.br/pt/stable/iniciarintegracao.html
 *
 * @author Saulo de Sá <srssaulo@gmail.com>
 */
class Connect
{

    private const TOKEN_TABLE_NAME = 'auth_neesgov_token';
    private const URL_PROVIDER = "https://sso.staging.acesso.gov.br";
    private const URL_PROVIDER_LOGOUT = "https://sso.staging.acesso.gov.br/logout";

    private const RESPONSE_TYPE = 'code';
//    private const URL_SERVICOS = "https://api.staging.acesso.gov.br";
//    private const URL_CATALOGO_SELOS = "https://confiabilidades.staging.acesso.gov.br";
    private const REDIRECT_URI = "https://ac.ava.rieh-hmg.nees.ufal.br/auth/neesgov/login.php"; // redirectURI informada na chamada do serviço do

    private const POST_LOGOUT_REDIRECT_URI = "https://ac.ava.rieh-hmg.nees.ufal.br/auth/neesgov/logout.php";

    private const SCOPES = ['openid', 'email', 'profile']; // Escopos openid+email+profile+govbr_empresa+govbr_confiabilidades
    private const CLIENT_ID = "ac.ava.rieh-hmg.nees.ufal.br"; // clientId informado na chamada do serviço do authorize. //TODO deve ser uma conf do plugin
    private const CLIENT_SECRET = "ANvI5Pt6ETw_G7I2xCuqecJeqrJk7MFa8K0moLkRxrMs_YkNbXgzdTj_-mTxxLRuHRFFnKMkxgfF_uGS-KurIOg"; //TODO deve ser uma conf do plugin

    private const CODE_CHALLENGE_METHOD = "S256";


    private $userInfo = null;


    /**
     * Authenticate method using OpenId Connect Client
     * @throws OpenIDConnectClientException
     */
    public function OpenIDAuthenticate()
    {
        $oidc = new OpenIDConnectClient(
            self::URL_PROVIDER,
            self::CLIENT_ID,
            self::CLIENT_SECRET
        );

        $oidc->setRedirectURL(self::REDIRECT_URI);


        $oidc->setResponseTypes(self::RESPONSE_TYPE);


        $oidc->addScope(self::SCOPES);


        $oidc->setCodeChallengeMethod(self::CODE_CHALLENGE_METHOD);


        if ($oidc->authenticate() && isset($_REQUEST['code'])) {
            $subs = (object)[
                'id' => $oidc->requestUserInfo('sub'),
                'email' => $oidc->requestUserInfo('email'),
                'name' => $oidc->requestUserInfo('name'),
                'picture' => $oidc->requestUserInfo('picture'),
                'idtoken' => $oidc->getIdToken(),
                'authcode' => $_REQUEST['code'],
                'expiry' => $oidc->getVerifiedClaims('exp'),
            ];

            $this->userInfo = $subs;

            $this->manageUserCodes();
        }

    }

    /**
     * create or update record into auth_neesgov_token
     * @return void
     * @throws \dml_exception
     */
    private function manageUserCodes()
    {
        global $DB;

        /**
         * stdClass Object
         * (
         * [id] => 06881435479
         * [email] => saulo.rufino@nees.ufal.br
         * [name] => Saulo Sa
         * [picture] => https://sso.staging.acesso.gov.br/userinfo/picture
         * [idtoken] => eyJraWQiOiJyc2ExIiwiYWxnIjoiUlMyNTYifQ.eyJzdWIiOiIwNjg4MTQzNTQ3OSIsImVtYWlsX3ZlcmlmaWVkIjoidHJ1ZSIsImFtciI6WyJwYXNzd2QiXSwicHJvZmlsZSI6Imh0dHBzOlwvXC9zZXJ2aWNvcy5zdGFnaW5nLmFjZXNzby5nb3YuYnJcLyIsImtpZCI6InJzYTEiLCJpc3MiOiJodHRwczpcL1wvc3NvLnN0YWdpbmcuYWNlc3NvLmdvdi5iclwvIiwicHJlZmVycmVkX3VzZXJuYW1lIjoiMDY4ODE0MzU0NzkiLCJub25jZSI6IjYwNjdkOTY0ZmQzMzgxZjJlNWMyNWI0NTAxY2E5NGVjIiwicGljdHVyZSI6Imh0dHBzOlwvXC9zc28uc3RhZ2luZy5hY2Vzc28uZ292LmJyXC91c2VyaW5mb1wvcGljdHVyZSIsImF1ZCI6ImFjLmF2YS5yaWVoLWhtZy5uZWVzLnVmYWwuYnIiLCJhdXRoX3RpbWUiOjE2ODg1MDIyNzIsInNjb3BlIjpbIm9wZW5pZCIsInByb2ZpbGUiLCJlbWFpbCJdLCJuYW1lIjoiU2F1bG8gU2EiLCJleHAiOjE2ODg1MDQ3ODMsImlhdCI6MTY4ODUwNDE4MywianRpIjoiNDQ3ZTQ4MWEtNDFjZi00ZTIwLTgwZmMtNTE0MjQ2ZDQ4MWI0IiwiZW1haWwiOiJzYXVsby5ydWZpbm9AbmVlcy51ZmFsLmJyIn0.p904kUDMyDWdzqfTVj_34J4ID28AaTmmOZYJWShZREKpTrhC_0sGj5nudZfMBUChTs3I2d8NDAeI5XV47bjrgJPIDkIDSQQWK_nQaMdivdJZ4Hjix9RTus4z1775fAJj6Mq2PFBBgf8EJ0yIrHTvEj2V3txViHazgQX8ar_5JvqDPxuIWMfGF3MDT44-W5r62-WQXM-De6UTqL4ThgXDykbicD0sC9MWSxWx3YIV0HrddYo5z6pa4HQx20Hdxo6LvMpLVW_9xSmB8G5M1Hwc1mDGHcmC6JahElYzKq2db002Mm4qjjw22D_fvhbSh-7TRvXtnJE3BR_nYEytdoc5wA
         * [authcode] => eyJraWQiOiJjb2RlQ3J5cHRvZ3JhcGh5IiwiYWxnIjoiZGlyIiwiZW5jIjoiQTI1NkdDTSJ9..ABpxCgolERzIZyYs.fBQ6zH04R7pS5Pj9fAkJF0jBfuKQF9upaZZfjD9Zk10LGQ.7akSFi7MoicT02yY6R9RyA
         * [expiry] => 1688504783
         * )
         */

        $mdlUserExists = $DB->get_record('user', ['username' => $this->userInfo->id], 'id');

        if (!$mdlUserExists) {
            throw new Exception('Usuário não cadastrado no Moodle');
        }

        $userTokenExists = $DB->get_record(self::TOKEN_TABLE_NAME, ['userid' => $mdlUserExists->id], 'id');

        if (!$userTokenExists) {
            // Store
            $DB->insert_record(self::TOKEN_TABLE_NAME,
                (object)[
                    'username'=>$this->userInfo->id,
                    'userid'=>$mdlUserExists->id,
                    'authcode'=>$this->userInfo->authcode,
                    'expiry'=>$this->userInfo->expiry,
                    'picture'=>$this->userInfo->picture,
                    'idtoken'=>$this->userInfo->idtoken,
                ]);

        } else {
            // Update
            $DB->update_record(self::TOKEN_TABLE_NAME,
                (object)[
                    'id'=>$userTokenExists->id,
//                    'username'=>$this->userInfo->username,
                    'userid'=>$mdlUserExists->id,
                    'authcode'=>$this->userInfo->authcode,
                    'expiry'=>$this->userInfo->expiry,
                    'picture'=>$this->userInfo->picture,
                    'idtoken'=>$this->userInfo->idtoken,
                ]);
        }


    }

    public static function logout_govbr(){
        echo 'Logout Moodle '; die;
        $redirect_uri = self::POST_LOGOUT_REDIRECT_URI;
        $logout_request = self::REDIRECT_URI;
        $action = $redirect_uri."?post_logout_redirect_uri=".$logout_request;
        return <<<HTML
        <script>
       
        var form = document.createElement("form");
        form.setAttribute("method", "post");
        form.setAttribute("action", "{$action}");
        document.body.appendChild(form);
        form.submit();
</script>
    
HTML;

    }


    public function getUserInfo()
    {
        return $this->userInfo;
    }


    /**
     * Verify if code is expired
     * @param int $expire
     * @return void
     * @throws Exception
     */
    public static function codeExpired(int $expire)
    {
        if (time() > $expire) {
            throw new Exception('code expired');
        }

    }


}