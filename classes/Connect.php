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
    private const URL_SERVICOS = "https://api.staging.acesso.gov.br";
    private const URL_CATALOGO_SELOS = "https://confiabilidades.staging.acesso.gov.br";
    private const REDIRECT_URI = "https://ava.rieh-hmg.nees.ufal.br/auth/neesgov/login.php"; // redirectURI informada na chamada do serviço do
    private const SCOPES = "openid+profile+(phone/email)+govbr_empresa"; // Escopos openid+email+profile+govbr_empresa+govbr_confiabilidades
    private const CLIENT_ID = "ava.rieh-hmg.nees.ufal.br"; // clientId informado na chamada do serviço do authorize. //TODO deve ser uma conf do plugin
    private const SECRET = "HJA8&A&*@!#(HSADNA<SU10*&!n"; // secret de conhecimento apenas do backend da aplicação.

    private static string $CODE_CHALLENGE;
    private const CODE_CHALLENGE_METHOD = "S256";

    private static string $STATE;

    private string $uriAuthRequest;


    public function __construct()
    {
        self::setCodeChallange();
        self::setState();//optional
    }

    public function getAthorizeURI()
    {
        $URI = self::URL_PROVIDER;
        $URI .= "/authorize?response_type=code&client_id=".self::CLIENT_ID;
        $URI .= "&scope=".self::SCOPES;
        $URI .= "&redirect_uri=".urlencode(utf8_encode(self::REDIRECT_URI));
        $URI .= "&nonce=". Utils::get_nonce();
        $URI .= "&state=". self::getState();
        $URI .= "&code_challenge=". self::getCodeChallange();
        $URI .= "&code_challenge_method=". self::CODE_CHALLENGE_METHOD;

        return trim($URI);

    }


    private static function setState()
    {
        self::$STATE = Utils::get_state();
    }

    private static function setCodeChallange()
    {
        self::$CODE_CHALLENGE = Utils::code_challange();
    }

    /**
     * get code challange value
     * @return string
     */
    public function getCodeChallange()
    {
        return self::$CODE_CHALLENGE;
    }

    /**
     * valid state moodle value
     * @return string
     */
    public function getState(){
        return self::$STATE;
    }







}