<?php
/** Server settings **/
$name = 'LiteBans';
$serverip = 'mc.example.com';

/** MySQL settings **/
// Server host
$dbhost = 'localhost';

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
function connect() {
    // imported
    global $dbhost, $username, $password, $database, $table_prefix, $show_inactive_bans;

    // exported
    global $conn, $active_query;
    global $table_bans, $table_mutes, $table_warnings, $table_history;

    $conn = new mysqli($dbhost, $username, $password, $database);

    if ($conn->connect_errno > 0) {
        die('Unable to connect to database: ' . $conn->connect_error);
    }

    $table_bans     = $table_prefix . "bans";
    $table_mutes    = $table_prefix . "mutes";
    $table_warnings = $table_prefix . "warnings";
    $table_history  = $table_prefix . "history";

    $active_query = "WHERE active=1";
    if ($show_inactive_bans) {
        $active_query = "";
    }
}

?>