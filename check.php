<?php
if (isset($_POST['name'], $_POST['table'])) {
    require 'includes/page.php';
    $name = $_POST['name']; // user input
    global $table_bans, $table_history, $conn;
    $stmt = $conn->prepare("SELECT uuid FROM " . $table_history . " WHERE name=? ORDER BY date LIMIT 1");
    if ($stmt->execute(array($name))) {
        if ($row = $stmt->fetch()) {
            $uuid = $row['uuid'];
        }
    }
    if (isset($uuid)) {
        $stmt = $conn->prepare("SELECT * FROM " . $table_bans . " WHERE (uuid=? AND active=1) LIMIT 1");
        if ($stmt->execute(array($uuid))) {
            if ($row = $stmt->fetch()) {
                $banner = get_banner_name($row['banned_by_name']);
                $reason = $row['reason'];
                $time = millis_to_date($row['time']);
                $until = millis_to_date($row['until']);
                echo($name . ' is banned! <br>');
                echo('Banned by ' . $banner . '<br>');
                echo('Reason: ' . $reason . '<br>');
                echo('Banned on: ' . $time . '<br>');
                echo('Banned until: ' . $until . '<br>');
            } else {
                echo($name . ' is not banned.');
            }
        }
    } else {
        echo($name . ' has not joined before.');
    }
}
?>