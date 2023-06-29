<?php

namespace auth_neesgov;

class Utils
{

    public static function get_nonce()
    {
        //echo \core\session\manager::get_login_token();
//        echo \core\session\manager::validate_login_token()
        //Sequência de caracteres usado para associar uma sessão do serviço consumidor
        //a um Token de ID e para atenuar os ataques de repetição. Pode ser um valor
        //aleatório, mas que não seja de fácil dedução. Item obrigatório.
        return sesskey();
    }


    public static function get_state()
    {
        //Parâmetro STATE deve obrigatoriamente ser usado e deve ser validado no cliente
        //(validado que foi previamente emitido pelo cliente)
        return \core\session\manager::get_login_token();

    }

    public static function validate_state($state)
    {
        return \core\session\manager::validate_login_token($state);
    }

    public static function get_redirect_uri(){

    }


    private static function base64url_encode($plainText){
        $base64 = base64_encode($plainText);
        $base64 = trim($base64, "=");
        $base64url = strtr($base64, '+/', '-_');
        return ($base64url);
    }

    public static function code_challange(): string
    {
        //https://devforum.okta.com/t/how-to-do-pkce-challenge-in-php/553
        //  https://medium.com/zenchef-tech-and-product/how-to-generate-a-pkce-challenge-with-php-fbee1fa29379
        //second alternative:
//        https://github.com/aweber/public-api-examples/blob/c28945cb419805bab30b4db9db41baf0df4338c8/php/get-pkce-access-token#L27

        $random = bin2hex(openssl_random_pseudo_bytes(32));
        $verifier = self::base64url_encode(pack('H*', $random));
        $challenge = self::base64url_encode(pack('H*', hash('sha256', $verifier)));
        return $challenge;

    }


}