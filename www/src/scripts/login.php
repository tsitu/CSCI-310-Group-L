<?php

require_once __DIR__ . '/../inc/queries.php';

session_start();

////check for empty fields
if ( empty($_POST['username']) || empty($_POST['password']) )
{
    $_SESSION['error'] = 'Email or password cannot be empty';
    header('Location: /login');
    exit();
}


//check against db for login
