<?php

require_once 'auth.php';

Autorizacija::logout();


session_start();
$_SESSION['role'] = 'guest';
$_SESSION['username'] = 'Gost';

header("Location: index.php");
exit;
exit();
