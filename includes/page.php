<?php

class Page {
    public function __construct($header = true) {
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
    }

    function run_query($table) {
        try {
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
     * Returns an <img> tag representing the Minecraft avatar for a specific name.
     * @param string
     * @return string
     */
    function get_avatar($name) {
        return "<img class='avatar noselect' src='https://cravatar.eu/avatar/$name/25'/>$name";
    }

    /**
     * Returns the last name for a UUID, or null if their name is not recorded in the database.
     * @param string
     * @return null|string
     */
    function get_name($uuid) {
        if (array_key_exists($uuid, $this->uuid_name_cache)) return $this->uuid_name_cache[$uuid];
        $history = $this->settings->table_history;
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
        $name = $this->get_name($uuid);
        if ($name !== null) {
            return $name;
        }
        $name = $row['banned_by_name'];
        return $this->clean($name);
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
        $type = ($title === "Bans") ? "modal" : "navbar";
        echo("
        <div class=\"row\">
            <div class=\"col-lg-12\">
                <h1 class=\"$type-header\">$title</h1>
            </div>
        </div>
        ");
    }

    function print_table_headers($headers) {
        echo("<thead><tr>");
        foreach ($headers as $header) {
            echo "<th><div style=\"text-align: center;\">$header</div></th>";
        }
        echo("<tbody>");
    }

    function print_check_form($table) {
        echo('
         <div class="row">
             <div style="margin-left: 15px;">
                 <form onsubmit="captureForm(event);" class="form-inline"><div class="form-group"><input type="text" class="form-control" id="user" placeholder="Player"></div><button type="submit" class="btn btn-default" style="margin-left: 5px;">Check</button></form>
             </div>
             <script type="text/javascript">function captureForm(b){o=$("#output");o.removeClass("in");x=setTimeout(function(){o.html("<br>")}, 150);$.ajax({type:"POST",url:"check.php",data:{name:$("#user").val(),table:"' . $table . '"}}).done(function(c){clearTimeout(x);o.html(c);o.addClass("in")});b.preventDefault();return false};</script>
             <div id="output" class="success fade" data-alert="alert" style="margin-left: 15px;"><br></div>
         </div>
         ');
    }

    function print_pager($page, $table) {
        if (!$this->settings->show_pager) return;
        $result = $this->conn->query("SELECT COUNT(*) AS count FROM $table")->fetch(PDO::FETCH_ASSOC);
        $total = $result['count'];

        $pages = (int)($total / $this->settings->limit_per_page) + 1;

        $cur = $this->page;
        $prev = $cur - 1;
        $next = $this->page + 1;

        $pager_prev = "<div style=\"float:left; font-size:30px;\">«</div>";
        if ($cur > 1) {
            $pager_prev = "<a href=\"$page?page=$prev\">$pager_prev</a>";
        }

        $pager_next = "<div style=\"float: right; font-size:30px;\">»</div>";
        if ($cur < $pages) {
            $pager_next = "<a href=\"$page?page=$next\">$pager_next</a>";
        }
        $pager_count = "<div style=\"margin-top: 32px;\"><div style=\"text-align: center; font-size:15px;\">Page $cur/$pages</div></div>";
        echo "$pager_prev $pager_next $pager_count";
    }

    function print_footer() {
        include './includes/footer.php';
        $time = microtime(true) - $this->time;
        echo "<!-- Page generated in $time seconds. -->";
    }
}
