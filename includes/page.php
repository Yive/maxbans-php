<?php
require './includes/head.php';
require './includes/header.php';
require_once './includes/settings.php';

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

$banner_name_cache = [];

function get_banner_name($row) {
    global $conn, $table_history, $banner_name_cache;
    $uuid = $row['banned_by_uuid'];
    if (array_key_exists($uuid, $banner_name_cache)) return $banner_name_cache[$uuid];
    $stmt = $conn->prepare("SELECT name FROM " . $table_history . " WHERE uuid=? ORDER BY date DESC LIMIT 1");
    if ($stmt->execute(array($uuid)) && $r = $stmt->fetch()) {
        $banner = $r['name'];
        $banner_name_cache[$uuid] = $banner;
        return $banner;
    }
    //return "null";
    $banner = $row['banned_by_name'];
    return clean($banner);
}

function millis_to_date($millis) {
    global $date_format;
    date_default_timezone_set("UTC");
    return date($date_format, $millis / 1000);
}

/**
 * Prepares text to be displayed on the web interface.
 * Removes chat colours, replaces newlines with proper HTML, and sanitizes the text.
 * @param $text
 * @return mixed|string
 */
function clean($text) {
    if (strstr($text, "\xa7") || strstr($text, "&")) {
        $regex = "/(?i)(\xa7|&)[0-9A-FK-OR]/";
        $text = preg_replace($regex, "", $text);
    }
    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    if (strstr($text, "\n")) {
        $text = preg_replace("/\n/", "<br>", $text);
    }
    return $text;
}

?>
