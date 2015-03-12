<?php
require 'head.php';
require 'header.php';
require 'data/database.php';

function get_query($table) {
    global $table_history, $active_query, $limit_per_page;
    return 'SELECT * FROM ' . $table . ' INNER JOIN ' . $table_history . ' on ' . $table . '.uuid=' . $table_history . '.uuid ' . $active_query .
    ' GROUP BY name ORDER BY time DESC LIMIT ' . $limit_per_page;
}

function run_query($table) {
    global $conn;
    if (!$result = $conn->query(get_query($table))) {
        die('Query error [' . $conn->error . ']');
    }
    return $result;
}

function get_avatar($name) {
    return "<img src='https://minotar.net/avatar/" . $name . "/25' style='margin-bottom:5px;margin-right:5px;border-radius:2px;' />" . $name;
}

?>