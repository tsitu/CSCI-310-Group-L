<?php

$servername = "localhost";
$dbname = "minance";
$username = "dbworker";
$password = "password";	//TODO enter a password here

// Create connection
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check for errors
if ($mysqli->connect_errno) {
    echo "Database connection error: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error . "<br />\n";
}
//else echo "Successful connection" . "<br />\n";

