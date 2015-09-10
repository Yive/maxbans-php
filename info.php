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

    function name() {
        return ucfirst($this->page->type);
    }

    function permanent() {
        return $this->row['until'] <= 0;
    }

    abstract function basic_info($row, $player_name);

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
}

class BanInfo extends Info {
    function basic_info($row, $player_name) {
        $page = $this->page;
        return array(
            'Banned Player' => $page->get_avatar($player_name),
            'Banned By'     => $page->get_avatar($page->get_banner_name($row)),
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
            'Muted Player' => $page->get_avatar($player_name),
            'Muted By'     => $page->get_avatar($page->get_banner_name($row)),
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
            'Warned Player'  => $page->get_avatar($player_name),
            'Warned By'      => $page->get_avatar($page->get_banner_name($row)),
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
            'Kicked Player' => $page->get_avatar($player_name),
            'Kicked By'     => $page->get_avatar($page->get_banner_name($row)),
            'Kick Reason'   => $page->clean($row['reason']),
            'Kick Date'     => $page->millis_to_date($row['time']),
        );
    }
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
    if ($player_name == null) {
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

    ?>
    <div class="container">
        <div class="row" style="margin-bottom:60px;">
            <?php $page->print_page_header(); ?>
            <div style="text-align: center;" class="col-lg-12">
                <table class="table table-striped table-bordered table-condensed">
                    <?php
                    $map = $info->basic_info($row, $player_name);
                    $permanent_val = $info->page->permanent[$type];
                    foreach ($map as $key => $val) {
                        if ($permanent && $key === "Expires" && $val === $permanent_val) {
                            continue;
                        }
                        echo "<tr><td>$key</td><td>$val</td></tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
        <?php
        $page->print_footer();
        ?>
    </div>
    <?php
}
