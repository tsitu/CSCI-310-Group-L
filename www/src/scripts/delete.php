<?php

require_once __DIR__ . '/../model/AccountManager.php';

$id = $_POST['id'];

$am = AccountManager::getInstance();
$am->deleteAccount($id);

echo 'success';