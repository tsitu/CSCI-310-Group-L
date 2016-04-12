<?php

require_once __DIR__ . '/../model/DBManager.php';

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
$manager = new DBManager();
$uid = $manager->getUser($username, $password);
if ( is_null($uid) )
{
    $_SESSION['error'] = 'Invalid login parameters';
    header('Location: /login');
    exit();
}


//set login session
$_SESSION['user_id'] = $uid;
$_SESSION['username'] = $username;

header('Location: /');
exit();