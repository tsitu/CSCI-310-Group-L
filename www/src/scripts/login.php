<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../model/UserManager.php';

session_start();
$now = new DateTime();

checkLock();
checkStrike();

$username = $_POST['username'];
$password = $_POST['password'];

//check for empty fields
if ( empty($username) || empty($password) )
	error('Empty username or password');

//check against db for login
$user_id = UserManager::getInstance()->verify($username, $password);
if (!$user_id)
{
	if (addStrike())
		lock();

	error('Invalid login attempt ' . $_SESSION['strike']);
}


//set login session
clearStrike();
$_SESSION['user_id'] = $user_id;
$_SESSION['username'] = $username;


header('Location: /');
exit();



/* HELPERS */
/**
 * Redirect back to login page with session error message.
 */
function error($msg)
{
	$_SESSION['error'] = $msg;
    header('Location: /login');
    exit();
}

/**
 * Add a strike (init if first).
 * If over the limit
 */
function addStrike()
{
	global $config, $now;

	if ( !isset($_SESSION['strike']) )
		$_SESSION['strike'] = 0;

	$_SESSION['strike']++;
	$_SESSION['strike_reset']= new DateTime( $config['login_downtime'] );

	return $_SESSION['strike'] >= $config['login_attempts'];
}

/**
 * Clear the strikes
 */
function clearStrike()
{
	unset($_SESSION['strike']);
	unset($_SESSION['strike_reset']);
}

/**
 *
 */
function checkStrike()
{
	global $now;

	if (!isset($_SESSION['strike']))
		return;

	if ($now >= $_SESSION['strike_reset'])
		clearStrike();
}

/**
 * Clear strikes and lock login for 1 min.
 */
function lock()
{
	global $config;

	clearStrike();

	$_SESSION['lock'] = new DateTime( $config['login_downtime'] );
	error('Locked for 1 minute');
}

/**
 * Check if login is locked
 */
function checkLock()
{
	global $now;

	if (!isset($_SESSION['lock']))
		return;

	if ($now < $_SESSION['lock'])
		error('Locked for ' . date_diff($now, $_SESSION['lock'])->s . ' seconds');

	unset($_SESSION['lock']);
}

