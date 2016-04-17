<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/AccountManager.php";

$id = $_POST['id'];
$inst = $_POST['inst'];
$type = $_POST['type'];

$am = AccountManager::getInstance();
$am->updateAccount($id, $inst, $type);
