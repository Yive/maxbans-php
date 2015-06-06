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

// Show inactive bans? Removed bans will show (Unbanned), mutes will show (Unmuted), warnings will show (Expired).
$show_inactive_bans = true;

// Amount of bans/mutes/warnings to show on each page
$limit_per_page = 20;

// If you set a table prefix in config.yml, put it here too
$table_prefix = "";

$driver = 'mysql';

/*****************************************************************************/
function litebans_connect() {
    // imported
    global $dbhost, $username, $password, $database, $table_prefix, $show_inactive_bans, $driver;

    // exported
    global $conn, $active_query;
    global $table_bans, $table_mutes, $table_warnings, $table_history;

    $dsn = $driver . ':dbname=' . $database . ';host=' . $dbhost . ';charset=utf8';

    try {
        $conn = new PDO($dsn, $username, $password);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
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