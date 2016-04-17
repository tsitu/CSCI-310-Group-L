<?php

//require_once "Account.php";
require_once "Transaction.php";


/* CONST */
const HOST_NAME = "sql3.freemysqlhosting.net";
const HOST_IP 	= "54.215.148.52";
const USERNAME  = "sql3114710";
const PASSWORD  = "3zaKKK36kN";
const DB 		= "sql3114710";
const PORT 		= "3306";

/**
 * Database manager class.
 * When initialized, connects to the MySQL server specified by constants.
 * Provides query methods that return data as php objects.
 */
//Singleton design
class DBManager
{
	private static $db;

	protected function __construct()
	{
		$dsn = "mysql:host=54.215.148.52;dbname=sql3114710";
		$this->connection = new PDO($dsn, USERNAME, PASSWORD);

		//throw exceptions
		$this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$this->connection->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
		$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	public static function getConnection() {
		if(null === static::$db) {
			static::$db = new static();
		} 

		//var_dump(static::$db);
		return static::$db->connection;
	}

	public static function encrypt($plaintext) {
		//hexadecimal key
		$key = pack('H*', "ccc04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a0ccc");

		//
		$iv = "0000000000000000";

		//produces encrypted "object" of length 128.
		$ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $plaintext, MCRYPT_MODE_CBC, $iv);

		//translate encrypted object to readable text.
		$ciphertext = base64_encode($ciphertext);

		return $ciphertext;
	}

	public static function decrypt($ciphertext) {
		//hexadecimal key
		$key = pack('H*', "ccc04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a0ccc");

		//
		$iv = "0000000000000000";

		//translate readable text to encrypted object.
		$ciphertext = base64_decode($ciphertext);

		//decryption step
		$plaintext = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext, MCRYPT_MODE_CBC, $iv);

		return $plaintext;
	}

	
	
}