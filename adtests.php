<?php
require "../../config.php";
require  $CFG->dirroot.'/auth/neesgov/classes/Connect.php'; //TODO put in general place

use auth_neesgov\Connect;

$cn = new Connect();

//echo $cn->getAthorizeURI();

//$cn->OpenIDAuthenticate();

echo Connect::logout_govbr();