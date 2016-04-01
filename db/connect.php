<?php

$servername = "localhost";
$dbname = "minance";
$username = "root";
$password = "";	//TODO enter a password here

// Create connection
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check for errors
if ($mysqli->connect_errno) {
    echo "Database error: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

