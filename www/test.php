<?php

require_once 'src/model/UserManager.php';
require_once 'src/model/TransactionManager.php';
require_once 'src/model/AccountManager.php';

$um = UserManager::getInstance();
$pairs = $um->getAssetHistory('net worth', 2, '2016-01-02 10:00:00', '2016-04-12 10:00:00');

foreach($pairs as $time => $value) {
	echo "$time - $value<br>";
}