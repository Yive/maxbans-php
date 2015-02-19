<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = 'password';
$conn = mysql_connect($dbhost, $dbuser, $dbpass);

$table_prefix = "";
$table_bans = $table_prefix . "bans";
$table_mutes = $table_prefix . "mutes";
$table_warnings = $table_prefix . "warnings";
$table_history = $table_prefix . "history";

if (!$conn) {
    die('Could not connect: ' . mysql_error());
}

mysql_select_db('litebans');
?>