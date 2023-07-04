<?php

namespace auth_neesgov;

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

    private const URL_PROVIDER = "https://sso.staging.acesso.gov.br";

    private const RESPONSE_TYPE = 'code';
//    private const URL_SERVICOS = "https://api.staging.acesso.gov.br";
//    private const URL_CATALOGO_SELOS = "https://confiabilidades.staging.acesso.gov.br";
    private const REDIRECT_URI = "https://ac.ava.rieh-hmg.nees.ufal.br/auth/neesgov/login.php"; // redirectURI informada na chamada do serviço do
    private const SCOPES = ['openid','email', 'profile']; // Escopos openid+email+profile+govbr_empresa+govbr_confiabilidades
    private const CLIENT_ID = "ac.ava.rieh-hmg.nees.ufal.br"; // clientId informado na chamada do serviço do authorize. //TODO deve ser uma conf do plugin
    private const CLIENT_SECRET = "ANvI5Pt6ETw_G7I2xCuqecJeqrJk7MFa8K0moLkRxrMs_YkNbXgzdTj_-mTxxLRuHRFFnKMkxgfF_uGS-KurIOg"; //TODO deve ser uma conf do plugin

    private const CODE_CHALLENGE_METHOD = "S256";


    private  $userInfo = null;


    /**
     * Authenticate method using OpenId Connect Client
     * @throws OpenIDConnectClientException
     */
    public function OpenIDAuthenticate(){
        $oidc = new OpenIDConnectClient(
            self::URL_PROVIDER,
            self::CLIENT_ID,
            self::CLIENT_SECRET
        );

        $oidc->setRedirectURL(self::REDIRECT_URI);


        $oidc->setResponseTypes(self::RESPONSE_TYPE);


        $oidc->addScope(self::SCOPES);


        $oidc->setCodeChallengeMethod(self::CODE_CHALLENGE_METHOD);


        if($oidc->authenticate() && isset($_REQUEST['code'])){
            $subs = (object)[
                'id'=>$oidc->requestUserInfo('sub'),
                'email'=>$oidc->requestUserInfo('email'),
                'name'=>$oidc->requestUserInfo('name'),
                'picture'=>$oidc->requestUserInfo('picture'),
                'idtoken'=>$oidc->getIdToken(),
                'authcode'=>$_REQUEST['code'],
                'expiry'=>$oidc->getVerifiedClaims('exp'),
            ];

            $this->userInfo = $subs;

        }

    }

    public function getUserInfo(){
        return $this->userInfo;
    }


}