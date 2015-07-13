<?php
if (isset($_POST['name'], $_POST['table'])) {
    $name = $_POST['name'];
    // validate user input
    if (strlen($name) > 16 || !preg_match("/^[0-9a-zA-Z_]{1,16}$/", $name)) {
        echo('Invalid name.');
        return;
    }
    require './includes/page.php';
    $page = new Page();
    $name = $_POST['name'];

    $stmt = $page->conn->prepare("SELECT name,uuid FROM " . $page->settings->table_history . " WHERE name=? ORDER BY date LIMIT 1");
    if ($stmt->execute(array($name))) {
        if ($row = $stmt->fetch()) {
            $name = $row['name'];
            $uuid = $row['uuid'];
        }
    }
    if (!isset($uuid)) {
        $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        echo($name . ' has not joined before.<br>');
        return;
    }
    $table = $page->settings->table_bans;

    $stmt = $page->conn->prepare("SELECT * FROM " . $table . " WHERE (uuid=? AND active=1) LIMIT 1");
    if ($stmt->execute(array($uuid))) {
        if (!($row = $stmt->fetch())) {
            echo($name . ' is not banned.<br>');
            return;
        }
        $banner = $page->get_banner_name($row);
        $reason = $row['reason'];
        $time = $page->millis_to_date($row['time']);
        $until = $page->millis_to_date($row['until']);
        echo($name . ' is banned!<br>');
        echo('Banned by: ' . $banner . '<br>');
        echo('Reason: ' . $page->clean($reason) . '<br>');
        echo('Banned on: ' . $time . '<br>');
        if ($row['until'] > 0) {
            echo('Banned until: ' . $until . '<br>');
        } else {
            echo('Banned permanently.<br>');
        }
    }
}
?>
