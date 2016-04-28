<?php

require_once 'src/model/UserManager.php';
require_once 'src/model/TransactionManager.php';
require_once 'src/model/AccountManager.php';

 $um = UserManager::getInstance();
 $st = new DateTime("04/01/2011");
 $en = new DateTime("05/01/2016");
 $pairs = $um->getAssetHistory('asset', 2, $st, $en);

foreach($pairs as $time => $value) {
	echo "$time - $value<br>";
}


// echo "Savings ->" . DBManager::encrypt("Savings");