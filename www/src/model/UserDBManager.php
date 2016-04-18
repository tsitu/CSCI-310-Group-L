<?php

require_once __DIR__ . "/DBManager.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/src/model/User.php";

class UserDBManager {

	private $db;
	protected static $userdb;

	private function __construct() {
		$db = DBManager::getConnection();
	}

	public static function getUserDBManager() {
		if(null === static::$userdb) {
			static::$userdb = new static();
		}

		return static::$userdb;
	}

	//Adds user to database with given parameters.
	public function addUser($email, $raw_password) {
		$stmt = DBManager::getConnection()->prepare("INSERT INTO Users (email, password) VALUES (:email, :hashed_password)");

		$stmt->bindParam(':email', DBManager::encrypt($email));
		$stmt->bindParam(':hashed_password', password_hash($raw_password, PASSWORD_DEFAULT));

		$stmt->execute(); 	//safe from SQL injection
	}

	//Deletes account from database matching $id.
	public function deleteUser($id) {
		$stmt = DBManager::getConnection()->prepare("DELETE FROM Users WHERE id = :id");

		$stmt->bindParam(':id', $id);

		$stmt->execute();	//safe from SQL injection
	}

	//Returns id matching given paramters
	public function getUserId($email, $raw_password) {
		$stmt = DBManager::getConnection()->prepare("SELECT * FROM Users WHERE email = :email");

		$stmt->bindParam(":email", DBManager::encrypt($email));
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		if(password_verify($raw_password, $row['password']))
			return $row['id'];

	}

	//returns user object matching id.
	public function getUser($id) {
		$stmt = DBManager::getConnection()->prepare("SELECT * FROM Users WHERE id = :id");

		$stmt->bindParam(':id', $id);

		$stmt->execute();	//safe from SQL injection

		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return new User($row['id'], DBManager::decrypt($row['email']), $row['password']);
	}

}

