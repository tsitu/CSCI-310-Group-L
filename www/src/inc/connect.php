<?php

$servername = "sql3.freemysqlhosting.net";
$dbname = "sql3112429";
$username = "sql3112429";
$password = "NqxhS6d8yQ";

// $servername = "localhost";
// $dbname = "minance";
// $username = "root";
// $password = "";

// Create connection
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check for errors
if ($mysqli->connect_errno) {
    echo "Database connection error: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error . "<br />\n";
}
//else echo "Successful connection" . "<br />\n";

?>