<?php

require_once __DIR__ . '/../inc/queries.php';

session_start();

$username = $_POST['username'];
$password = $_POST['password'];

//check for empty fields
if ( empty($username) || empty($password) )
{
    $_SESSION['error'] = 'Empty username or password';
    header('Location: /CSCI-310-Group-L/www/login');
    exit();
}


//check against db for login
$uid = login($username, $password);
if ( is_null($uid) )
{
    $_SESSION['error'] = 'Invalid login parameters';
    header('Location: /CSCI-310-Group-L/www/login');
    exit();
}


//set login session
$_SESSION['user_id'] = $uid;
$_SESSION['username'] = $username;

header('Location: ../../index.php');
exit();