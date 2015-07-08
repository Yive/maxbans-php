<?php
require './includes/head.php';
require './includes/header.php';
require_once './includes/settings.php';

litebans_connect();

function get_query($table) {
    global $active_query, $limit_per_page;
    return 'SELECT * FROM ' . $table . $active_query .
    ' GROUP BY ' . $table . '.id ORDER BY time DESC LIMIT ' . $limit_per_page;
}

function run_query($table) {
    global $conn;
    $time = microtime(true);
    try {
        $result = $conn->query(get_query($table));
    } catch (PDOException $ex) {
        die($ex->getMessage());
    }
    echo('<!-- Query executed in ' . (microtime(true) - $time) . ' sec -->');
    return $result;
}

function get_avatar($name) {
    return "<img src='https://cravatar.eu/avatar/" . $name . "/25' style='margin-bottom:5px;margin-right:5px;border-radius:2px;' />" . $name;
}

$uuid_name_cache = [];

function get_name($uuid) {
    global $conn, $table_history, $uuid_name_cache;
    if (array_key_exists($uuid, $uuid_name_cache)) return $uuid_name_cache[$uuid];
    $time = microtime(true);
    $stmt = $conn->prepare("SELECT name FROM " . $table_history . " WHERE uuid=? ORDER BY date DESC LIMIT 1");
    if ($stmt->execute(array($uuid)) && $row = $stmt->fetch()) {
        echo('<!-- Query executed in ' . (microtime(true) - $time) . ' sec -->');
        $banner = $row['name'];
        $uuid_name_cache[$uuid] = $banner;
        return $banner;
    }
    return null;
}

function get_banner_name($row) {
    $uuid = $row['banned_by_uuid'];
    $name = get_name($uuid);
    if ($name !== null) {
        return $name;
    }
    $name = $row['banned_by_name'];
    return clean($name);
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

function print_page_header($title) {
    echo('
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">' . $title . '</h1>
        </div>
    </div>
    ');
}

function print_table_headers($headers) {
    echo("<thead><tr>");
    foreach ($headers as $header) {
        echo('<th><div style="text-align: center;">');
        echo($header);
        echo('</div></th>');
    }
    echo("<tbody>");
}

function print_check_form($table) {
    // var table=document.URL.substring(document.URL.lastIndexOf("/")+1); table=table.substring(0,table.indexOf("."));
    echo('<br>');
    echo('<form onsubmit="captureForm(event);" class="form-inline"><div class="form-group"><input type="text" class="form-control" id="user" placeholder="Player"></div><button type="submit" class="btn btn-default">Check</button></form>');
    echo('<script type="text/javascript">function captureForm(b){$.ajax({type:"POST",url:"check.php",data:{name:document.getElementById("user").value,table:"' . $table . '"}}).done(function(c){document.getElementById("output").innerHTML=c});b.preventDefault();return false};</script>');
    echo('<div id="output"></div>');
}

?>
