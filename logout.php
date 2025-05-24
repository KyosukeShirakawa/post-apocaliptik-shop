<?php
session_start();
include_once("userStorage.php");
include_once("auth.php");

$auth = new Auth(new UserStorage());
$auth->logout();

header("Location: login.php");
exit();
