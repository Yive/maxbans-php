<?php
namespace litebans;

use PDO;
use PDOException;
use PDORow;

require_once './includes/page.php';
require_once './info.php';

class History {
    /**
     * Appends COUNT(*) from $table matching $uuid to $counts,
     * then appends all rows from $table matching $uuid to $array
     * @param Page $page
     * @param array $array
     * @param string $type
     * @param string $uuid
     * @param string $field
     * @param array $counts
     */
    static function push($page, &$array, $type, $uuid, $field, &$counts) {
        $table = $page->settings->table[$type];
        $count_st = $page->conn->prepare("SELECT COUNT(*) AS count FROM $table WHERE $field=:uuid");
        $count_st->bindParam(":uuid", $uuid, PDO::PARAM_STR);
        if ($count_st->execute() && ($row = $count_st->fetch()) !== null) {
            $counts[$type] = $row['count'];
        }
        $sel = $page->get_selection($table);

        $st = $page->conn->prepare("SELECT $sel FROM $table WHERE $field=:uuid ORDER BY time");

        $st->bindParam(":uuid", $uuid, PDO::PARAM_STR);

        if ($st->execute()) {
            while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
                $row['__table__'] = $type;
                array_push($array, $row);
            }
        }
    }

    /**
     * usort() function for rows in the database
     * @param PDORow $a
     * @param PDORow $b
     * @return int
     */
    static function cmp_row_date($a, $b) {
        $a = $a['time'];
        $b = $b['time'];
        if ($a === $b) {
            return 0;
        }
        return ($a < $b) ? 1 : -1;
    }
}

$page = new Page("history");

isset($_GET['uuid']) && is_string($_GET['uuid']) or die("Missing arguments (uuid).");

$staffhistory = (isset($_GET['staffhistory']) && $_GET['staffhistory'] === "1");

$uuid = $_GET['uuid'];
$name = $page->get_name($uuid);

$name !== null or die("Player not found in database.");

if ($staffhistory) {
    $page->name = "Recent Punishments by $name";
} else {
    $page->name = "Recent Punishments for $name";
}


$page->print_title();
$page->print_page_header();

$from = null;
$from_title = null;
$from_href = null;

if (isset($_GET['from'])) {
    // sanitize $_GET['from']
    $info = $page->type_info($_GET['from']);
    if ($info['type'] !== null) {
        $from_title = $info['title'];
        $from = lcfirst($from_title);
        $from_href = "$from.php";
    }
}

try {
    $all = array();
    $counts = array();

    $field = "uuid";
    if ($staffhistory) {
        $field = "banned_by_uuid";
    }

    History::push($page, $all, 'bans', $uuid, $field, $counts);
    History::push($page, $all, 'mutes', $uuid, $field, $counts);
    History::push($page, $all, 'warnings', $uuid, $field, $counts);
    History::push($page, $all, 'kicks', $uuid, $field, $counts);

    $total = 0;
    foreach ($counts as $count) {
        $total += $count;
    }

    usort($all, array("litebans\\History", "cmp_row_date"));

    if (!empty($all)) {
        $page->table_begin();

        $offset = 0;
        $limit = $page->settings->limit_per_page;

        if ($page->settings->show_pager) {
            $current_page = $page->page - 1;
            $offset = ($limit * $current_page);
            $limit += $offset;
        }

        $i = 0;
        foreach ($all as $row) {
            $i++;
            if ($page->settings->show_pager && $i < $offset) {
                continue;
            }
            if ($i > $limit) break;

            $type = $row['__table__'];
            $page->set_info($page->type_info($type));

            $style = 'style="font-size: 13px;"';

            $label_type = $page->type;
            $label_name = Info::create($row, $page, $label_type)->name(); //ucfirst($label_type);
            $label = "<span $style class='label label-$label_type'>$label_name</span>";

            $page->print_table_rows($row, array(
                'Type'      => $label,
                'Player'    => $page->get_avatar($page->get_name($row['uuid']), $row['uuid']),
                'Moderator' => $page->get_avatar($page->get_banner_name($row), $row['banned_by_uuid']),
                'Reason'    => $page->clean($row['reason']),
                'Date'      => $page->millis_to_date($row['time']),
                'Expires'   => $page->expiry($row),
            ));
        }

        $page->table_end();
        // print pager
        if ($page->settings->show_pager) {
            $page->name = "history";
            $args = "&uuid=$uuid";
            if ($from !== null) {
                $args .= "&from=$from";
            }
            if ($staffhistory) {
                $args .= "&staffhistory=1";
            }
            $page->print_pager($total, $args);
        }
    } else {
        echo "No punishments found.<br>";
    }

    if ($from_href !== null) {
        echo "<br><a class=\"btn\" href=\"$from_href\">Return to $from_title</a>";
    }

    $page->print_footer();
} catch (PDOException $ex) {
    die($ex->getMessage());
}
