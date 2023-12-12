<?php

namespace auth_neesgov\event;
defined('MOODLE_INTERNAL') || die();

use core\event\base;

class neesgov_login extends base
{

    protected function init()
    {
        // TODO: Implement init() method.

        $this->data['crud'] = 'r';
        $this->data['edulevel'] = self::LEVEL_OTHER;
        $this->data['objecttable'] = 'user';
        $this->context = \context_system::instance();
    }

    public static function get_name() {
        return get_string('evt_neesgov_login', 'auth_neesgov');
    }

    public function get_description() {
        return get_string('evt_neesgov_login_description', 'auth_neesgov', ['userid'=>$this->userid, 'objectid' => $this->objectid]);
    }

}