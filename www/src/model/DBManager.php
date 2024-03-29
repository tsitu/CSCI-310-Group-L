<?php

/* CONST */
const HOST_NAME = "sql3.freemysqlhosting.net";
const HOST_IP 	= "54.215.148.52";
const USERNAME  = "sql3112429";
const PASSWORD  = "NqxhS6d8yQ";
const DB 		= "sql3112429";
const PORT 		= "3306";

/**
 * Single DBConnection class maintains one PDO connection to DB specified by constants.
 * All data managers should use `DBConnection::getConnection()` for working with DB.
 */
class DBManager
{
	private static $instance;

	private $connection;


	/* --- INIT --- */
	/**
	 * Return the lazy-loaded PDO connection to the DB.
	 *
	 * @return PDO connection to DB
	 */
	public static function getConnection()
	{
		if(null === static::$instance)
			static::$instance = new static();

		//var_dump(static::$db);
		return static::$instance->connection;
	}

	/**
	 * Protected constructor prevents new instances.
	 * Connect to the host db specified by constants and configure.
	 */
	protected function __construct()
	{
		$dsn = "mysql:host=" . HOST_IP . ";dbname=" . DB;
		$this->connection = new PDO($dsn, USERNAME, PASSWORD);

		//throw exceptions
		$this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$this->connection->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
		$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	/**
	 * Private clone to prevent two instances
	 */
	private function __clone()
	{

	}

	/**
	 * Private wakeup to prevent unserializing
	 */
	private function __wakeup()
	{

	}


	/* --- ENCRYPTION --- */
	/**
	 * Returns encrypted string of given plain text
	 *
	 * @param $plaintext - string to encrypt
	 * @return encrypted `$plaintext`
	 */
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

	/**
	 * Returns plain text of given encrypted string
	 *
	 * @param $ciphertext - encrypted string to decrypt
	 * @return decrypted `$cipertext`
	 */
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

	
	public static function sqlDatetime($datetime)
	{
		return $datetime->format("Y-m-d H:i:s");
	}
}