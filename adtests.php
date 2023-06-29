<?php
require "../../config.php";

use auth_neesgov\Connect;

$cn = new Connect();

//echo $cn->getAthorizeURI();

$cn->OpenIDAuthenticate();