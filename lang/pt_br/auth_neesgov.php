<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Plugin for gov br authentication.
 * pt_br translation.
 * @package     auth_neesgov
 * @copyright   2023 NEES/UFAL https://www.nees.ufal.br/
 * @author      Saulo Sá (srssaulo@gmail.com)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
$string['auth_neesgovdescription'] = 'Plugin de autenticação com o Gov.br';
$string['auth_type_change'] = 'Mudar para autenticação manual após login';
$string['auth_type_change_desc'] = 'Altera o tipo de autenticação para manual após autenticar com o neesgov (gov.br)';
$string['btn_gov_login'] = "Entrar com o gov.br";
$string['client_id'] = 'Client ID';
$string['client_id_desc'] = 'Client ID previamente definida com o Gov.br.';
$string['client_secret'] = 'Client secret';
$string['client_secret_desc'] = 'Client secret previamente definida com o Gov.br.';
$string['evt_neesgov_login'] = 'Login com Gov.br';
$string['evt_neesgov_login_description'] = 'O usuário com id \'{$a->userid}\' logou usando o Gov.br';
$string['generaldesc'] = 'Variáveis de configuração do plugin de autenticação Nees Gov.br';
$string['login_fail'] = 'Falha de login.';
$string['moduleid'] = 'Nees id do módulo';
$string['moduleid_desc'] = 'Nees id do módulo da repositório da aplicação ';
$string['plugindescription'] = 'Plugin de autenticação que permite a auteticação dos usuários com suas credenciais no fornecedor gv.br.';
$string['pluginname'] = 'Nees Gov.br';
$string['post_logout_uri'] = 'Depois do logout, redirect URI';
$string['post_logout_uri_desc'] = 'Previamente definida com o Gov.br. Deve ser uma URI previamente acordada.';
$string['privacy:metadata'] = 'O plugin de autenticação neesgov não guarda dados novos ou permanentes dos usuários. O plugin acessa dados já existentes no Moodle além de armazenar dados temporários de acesso.';
$string['redirect_uri'] = 'Pós authorize, redirect URI';
$string['redirect_uri_desc'] = 'Previamente definida com o Gov.br. Deve ser uma URI previamente acordada.';
$string['uri_provider'] = 'Provider URI';
$string['uri_provider_desc'] = 'URL base do fornecedor. Composta por requisições Authorize e Logout . <b>Obs</b>: o valor padrão é <b>staging</b>';
$string['user_not_registred'] = 'Usuário não cadastrado no Moodle. Você precisa ser cadastrado primeiro no Moodle.';