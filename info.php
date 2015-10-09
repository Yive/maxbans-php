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
        return ((int)$this->row['until']) <= 0;
    }

    function history_link($player_name, $uuid, $args = "") {
        return "<a href=\"history.php?uuid=$uuid$args\">$player_name</a>";
    }

    function punished_avatar($player_name, $row) {
        return $this->page->get_avatar($player_name, $row['uuid'], false, $this->history_link($player_name, $row['uuid']));
    }

    function moderator_avatar($row) {
        $banner_name = $this->page->get_banner_name($row);
        return $this->page->get_avatar($banner_name, $row['banned_by_uuid'], false, $this->history_link($banner_name, $row['banned_by_uuid'], "&staffhistory=1"));
    }

    abstract function basic_info($row, $player_name);
}

class BanInfo extends Info {
    function basic_info($row, $player_name) {
        $page = $this->page;
        return array(
            'Banned Player' => $this->punished_avatar($player_name, $row),
            'Banned By'     => $this->moderator_avatar($row),
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
            'Muted Player' => $this->punished_avatar($player_name, $row),
            'Muted By'     => $this->moderator_avatar($row),
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
            'Warned Player'  => $this->punished_avatar($player_name, $row),
            'Warned By'      => $this->moderator_avatar($row),
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
            'Kicked Player' => $this->punished_avatar($player_name, $row),
            'Kicked By'     => $this->moderator_avatar($row),
            'Kick Reason'   => $page->clean($row['reason']),
            'Kick Date'     => $page->millis_to_date($row['time']),
        );
    }
}

// check if info.php is requested, otherwise it's included
if ((substr($_SERVER['SCRIPT_NAME'], -strlen("info.php"))) !== "info.php") {
    return;
}

isset($_GET['type'], $_GET['id']) or die("Missing arguments (type, id).");

$type = $_GET['type'];
$id = $_GET['id'];
$page = new Page($type);

($page->type !== null) or die("Unknown page type requested.");

filter_var($id, FILTER_VALIDATE_INT) or die("Invalid ID.");

$id = (int)$id;

$type = $page->type;
$table = $page->table;
$query = "SELECT * FROM $table WHERE id=? LIMIT 1";

$st = $page->conn->prepare($query);

if ($st->execute(array($id))) {
    ($row = $st->fetch()) or die("Error: $type not found in database.");

    $player_name = $page->get_name($row['uuid']);

    ($player_name !== null) or die("Error: Player name not found.");

    $info = Info::create($row, $page, $type);

    $name = $info->name();
    $permanent = $info->permanent();

    $page->name = "$name #$id";
    $page->print_title();

    if (!($info instanceof KickInfo)) {
        $style = 'style="margin-left: 13px; font-size: 16px;"';
        $active = $page->active($row);
        if ($active === true) {
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
            // skip "Expires" row if punishment is permanent
            continue;
        }
        echo "<tr><td>$key</td><td>$val</td></tr>";
    }
    $page->table_end(false);
    $page->print_footer();
}
