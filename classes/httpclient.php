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
 * HTTP client. And GOV.BR Nees flow
 *
 * @package auth_neesgov
 * @author Saulo Sá <srssaulo@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright 2023 Saulo Sá <srssaulo@gmail.com>
 */

namespace auth_neesgov;

require_once($CFG->dirroot . '/lib/filelib.php');

class httpclient extends \curl
{


    public function handleRedirect(){
        global $CFG, $SESSION;



//        $redirecturl = new \moodle_url('/', []);
        $redirecturl = 'https://develop-login-integracao-dot-scanner-prova.rj.r.appspot.com/login';
//        $redirecturl = 'https://localhostcomtoken';

        redirect($redirecturl);
    }


    /**
     * HTTP POST method.
     *
     * abtration to post parent curl method
     * @param string $url
     * @param array|string $params
     * @param array $options
     * @return bool
     */
    public function post($url, $params = '', $options = array()) {
        // Encode data to disable uploading files when values are prefixed @.
        if (is_array($params)) {
            $params = http_build_query($params, '', '&');
        }
        return parent::post($url, $params, $options);
    }


}