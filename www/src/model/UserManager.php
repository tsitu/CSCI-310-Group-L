<?php

require_once __DIR__ . '/DBManager.php';

/**
 * Singleton UserManager provides DB queries related to users.
 * Uses connection from singleton DBManager to execute queries.
 */
class UserManager
{
    private static $instance;

    private $connection;

    /* --- INIT --- */
    /**
     * Returns singleton instnace of UserManager
     *
     * @return singleton instance of UserManager
     */
    public static function getInstance()
    {
        if(null === static::$instance)
            static::$instance = new static();

        return static::$instance;
    }

    /**
     * Protected constructor to prevent new instance.
     * Store reference to connection from `DBManager`
     */
    protected function __construct()
    {
        $this->connection = DBManager::getConnection();
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


    /* --- QUERIES --- */
    /**
     * Verify that user with given `$username` and `$password` exist, and return user id if found.
     *
     * @param $username - string email
     * @param $password - string password
     * @return user_id if valid, otherwise null
     */
    public function verify($username, $password)
    {
        $str = "SELECT id, password FROM Users WHERE email = ?";

        //encrypt
        $username = DBManager::encrypt($username);

        // echo $username . '<br>';
        // echo $password . '<br>';

        $stmt = $this->connection->prepare($str);
        $stmt->execute([$username]);

        $row = $stmt->fetch(PDO::FETCH_OBJ);
        if (!$row)
            return null;

        if ( !password_verify($password, $row->password) )
            return null;

        return $row->id;
    }

    /** 
     * Adds user to database with given parameters.
     */
    public function addUser($username, $password)
    {
        $str = "INSERT INTO Users (email, password) VALUES (:username, :password)";

        //encrypt
        $username = DBManager::encrypt($username);
        $password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->connection->prepare($str);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
    }

    /**
     * Delete specified user
     */
    public function deleteUser($id)
    {
        $str = "DELETE FROM Users WHERE id = :id";

        $stmt = $this->connection->prepare($str);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    /**
     * Get specified user
     */
    public function getUser($id)
    {
        $str = "SELECT id, email, password FROM Users WHERE id = :id";

        $stmt = $this->connection->prepare($str);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_OBJ);
        if (!$row)
            return null;

        //decrypt
        $row->email = DBManager::decrypt($row->email);

        return new User($row->id, $row->email, $row->password);
    }

    //totals
    /**
     * Get spending for all categories by user during given month, year
     *
     * @return map of categories to spending
     */
    public function getCategorySpendingsForTime($user_id, $month, $year)
    {
        $str = "
        SELECT category, SUM(amount) as spent from Transactions 
        WHERE (user_id, MONTH(t), YEAR(t)) = (:user_id, :month, :year)
        GROUP BY category
        ";

        $stmt = $this->connection->prepare($str);
        $stmt->execute([
            ':user_id'  => $user_id,
            ':month'    => $month,
            ':year'     => $year
        ]);

        $rows = $stmt->fetchAll(PDO::FETCH_OBJ);
        if (!$rows)
            return [];

        $results = [];
        foreach ($rows as $row)
        {
            $c = strtolower( trim(DBManager::decrypt($row->category)) );
            $results[$c] = (double) $row->spent;
        }

        return $results;
    }

    //account_type = {'asset', 'liability', 'net'}
    //time parameters are in datetime format or YYYY-MM-DDThh:mm:ss.nnn format
    //DOES NOT account for negatives...so if savings balance is negative, it's not counted in liabilities or net worth...
    public function getAssetHistory($account_type, $user_id, $beg, $end)
    {
        $str = "";

        //1. find all transaction occurances after beg and before end
        if($account_type == 'asset') 
        {
            $str = "SELECT * FROM Transactions INNER JOIN Accounts ON Transactions.account_id=Accounts.id
                    WHERE Transactions.t >= :beg AND Transactions.t <= :end AND Transactions.user_id = :user_id AND Accounts.type = :savings
                    ORDER BY t";
        }
        else if($account_type == 'liability')
        {
            $str = "SELECT * FROM Transactions INNER JOIN Accounts ON Transactions.account_id=Accounts.id
                    WHERE Transactions.t >= :beg AND Transactions.t <= :end AND Transactions.user_id = :user_id AND (Accounts.type = :credit OR Accounts.type = :loan)
                    ORDER BY t";
        }
        else if($account_type == 'net')
        {
            $str = "SELECT * FROM Transactions INNER JOIN Accounts ON Transactions.account_id=Accounts.id
                    WHERE Transactions.t >= :beg AND Transactions.t <= :end AND Transactions.user_id = :user_id AND (Accounts.type = :savings OR Accounts.type = :credit OR Accounts.type = :loan)
                    ORDER BY t";
        }
        else
        {
            echo '$account_type parameter not formatted correctly.<br>';
            return null;
        }

        //encrypt
        $beg = DBManager::sqlDatetime($beg);
        $end = DBManager::sqlDatetime($end);

        $savings    = DBManager::encrypt('Savings');
        $credit     = DBManager::encrypt('Credit');
        $loan       = DBManager::encrypt('Loan');


        //prepare
        $stmt = $this->connection->prepare($str);
        $stmt->bindParam(':user_id', $user_id);


        $stmt->bindParam(':beg', $beg);
        $stmt->bindParam(':end', $end);

        if($account_type == 'asset') 
        {
            $stmt->bindParam(':savings', $savings);
        }
        else if($account_type == 'liability')
        {
            $stmt->bindParam(':credit', $credit);
            $stmt->bindParam(':loan', $loan);
        }
        else if($account_type == 'net')
        {
            $stmt->bindParam(':savings', $savings);
            $stmt->bindParam(':credit', $credit);
            $stmt->bindParam(':loan', $loan);
        }

        //execute
        $stmt->execute();
        $rows = $stmt->fetchAll();
        if (!$rows)
            return [];
        
        //sum up the amounts, and at each timestamp, add a snapshot into the array.
        $unique_accounts = [];
        $transactions = [];
        $snapshot = []; //k->datetime, v->totalAmount
        $times = [];    //all transaction datetimes in range beg to end
        $sum = 0;
        foreach($rows as $row)
        {
<<<<<<< Updated upstream
        	echo "Processing " . $row['account_id'] . "<br>";
=======
>>>>>>> Stashed changes
            if(!in_array($row['account_id'], $unique_accounts))
            {
                $unique_accounts[] = $row['account_id'];
                $sum += $row['balance'];
                $snapshot[ date_create($row['t'])->getTimestamp() ] = $sum;
            }
            else 
            {
                $sum += $row['amount'];
                $snapshot[ date_create($row['t'])->getTimestamp() ] = $sum;
            }
        }
        echo "size: " . count($unique_accounts) . "<br>";
        echo $unique_accounts[0] . " " . $unique_accounts[1] . "<br>";
        return $snapshot;
    }
}