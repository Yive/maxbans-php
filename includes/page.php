<?php
require 'head.php';
require 'header.php';
require_once 'settings.php';

litebans_connect();

function get_query($table) {
    global $table_history, $active_query, $limit_per_page;
    return 'SELECT * FROM ' . $table . ' INNER JOIN ' . $table_history . ' on ' . $table . '.uuid=' . $table_history . '.uuid ' . $active_query .
    ' GROUP BY ' . $table . '.id ORDER BY time DESC LIMIT ' . $limit_per_page;
}

function run_query($table) {
    global $conn;
    try {
        $result = $conn->query(get_query($table));
    } catch (PDOException $ex) {
        die($ex->getMessage());
    }
    return $result;
}

function get_avatar($name) {
    return "<img src='https://cravatar.eu/avatar/" . $name . "/25' style='margin-bottom:5px;margin-right:5px;border-radius:2px;' />" . $name;
}

function get_banner_name($banner) {
    if ($banner === "CONSOLE") {
        return "Console";
    }
    return $banner;
}

function millis_to_date($millis) {
    date_default_timezone_set("UTC");
    return date('F j, Y, g:i a', $millis / 1000);
}

/**
 * Removes chat colors from a string.
 * @param $text
 */
function clean($text) {
    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    if (strstr($text, "\xa7") || strstr($text, "&")) {
        $regex = "/(?i)(\xa7|&)[0-9A-FK-OR]/";
        $text = preg_replace($regex, "", $text);
    }
    if (strstr($text, "\n")) {
        $text = preg_replace("/\n/", "<br>", $text);
    }
    return $text;
}

?>
