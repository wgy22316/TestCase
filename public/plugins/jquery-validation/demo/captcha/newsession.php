<?php

// Include the random string file
require '../assets/plugins/jquery-validation/demo/captcha/rand.php';

// Begin a new session
session_start();

// Set the session contents
$_SESSION['captcha_id'] = $str;

?>