<?php

$config = [

//dashboard
'default_range'		=> '-3 months',

//login
'login_attempts' 	=> 4,
'login_downtime'	=> '+1 min',

//db
'db_host'			=> 'sql3.freemysqlhosting.net',
'db_host_ip'		=> '54.215.148.52',
'db_port'			=> '3306',
'db_name'			=> 'sql3112429',
'db_username'		=> 'sql3112429',
'db_password'		=> 'NqxhS6d8yQ',

//budget
'budget_categories'	=> [
	'food', 
	'shopping', 
	'travel', 
	'education'
],
'budget_icons'	=> [
	'food' 		=> 'spoon',
	'shopping' 	=> 'pricetag',
	'travel' 	=> 'plane',
	'education' => 'university',
],
'budget_default'	=> 200

];