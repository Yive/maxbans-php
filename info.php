<?php
namespace litebans;

use PDO;

require_once './includes/page.php';

abstract class Info {
    /**
     * @param $row PDO::PDORow
     * @param $page Page
     */
    public function __construct($row, $page) {
        $this->row = $row;
        $this->page = $page;
        $this->table = $page->table;
    }

    static function create($row, $page, $type) {
        switch ($type) {
            case "ban":
                return new BanInfo($row, $page);
            case "mute":
                return new MuteInfo($row, $page);
            case "warn":
                return new WarnInfo($row, $page);
            case "kick":
                return new KickInfo($row, $page);
        }
        return null;
    }

    function name() {
        return ucfirst($this->page->type);
    }

    function permanent() {
        return $this->row['until'] <= 0;
    }

    function history_link($player_name, $row) {
        $uuid = $row['uuid'];
        return "<a href=\"history.php?uuid=$uuid\">$player_name</a>";
    }

    abstract function basic_info($row, $player_name);
}

class BanInfo extends Info {
    function basic_info($row, $player_name) {
        $page = $this->page;
        return array(
            'Banned Player' => $page->get_avatar($player_name, $row['uuid'], false, $this->history_link($player_name, $row)),
            'Banned By'     => $page->get_avatar($page->get_banner_name($row), $row['banned_by_uuid'], false),
            'Ban Reason'    => $page->clean($row['reason']),
            'Ban Placed'    => $page->millis_to_date($row['time']),
            'Expires'       => $page->expiry($row),
        );
    }
}

class MuteInfo extends Info {
    function basic_info($row, $player_name) {
        $page = $this->page;
        return array(
            'Muted Player' => $page->get_avatar($player_name, $row['uuid'], false, $this->history_link($player_name, $row)),
            'Muted By'     => $page->get_avatar($page->get_banner_name($row), $row['banned_by_uuid'], false),
            'Mute Reason'  => $page->clean($row['reason']),
            'Mute Placed'  => $page->millis_to_date($row['time']),
            'Expires'      => $page->expiry($row),
        );
    }
}

class WarnInfo extends Info {
    function name() {
        return "Warning";
    }

    function basic_info($row, $player_name) {
        $page = $this->page;
        return array(
            'Warned Player'  => $page->get_avatar($player_name, $row['uuid'], false, $this->history_link($player_name, $row)),
            'Warned By'      => $page->get_avatar($page->get_banner_name($row), $row['banned_by_uuid'], false),
            'Warning Reason' => $page->clean($row['reason']),
            'Warning Placed' => $page->millis_to_date($row['time']),
            'Expires'        => $page->expiry($row),
        );
    }
}

class KickInfo extends Info {
    function basic_info($row, $player_name) {
        $page = $this->page;
        return array(
            'Kicked Player' => $page->get_avatar($player_name, $row['uuid'], false, $this->history_link($player_name, $row)),
            'Kicked By'     => $page->get_avatar($page->get_banner_name($row), $row['banned_by_uuid'], false),
            'Kick Reason'   => $page->clean($row['reason']),
            'Kick Date'     => $page->millis_to_date($row['time']),
        );
    }
}

// check if info.php is requested, otherwise it's included
if ((substr($_SERVER['SCRIPT_NAME'], -strlen("info.php"))) !== "info.php") {
    return;
}

if (!isset($_GET['type'], $_GET['id'])) {
    die("Missing arguments (type, id).");
}

$type = $_GET['type'];
$id = $_GET['id'];
$page = new Page($type);

if ($page->type === null) {
    die("Unknown page type requested.");
}

if (!filter_var($id, FILTER_VALIDATE_INT)) {
    die("Invalid ID.");
}
$id = (int)$id;

$type = $page->type;
$table = $page->table;
$query = "SELECT * FROM $table WHERE id=? LIMIT 1";

$st = $page->conn->prepare($query);

if ($st->execute(array($id))) {
    if (!($row = $st->fetch())) {
        die("Error: $type not found in database.");
    }
    $player_name = $page->get_name($row['uuid']);
    if ($player_name === null) {
        die("Error: Player name not found.");
    }

    $info = Info::create($row, $page, $type);

    $name = $info->name();
    $permanent = $info->permanent();
    $active = $row['active'];

    $page->name = "$name #$id";
    $page->print_title();

    if (!($info instanceof KickInfo)) {
        $style = 'style="margin-left: 13px; font-size: 16px;"';
        if ($active) {
            $page->name .= "<span $style class='label label-danger'>Active</span>";
        } else {
            $page->name .= "<span $style class='label label-warning'>Inactive</span>";
        }
        if ($permanent) {
            $page->name .= "<span $style class='label label-danger'>Permanent</span>";
        }
    }
    $page->print_page_header();

    $page->table_begin();
    $map = $info->basic_info($row, $player_name);
    $permanent_val = $info->page->permanent[$type];
    foreach ($map as $key => $val) {
        if ($permanent && $key === "Expires" && $val === $permanent_val) {
            continue;
        }
        echo "<tr><td>$key</td><td>$val</td></tr>";
    }
    $page->table_end(false);
    $page->print_footer();
}
