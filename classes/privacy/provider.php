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
 * nuul provider policy
 * @package     auth_neesgov
 * @copyright   2023 NEES/UFAL https://www.nees.ufal.br/
 * @author      Saulo Sá (srssaulo@gmail.com)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace auth_neesgov\privacy;

use core_privacy\local\metadata\collection;

/**
 * Declare privacy class with null provider reason
 */
class privacy implements \core_privacy\local\metadata\provider {

    public static function get_metadata(collection $collection): collection {
        $collection->add_database_table(
            'auth_neesgov_token',
            [
                'userid' => 'privacy:metadata:auth_neesgov_token:userid',
                'username' => 'privacy:metadata:auth_neesgov_token:username',
                'authcode' => 'privacy:metadata:auth_neesgov_token:authcode',
                'expiry' => 'privacy:metadata:auth_neesgov_token:expiry',
                'picture' => 'privacy:metadata:auth_neesgov_token:picture',
                'idtoken' => 'privacy:metadata:auth_neesgov_token:idtoken',
            ],
            'privacy:metadata:auth_neesgov_token'
        );


        return $collection;
    }
}
