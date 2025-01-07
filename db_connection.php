<?php
// Database credentials
$host = "303.itpwebdev.com";
$user = "pangulur_pangulur";
$pass = "OOrang#8234";
$db = "pangulur_fit_helper";

// Create a new mysqli object to connect to the database
$mysqli = new mysqli($host, $user, $pass, $db);
$mysqli->set_charset('utf8');

// Check for connection errors
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

// The connection is successful if no error was echoed
// The $mysqli object is now ready for use in other scripts that include this file
?>