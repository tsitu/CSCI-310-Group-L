<?php

require_once __DIR__ . '/../model/AccountManager.php';

$id = $_POST['id'];
$inst = $_POST['inst'];
$type = $_POST['type'];

$am = AccountManager::getInstance();
$am->updateAccount($id, $inst, $type);
