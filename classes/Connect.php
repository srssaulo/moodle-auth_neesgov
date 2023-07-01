<?php

namespace auth_neesgov;

use Jumbojett\OpenIDConnectClient;
use Jumbojett\OpenIDConnectClientException;

require  $CFG->dirroot.'/auth/neesgov/vendor/autoload.php'; //TODO put in general place

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
    private const URL_SERVICOS = "https://api.staging.acesso.gov.br";
    private const URL_CATALOGO_SELOS = "https://confiabilidades.staging.acesso.gov.br";
    private const REDIRECT_URI = "https://rieh-hmg.nees.ufal.br/auth/neesgov/login.php"; // redirectURI informada na chamada do serviço do
    private const SCOPES = ['openid','email', 'profile']; // Escopos openid+email+profile+govbr_empresa+govbr_confiabilidades
    private const CLIENT_ID = "ac.ava.rieh-hmg.nees.ufal.br"; // clientId informado na chamada do serviço do authorize. //TODO deve ser uma conf do plugin
    private const CLIENT_SECRET = "ANvI5Pt6ETw_G7I2xCuqecJeqrJk7MFa8K0moLkRxrMs_YkNbXgzdTj_-mTxxLRuHRFFnKMkxgfF_uGS-KurIOg"; //TODO deve ser uma conf do plugin

    private const CODE_CHALLENGE_METHOD = "S256";


    public function __construct()
    {

    }


    /**
     * Authenticate method using OpenId Connect Client
     * @return void
     * @throws OpenIDConnectClientException
     */
    public function OpenIDAuthenticate(){
        $oidc = new OpenIDConnectClient(
            self::URL_PROVIDER,
            self::CLIENT_ID,
            self::CLIENT_SECRET
        );

//        $codeVerifier = bin2hex(random_bytes(64));
//
//        //force code challenge in code request
//        $oidc->addAuthParam([
//            'code_challenge_method'=>self::CODE_CHALLENGE_METHOD,
//            'code_challenge'=>rtrim(strtr(base64_encode(hash('sha256', $codeVerifier, true)), '+/', '-_'), '=')
//        ]);

        $oidc->setResponseTypes(self::RESPONSE_TYPE);


        $oidc->addScope(self::SCOPES);


//        $oidc->setRedirectURL(self::REDIRECT_URI); //if not was set, return to origin

        $oidc->setCodeChallengeMethod(self::CODE_CHALLENGE_METHOD);


        $oidc->authenticate(); //aqui eu pego o code

       print_object($oidc->requestUserInfo('sub'));
//
//        print_object($oidc->requestUserInfo('sub'));
//        print_object($oidc->getVerifiedClaims('sub'));


//        print_object($sub);
        //aqui o access token
//       echo  $oidc->requestClientCredentialsToken()->access_token;
//        echo $oidc->requestClientCredentialsToken();
//       echo  $oidc->getProviderURL();
//       echo $oidc->getResponseCode();
//        $oidc->authenticate();


    }


}