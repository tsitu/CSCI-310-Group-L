<?php

require_once "../model/AccountManager.php";

$id = $_POST['id'];

$am = AccountManager::getInstance();
$am->deleteAccount($id);