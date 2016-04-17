<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/AccountManager.php";

$id = $_POST['id'];

$am = AccountManager::getInstance();
$am->deleteAccount($id);