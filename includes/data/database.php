<?php
// Server host
$dbhost   = 'localhost';

// Username/password
$username = 'root';
$password = 'password';

// Database name
$database = 'litebans';

$conn = new mysqli($dbhost, $username, $password, $database);

$table_prefix = "";
$table_bans = $table_prefix . "bans";
$table_mutes = $table_prefix . "mutes";
$table_warnings = $table_prefix . "warnings";
$table_history = $table_prefix . "history";

if($conn->connect_errno > 0) {
    die('Unable to connect to database [' . $conn->connect_error . ']');
}

?>