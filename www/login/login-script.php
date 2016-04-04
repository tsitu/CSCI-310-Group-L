<?php

if ( session_id() == '' || !isset($_SESSION) ) session_start();

$username = $_POST['username'];
$password = $_POST['password'];

//check for empty fields
if ( empty($username) || empty($password) )
{
    $_SESSION['errors'] = 'Email or password cannot be empty';
    header('Location: /login.php');
    exit();
}


//check against db for login
