<?php

require_once __DIR__ . '/../model/UserManager.php';

$username = 'test@gmail.com';
$password = 'test';

$um = UserManager::getInstance();
$um->addUser($username, $password);