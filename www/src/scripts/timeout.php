<?php
//PHP timeout- include at the top of every page that should be secured.

if(session_id() == "" || !isset($_SESSION))
	session_start();

if($_SESSION['timeout'] < time())	//expiry time has past the current time
{
	header("Location: /src/scripts/logout.php"); //redirect to logout.php
	exit();

	//DELETE ME WHEN DONE:
	//IMPORTANT: logout should NOT have the timeout script. Or it will do endless redirect.
	//timeout should be updated on login WITHOUT checking the timeout.
}

$_SESSION['timeout'] = time() + 30;	//TEMPORARY: 30 second timeout for testing purposes. Change to 10 minutes.

