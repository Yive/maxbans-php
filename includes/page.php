<?php
namespace litebans;

use PDO;
use PDOException;

class Page {
    public function __construct($name, $header = true) {
        if ($header) {
            require_once './includes/head.php';
            require_once './includes/header.php';
        }
        require_once './includes/settings.php';
        $settings = new Settings();
        $this->conn = $settings->conn;
        $this->settings = $settings;
        $this->uuid_name_cache = array();
        $this->time = microtime(true);
        $this->page = 1;
        if (isset($_GET['page'])) {
            $page = $_GET['page']; // user input
            if (filter_var($page, FILTER_VALIDATE_INT)) {
                $this->page = (int)$page;
            }
        }
        $this->name = $name;

        $this->type = null;
        $this->table = null;
        $this->title = null;

        $info = $this->type_info($name);
        $this->set_info($info);

        $this->permanent = array(
            'ban'  => 'Permanent Ban',
            'mute' => 'Permanent Mute',
            'warn' => 'Permanent',
            'kick' => null,
        );
        $this->expired = array(
            'ban'  => '(Unbanned)',
            'mute' => '(Unmuted)',
            'warn' => '(Expired)',
            'kick' => null,
        );
    }

    public function type_info($type) {
        $settings = $this->settings;
        switch ($type) {
            case "ban":
            case "bans":
                return array(
                    "type"  => "ban",
                    "table" => $settings->table['bans'],
                    "title" => "Bans",
                );
            case "mute":
            case "mutes":
                return array(
                    "type"  => "mute",
                    "table" => $settings->table['mutes'],
                    "title" => "Mutes",
                );
            case "warn":
            case "warnings":
                return array(
                    "type"  => "warn",
                    "table" => $settings->table['warnings'],
                    "title" => "Warnings",
                );
            case "kick":
            case "kicks":
                return array(
                    "type"  => "kick",
                    "table" => $settings->table['kicks'],
                    "title" => "Kicks",
                );
            default:
                return array(
                    "type"  => null,
                    "table" => null,
                    "title" => null,
                );
        }
    }

    function run_query() {
        try {
            $table = $this->table;
            $active_query = $this->settings->active_query;
            $limit = $this->settings->limit_per_page;

            $offset = 0;
            if ($this->settings->show_pager) {
                $page = $this->page - 1;
                $offset = ($limit * $page);
            }
            $query = "SELECT * FROM $table $active_query GROUP BY $table.id ORDER BY time DESC LIMIT :limit OFFSET :offset";
            $st = $this->conn->prepare($query);

            $st->bindParam(':offset', $offset, PDO::PARAM_INT);
            $st->bindParam(':limit', $limit, PDO::PARAM_INT);

            $st->execute();

            return $st;
        } catch (PDOException $ex) {
            die($ex->getMessage());
        }
    }

    /**
     * Returns HTML representing the Minecraft avatar for a specific name or UUID.
     * @param $name
     * @param $uuid
     * @param bool $name_under
     * @param string $name_repl
     * @return string
     */
    function get_avatar($name, $uuid, $name_under = true, $name_repl = null) {
        if (strlen($uuid) === 36 && $uuid[14] === '3') {
            // Avatars cannot be associated with offline mode UUIDs (version 3)
            $uuid = $name;
        }
        $src = str_replace('$NAME', $name, str_replace('$UUID', $uuid, $this->settings->avatar_source));
        if (in_array($name, $this->settings->console_aliases)) {
            $src = $this->settings->console_image;
            $name = $this->settings->console_name;
        }
        if ($name_repl !== null) {
            $name = $name_repl;
        }
        if ($name_under) {
            return "<p align='center'><img class='avatar noselect' src='$src'/><br>$name</p>";
        }
        return "<img class='avatar noselect' src='$src'/>$name";
    }

    /**
     * Returns the last name for a UUID, or null if their name is not recorded in the database.
     * @param string
     * @return null|string
     */
    function get_name($uuid) {
        if (array_key_exists($uuid, $this->uuid_name_cache)) return $this->uuid_name_cache[$uuid];
        $history = $this->settings->table['history'];
        $stmt = $this->conn->prepare("SELECT name FROM $history WHERE uuid=? ORDER BY date DESC LIMIT 1");
        if ($stmt->execute(array($uuid)) && $row = $stmt->fetch()) {
            $banner = $row['name'];
            $this->uuid_name_cache[$uuid] = $banner;
            return $banner;
        }
        $this->uuid_name_cache[$uuid] = null;
        return null;
    }

    /**
     * Returns the banner name for a specific row in the database
     * using their UUID->name if possible, otherwise returns their last recorded name.
     * @param row
     * @return string
     */
    function get_banner_name($row) {
        $uuid = $row['banned_by_uuid'];
        $display_name = $row['banned_by_name'];
        $console_aliases = $this->settings->console_aliases;
        if (in_array($uuid, $console_aliases) || in_array($row['banned_by_name'], $console_aliases)) {
            return $this->settings->console_name;
        }
        $name = $this->get_name($uuid);
        if ($name !== null) {
            return $name;
        }
        return $this->clean($display_name);
    }

    /**
     * Converts a timestamp (in milliseconds) to a date using the configured date format.
     * @param int
     * @return string
     */
    function millis_to_date($millis) {
        return date($this->settings->date_format, $millis / 1000);
    }

    /**
     * Prepares text to be displayed on the web interface.
     * Removes chat colours, replaces newlines with proper HTML, and sanitizes the text.
     * @param string
     * @return string
     */
    function clean($text) {
        if (strstr($text, "\xa7") || strstr($text, "&")) {
            $text = preg_replace("/(?i)(\xa7|&)[0-9A-FK-OR]/", "", $text);
        }
        $text = htmlspecialchars($text, ENT_QUOTES, "UTF-8");
        if (strstr($text, "\n")) {
            $text = preg_replace("/\n/", "<br>", $text);
        }
        return $text;
    }

    /**
     * Returns a string that shows the expiry date of a punishment.
     * If the punishment does not expire, it will be shown as permanent.
     * If the punishment has already expired, it will show as expired.
     * @param row
     * @return string
     */
    public function expiry($row) {
        if ($this->type === "kick") {
            return "N/A";
        }
        if ($row['until'] <= 0) {
            $until = $this->permanent[$this->type];
        } else {
            $until = $this->millis_to_date($row['until']);
        }
        if ($this->settings->show_inactive_bans && !$row['active']) {
            $until .= ' ' . $this->expired[$this->type];
        }
        return $until;
    }

    function title() {
        return ucfirst($this->name);
    }

    function print_title() {
        $title = $this->title();
        $name = $this->settings->name;
        echo "<title>$title - $name</title>";
    }

    function print_table_rows($row, $array) {
        $id = $row['id'];
        $type = $this->type;
        echo "<tr>";
        foreach ($array as $header => $text) {
            $style = "";
            if ($header === "Reason") {
                $style = "style=\"width: 30%;\"";
            }
            $a = "a";
            if ($header === "Received Warning") {
                $icon = ($text !== "No") ? "glyphicon-ok" : "glyphicon-remove";
                $a .= " class=\"glyphicon $icon\" aria-hidden=true";
                $text = "";
            }
            echo "<td $style><$a href=\"info.php?type=$type&id=$id\">$text</a></td>";
        }
        echo "</tr>";
    }

    function print_page_header($container_start = true) {
        $title = $this->title();
        if ($container_start) {
            echo '<div class="container">';
        }

        echo "<div class=\"row\"><div style=\"text-align: center;\" class=\"col-lg-12\"><h1 class=\"modal-header\">$title</h1></div></div>";
        if ($container_start) {
            echo '<div class="row"><div style="text-align: center;" class="col-lg-12">';
        }
    }

    function table_print_headers($headers) {
        echo("<thead><tr>");
        foreach ($headers as $header) {
            echo "<th><div style=\"text-align: center;\">$header</div></th>";
        }
        echo("<tbody>");
    }

    function print_check_form() {
        $table = $this->name;
        echo('
         <div style="text-align: left;" class="row">
             <div style="margin-left: 15px;">
                 <form onsubmit="captureForm(event);" class="form-inline"><div class="form-group"><input type="text" class="form-control" id="user" placeholder="Player"></div><button type="submit" class="btn btn-default" style="margin-left: 5px;">Check</button></form>
             </div>
             <script type="text/javascript">function captureForm(b){o=$("#output");o.removeClass("in");x=setTimeout(function(){o.html("<br>")}, 150);$.ajax({type:"GET",url:"check.php?name="+$("#user").val()+"&table=' . $table . '"}).done(function(c){clearTimeout(x);o.html(c);o.addClass("in")});b.preventDefault();return false};</script>
             <div id="output" class="success fade" data-alert="alert" style="margin-left: 15px;"><br></div>
         </div>
         ');
    }

    function print_pager($total = -1, $args = "") {
        $table = $this->table;
        $page = $this->name . ".php";

        if (!$this->settings->show_pager) return;
        if ($total === -1) {
            $result = $this->conn->query("SELECT COUNT(*) AS count FROM $table")->fetch(PDO::FETCH_ASSOC);
            $total = $result['count'];
        }

        $pages = (int)($total / $this->settings->limit_per_page) + 1;

        $cur = $this->page;
        $prev = $cur - 1;
        $next = $this->page + 1;

        $prev_active = ($cur > 1);
        $next_active = ($cur < $pages);

        $prev_class = $prev_active ? "pager-active" : "pager-inactive";
        $next_class = $next_active ? "pager-active" : "pager-inactive";

        $pager_prev = "<div class=\"$prev_class\" style=\"float:left; font-size:30px;\">«</div>";
        if ($prev_active) {
            $pager_prev = "<a href=\"$page?page={$prev}{$args}\">$pager_prev</a>";
        }

        $pager_next = "<div class=\"$next_class\" style=\"float: right; font-size:30px;\">»</div>";
        if ($next_active) {
            $pager_next = "<a href=\"$page?page={$next}{$args}\">$pager_next</a>";
        }
        $pager_count = "<div style=\"margin-top: 32px;\"><div style=\"text-align: center; font-size:15px;\">Page $cur/$pages</div></div>";
        echo "$pager_prev $pager_next $pager_count";
    }

    function print_footer($container_end = true) {
        if ($container_end) {
            echo "</div></div></div>";
        }
        include './includes/footer.php';
        $time = microtime(true) - $this->time;
        echo "<!-- Page generated in $time seconds. -->";
    }

    public function table_begin() {
        echo '<table class="table table-striped table-bordered table-condensed">';
    }

    public function table_end($clicky = true) {
        echo '</table>';
        if ($clicky) {
            echo "<script type=\"text/javascript\">$('tr').click(function(){window.location=$(this).find('a').attr('href');}).hover(function(){\$(this).toggleClass('hover');});</script>";
        }
    }

    /**
     * @param $info
     */
    public function set_info($info) {
        $this->type = $info['type'];
        $this->table = $info['table'];
        $this->title = $info['title'];
    }
}
