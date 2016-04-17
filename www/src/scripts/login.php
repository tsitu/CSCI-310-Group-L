<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/UserManager.php";

session_start();

$username = $_POST['username'];
$password = $_POST['password'];


//check for empty fields
if ( empty($username) || empty($password) )
{
    $_SESSION['error'] = 'Empty username or password';
    header('Location: /login');
    exit();
}


//check against db for login
$user_id = UserManager::getInstance()->verify($username, $password);
if (!$user_id)
{
    $_SESSION['error'] = 'Invalid login parameters';
    header('Location: /login');
    exit();
}


//set login session
$_SESSION['user_id'] = $user_id;
$_SESSION['username'] = $username;

header('Location: /');
exit();