<?php
session_start();
session_destroy();
header ("Location: ".Constants::$ROOT_URL."admin/login");