
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

	public static function unsafe_encrypt($plaintext) {
		# --- ENCRYPTION ---

	    # the key should be random binary, use scrypt, bcrypt or PBKDF2 to
	    # convert a string into a key
	    # key is specified using hexadecimal
	    $key = pack('H*', "efe04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a1123");
	    
	    # show key size use either 16, 24 or 32 byte keys for AES-128, 192
	    # and 256 respectively
	    $key_size =  strlen($key);
	    //echo "Key size: " . $key_size . "\n";
	    
	    //$plaintext = "This string was AES-256 / CBC / ZeroBytePadding encrypted.";

	    # create a random IV to use with CBC encoding
	    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
	    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	    
	    # creates a cipher text compatible with AES (Rijndael block size = 128)
	    # to keep the text confidential 
	    # only suitable for encoded input that never ends with value 00h
	    # (because of default zero padding)
	    $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $plaintext, MCRYPT_MODE_CBC, $iv);

	    # prepend the IV for it to be available for decryption
	    $ciphertext = $iv . $ciphertext;
	    
	    # encode the resulting cipher text so it can be represented by a string
	    $ciphertext_base64 = base64_encode($ciphertext);

	    //echo  $ciphertext_base64 . "\n";
	    return $ciphertext_base64;

	    # === WARNING ===

	    # Resulting cipher text has no integrity or authenticity added
	    # and is not protected against padding oracle attacks.
	}


	public static function unsafe_decrypt($ciphertext_base64) {

		$key = pack('H*', "efe04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a1123")
		 # --- DECRYPTION ---
    
	    $ciphertext_dec = base64_decode($ciphertext_base64);
	    
	    # retrieves the IV, iv_size should be created using mcrypt_get_iv_size()
	    $iv_dec = substr($ciphertext_dec, 0, $iv_size);
	    
	    # retrieves the cipher text (everything except the $iv_size in the front)
	    $ciphertext_dec = substr($ciphertext_dec, $iv_size);

	    # may remove 00h valued characters from end of plain text
	    $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
	    
	    return $plaintext_dec;
	}

}