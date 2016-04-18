<?php

require_once "../model/UserManager.php";

$username = 'test@gmail.com';
$password = 'test';

$um = UserManager::getInstance();
$um->addUser($username, $password);