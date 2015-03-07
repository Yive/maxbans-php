<?php
// Server host
$dbhost = 'localhost';

// Username/password
$username = 'root';
$password = 'password';

// Database name
$database = 'litebans';

// Show inactive bans? Removed bans will show (Unbanned), mutes will show (Unmuted), warnings will show (Inactive).
$show_inactive_bans = true;

// Amount of bans/mutes/warnings to show on each page
$limit_per_page = 20;

// If you set a table prefix in config.yml, put it here too
$table_prefix = "";


/*****************************************************************************/
$conn = new mysqli($dbhost, $username, $password, $database);

$table_bans     = $table_prefix . "bans";
$table_mutes    = $table_prefix . "mutes";
$table_warnings = $table_prefix . "warnings";
$table_history  = $table_prefix . "history";

if ($conn->connect_errno > 0) {
    die('Unable to connect to database: ' . $conn->connect_error);
}

$active_query = "WHERE active=1";
if ($show_inactive_bans) {
    $active_query = "";
}

?>