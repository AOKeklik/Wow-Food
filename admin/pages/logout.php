<?php
require_once ("../load.php");

session_destroy();
header ("Location: ".Constants::$ROOT_URL."admin/login");