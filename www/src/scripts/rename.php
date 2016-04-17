<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/AccountManager.php";

session_start();

$id = $_POST['id'];
$inst = $_POST['inst'];
$type = $_POST['type'];
$user_id = $_SESSION['user_id'];

$am = AccountManager::getInstance();
$am->updateAccount($id, $inst, $type);
