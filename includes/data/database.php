<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = 'password';
$conn = new mysqli($dbhost, $dbuser, $dbpass, 'litebans');

$table_prefix = "";
$table_bans = $table_prefix . "bans";
$table_mutes = $table_prefix . "mutes";
$table_warnings = $table_prefix . "warnings";
$table_history = $table_prefix . "history";

if($conn->connect_errno > 0){
    die('Unable to connect to database [' . $conn->connect_error . ']');
}

?>