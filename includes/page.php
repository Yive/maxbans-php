<?php
require './includes/head.php';
require './includes/header.php';
require_once './includes/settings.php';

class Page {
    public function __construct() {
        $settings = new Settings();
        $this->conn = $settings->conn;
        $this->settings = $settings;
        $this->uuid_name_cache = array();
    }

    function get_query($table) {
        return 'SELECT * FROM ' . $table . $this->settings->active_query .
        ' GROUP BY ' . $table . '.id ORDER BY time DESC LIMIT ' . $this->settings->limit_per_page;
    }

    function run_query($table) {
        $time = microtime(true);
        try {
            $result = $this->conn->query($this->get_query($table));
        } catch (PDOException $ex) {
            die($ex->getMessage());
        }
        echo('<!-- Query executed in ' . (microtime(true) - $time) . ' sec -->');
        return $result;
    }

    function get_avatar($name) {
        return "<img src='https://cravatar.eu/avatar/$name/25' style='margin-bottom:5px;margin-right:5px;border-radius:2px;' />$name";
    }

    function get_name($uuid) {
        if (array_key_exists($uuid, $this->uuid_name_cache)) return $this->uuid_name_cache[$uuid];
        $time = microtime(true);
        $stmt = $this->conn->prepare("SELECT name FROM " . $this->settings->table_history . " WHERE uuid=? ORDER BY date DESC LIMIT 1");
        if ($stmt->execute(array($uuid)) && $row = $stmt->fetch()) {
            echo('<!-- Query executed in ' . (microtime(true) - $time) . ' sec -->');
            $banner = $row['name'];
            $this->uuid_name_cache[$uuid] = $banner;
            return $banner;
        }
        $this->uuid_name_cache[$uuid] = null;
        return null;
    }

    function get_banner_name($row) {
        $uuid = $row['banned_by_uuid'];
        $name = $this->get_name($uuid);
        if ($name !== null) {
            return $name;
        }
        $name = $row['banned_by_name'];
        return $this->clean($name);
    }

    function millis_to_date($millis) {
        return date($this->settings->date_format, $millis / 1000);
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
                <h1 class="' . ($title === "Bans" ? "modal" : "navbar") . '-header">' . $title . '</h1>
            </div>
        </div>
        ');
    }

    function print_table_headers($headers) {
        echo("<thead><tr>");
        foreach ($headers as $header) {
            echo '<th><div style="text-align: center;">', $header, '</div></th>';
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
}

?>
