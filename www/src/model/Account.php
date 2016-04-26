<?php


/**
 * Account class.
 */
class Account
{
	public $id;
	public $user_id;

	public $name;
	public $type;
	public $institution;
	public $balance;

	/**
	 *
	 */
	function __construct($_id, $_institution, $_type) {
		$this->id = $_id;
		$this->institution = $_institution;
		$this->type = $_type;
		
		$this->name = $this->institution . ' - ' . $this->type;
	}

	/**
	 *
	 */
	function getID() {
		return $this->id;
	}
	function getInstitution(){
		return $this->institution;
	}
	function getType() {
		return $this->type;
	}


	//Removes all transactions tied to $userId "AND" $accountId
	//"Remove Account" function on UI.
	static function removeTransactions($userId, $accountId) {
		global $mysqli;
		//prepare
		if( ($stmt = $mysqli->prepare("DELETE FROM transactions WHERE userId=? AND accountId=?")))
		{
			//bind
			if(! $stmt->bind_param("ii", $userId, $accountId) )
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error . "<br />";

			//execute
			if(! $stmt->execute() ) echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error . "<br />";

		} else {
			echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . "<br />"; //remove after debug
		}
	}


	//Gets an accountId given input. Creates one if none exists.
	//First does insert if not exists.
	//Second returns id.
	function getAccountId($institution, $type) {
		global $mysqli;

		$stmt1 = $mysqli->prepare("
		SELECT id
		FROM accounts
		WHERE institution=? AND type=?");

		if(! $stmt1->bind_param("ss", $institution, $type) )
		echo "Binding parameters failed: (" . $stmt1->errno . ") " . $stmt1->error . "<br />";

		if(! $stmt1->execute() ) echo "Execute failed: (" . $stmt1->errno . ") " . $stmt1->error . "<br />";

		$stmt1->bind_result($id);
		$stmt1->fetch();
		$stmt1->close();

		if ($id == 0) {
			$stmt2 = $mysqli->prepare("
			INSERT INTO accounts (institution, type)
			VALUES (?,?)");

			if(! $stmt2->bind_param("ss", $institution, $type) )
			echo "Binding parameters failed: (" . $stmt2->errno . ") " . $stmt2->error . "<br />";

			if(! $stmt2->execute() ) echo "Execute failed: (" . $stmt2->errno . ") " . $stmt2->error . "<br />";

			$id = $stmt2->insert_id;
			//echo "Inserted new id: " . $id . "<br />";
		}

		return $id;
	}

	/**
	 * Ensure numeric fields are the correct type since PDO::fetch() only generates strings
	 */
	public function fixTypes()
	{
		$this->id = (int) $this->id;
		$this->user_id = (int) $this->user_id;

		$this->type = rtrim(DBManager::decrypt($this->type));
		$this->institution = rtrim(DBManager::decrypt($this->institution));
		$this->name = $this->institution . ' - ' . $this->type;

		$this->balance = (double) $this->balance;
	}
}