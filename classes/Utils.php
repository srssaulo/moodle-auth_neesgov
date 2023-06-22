<?php

namespace auth_neesgov;

class Utils
{

    public static function get_nonce(){
        //echo \core\session\manager::get_login_token();
//        echo \core\session\manager::validate_login_token()
        //Sequência de caracteres usado para associar uma sessão do serviço consumidor
        //a um Token de ID e para atenuar os ataques de repetição. Pode ser um valor
        //aleatório, mas que não seja de fácil dedução. Item obrigatório.
        return sesskey();
    }


    public static function get_state(){
        //Parâmetro STATE deve obrigatoriamente ser usado e deve ser validado no cliente
        //(validado que foi previamente emitido pelo cliente)

        return \core\session\manager::get_login_token();

    }

    public static function validate_state($state){
        return \core\session\manager::validate_login_token($state);
    }


    public static function code_challange(){
        //https://devforum.okta.com/t/how-to-do-pkce-challenge-in-php/553
//        https://medium.com/zenchef-tech-and-product/how-to-generate-a-pkce-challenge-with-php-fbee1fa29379

    }



}