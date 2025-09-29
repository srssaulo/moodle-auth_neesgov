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
 * gov br connect steps
 * @package     auth_neesgov
 * @copyright   2023 NEES/UFAL https://www.nees.ufal.br/
 * @author      Saulo Sá (srssaulo@gmail.com)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace auth_neesgov;

use Exception;

/**
 * <https://manual-roteiro-integracao-login-unico.servicos.gov.br/pt/stable/arquivos/ExemploIntegracaoGovBr.java>
 * Steps docs:
 * <https://manual-roteiro-integracao-login-unico.servicos.gov.br/pt/stable/iniciarintegracao.html>
 *
 * @author Saulo de Sá (srssaulo@gmail.com)
 */
class connect {

    /**
     * Define plugin table name
     */
    private const TOKEN_TABLE_NAME = 'auth_neesgov_token';

    /**
     * Response code time. See gov br documentation
     */
    private const RESPONSE_TYPE = 'code';


    /**
     * Define scopes. See gov br documentation
     */
    private const SCOPES = ['openid', 'email', 'profile'];// Escopos openid+email+profile+govbr_empresa+govbr_confiabilidades !

    /**
     * Response code challenge method. See gov br documentation
     */
    private const CODE_CHALLENGE_METHOD = "S256";

    /**
     * receive user info during connection
     * @var null
     */
    private $userinfo = null;

    /**
     * Get plugin setting vars
     * @return array
     * @throws \dml_exception
     */
    private static function get_config_vars() {
        return [
            'URL_PROVIDER' => get_config('auth_neesgov', 'uri_provider'),
            'REDIRECT_URI' => get_config('auth_neesgov', 'redirect_uri'),
            'POST_LOGOUT_REDIRECT_URI' => get_config('auth_neesgov', 'post_logout_uri'),
            'CLIENT_ID' => get_config('auth_neesgov', 'client_id'),
            'CLIENT_SECRET' => get_config('auth_neesgov', 'client_secret'),
        ];

    }

    /**
     * Authenticate method using OpenId Connect Client
     * @throws OpenIDConnectClientException
     */
    public function openidauthenticate() {

        $env = self::get_config_vars();

        $oidc = new OpenIDConnectClient(
            $env['URL_PROVIDER'],
            $env['CLIENT_ID'],
            $env['CLIENT_SECRET'],
        );

        $oidc->setRedirectURL($env['REDIRECT_URI']);

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

            $this->userinfo = $subs;

            $this->manageusercodes();
        }

    }

    /**
     * create or update record into auth_neesgov_token
     * @return void
     * @throws \dml_exception
     */
    private function manageusercodes() {
        global $DB, $CFG;

        $mdluserexists = $DB->get_record('user', ['username' => $this->userinfo->id], 'id');

        if (!$mdluserexists) {
            // Neesgov logout because user is login in gov.br!
            redirect(
                $CFG->wwwroot . '/auth/neesgov/logout.php?pass=1',
                'Usuário não cadastrado no Moodle',
                3,
                \core\output\notification::NOTIFY_ERROR);
        }

        $usertokenexists = $DB->get_record(self::TOKEN_TABLE_NAME, ['userid' => $mdluserexists->id], 'id');

        if (!$usertokenexists) {
            // Store!
            $DB->insert_record(self::TOKEN_TABLE_NAME,
                (object)[
                    'username' => $this->userinfo->id,
                    'userid' => $mdluserexists->id,
                    'authcode' => $this->userinfo->authcode,
                    'expiry' => $this->userinfo->expiry,
                    'picture' => $this->userinfo->picture,
                    'idtoken' => $this->userinfo->idtoken,
                ]);

        } else {
            // Update!
            $DB->update_record(self::TOKEN_TABLE_NAME,
                (object)[
                    'id' => $usertokenexists->id,
                    'userid' => $mdluserexists->id,
                    'authcode' => $this->userinfo->authcode,
                    'expiry' => $this->userinfo->expiry,
                    'picture' => $this->userinfo->picture,
                    'idtoken' => $this->userinfo->idtoken,
                ]);
        }
    }

    /**
     * When logout in moodle, either logout in gov.br
     * @return string
     */
    public static function logout_govbr() {

        $env = self::get_config_vars();

        $uriproviderlogout = $env['URL_PROVIDER'].'/logout';
        $postlogoutredirecturi = $env['POST_LOGOUT_REDIRECT_URI'];
        $action = $uriproviderlogout."?post_logout_redirect_uri=".$postlogoutredirecturi;
        return <<<HTML
        <script type="text/javascript">
            window.location.href='{$action}';
        </script>
HTML;

    }

    /**
     * get user info
     */
    public function getuserinfo() {
        return $this->userinfo;
    }

    /**
     * Verify if code is expired
     * @param int $expire
     * @return void
     * @throws Exception
     */
    public static function codeexpired(int $expire) {
        if (time() > $expire) {
            throw new Exception('code expired');
        }
    }
}
