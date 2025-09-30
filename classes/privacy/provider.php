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
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\contextlist;

/**
 * Declare privacy class with null provider reason
 */
class provider implements \core_privacy\local\metadata\provider,
    \core_privacy\local\request\plugin\provider {

    /**
     * Get information about the user data stored by this plugin.
     *
     * @param collection $collection An object for storing metadata.
     * @return collection The metadata.
     */
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

    /**
     * Delete all user data for this user only.
     *
     * @param approved_contextlist $contextlist The list of approved contexts for a user.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        if (empty($contextlist->count())) {
            return;
        }
        $userid = $contextlist->get_user()->id;
        foreach ($contextlist->get_contexts() as $context) {
            if ($context->contextlevel != CONTEXT_USER) {
                continue;
            }
            if ($context->instanceid == $userid) {
                // Because we only use user contexts the instance ID is the user ID.
                static::delete_user_data($context->instanceid);
            }
        }
    }

    /**
     * Delete one user in context
     *
     *
     * @param  \context $context The context to delete data for.
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
    }

    /**
     * Return all contexts for this userid. In this situation the user context.
     *
     * @param  int $userid The user ID.
     * @return contextlist The list of context IDs.
     */
    public static function get_contexts_for_userid(int $userid): contextlist {
        $sql = "SELECT ctx.id
                  FROM {auth_neesgov_token} an
                  JOIN {context} ctx ON ctx.instanceid = an.userid AND ctx.contextlevel = :contextlevel
                 WHERE an.userid = :userid";
        $params = ['userid' => $userid, 'contextlevel' => CONTEXT_USER];
        $contextlist = new contextlist();
        $contextlist->add_from_sql($sql, $params);

        return $contextlist;
    }

    /**
     *
     * Nothing to export
     *
     * @param  approved_contextlist $contextlist The list of approved contexts for a user.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
    }

    /**
     * This does the deletion of user data for the auth_neesgov.
     *
     * @param int $userid The user ID
     */
    protected static function delete_user_data(int $userid) {
        global $DB;

        // Because we only use user contexts the instance ID is the user ID.
        $DB->delete_records('auth_neesgov_token', ['userid' => $userid]);
    }

}
