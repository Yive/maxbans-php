<?php
namespace litebans;

use PDO;

class Header {
/**
 * @param $page Page
 */
function __construct($page) {
    $this->page = $page;
    if ($page->settings->header_show_totals) {
        $t = $page->settings->table;
        $t_bans = $t['bans'];
        $t_mutes = $t['mutes'];
        $t_warnings = $t['warnings'];
        $t_kicks = $t['kicks'];
        $st = $page->conn->query("SELECT
            (SELECT COUNT(*) FROM $t_bans) AS c_bans,
            (SELECT COUNT(*) FROM $t_mutes) AS c_mutes,
            (SELECT COUNT(*) FROM $t_warnings) AS c_warnings,
            (SELECT COUNT(*) FROM $t_kicks) AS c_kicks");
        ($row = $st->fetch(PDO::FETCH_ASSOC)) or die('Failed to fetch row counts.');
        $this->count = array(
            'bans.php'     => $row['c_bans'],
            'mutes.php'    => $row['c_mutes'],
            'warnings.php' => $row['c_warnings'],
            'kicks.php'    => $row['c_kicks'],
        );
    }
}

function navbar($links) {
    echo '<ul class="nav navbar-nav">';
    foreach ($links as $page => $title) {
        $li = "li";
        if ((substr($_SERVER['SCRIPT_NAME'], -strlen($page))) === $page) {
            $li .= ' class="active"';
        }
        if ($this->page->settings->header_show_totals && isset($this->count[$page])) {
            $title .= " <span class=\"badge\">";
            $title .= $this->count[$page];
            $title .= "</span>";
        }
        echo "<$li><a href=\"$page\">$title</a></li>";
    }
    echo '</ul>';
}

function print_header() {
$settings = $this->page->settings;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="LiteBans">
    <link rel="shortcut icon" href="includes/img/minecraft.ico">
    <!-- CSS -->
    <link href="includes/css/bootstrap.min.css" rel="stylesheet">
    <link href="includes/css/custom.css" rel="stylesheet">
    <script type="text/javascript">
        function withjQuery(f) {
            if (window.jQuery) f();
            else window.setTimeout(function () {
                withjQuery(f);
            }, 100);
        }
    </script>
</head>

<header class="navbar navbar-default navbar-static-top" role="banner">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#litebans-navbar" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand"
               href="<?php echo $settings->name_link; ?>"><?php echo $settings->name; ?></a>
        </div>
        <nav id="litebans-navbar" class="collapse navbar-collapse">
            <?php
            $this->navbar(array(
                "index.php"    => "Home",
                "bans.php"     => "Bans",
                "mutes.php"    => "Mutes",
                "warnings.php" => "Warnings",
                "kicks.php"    => "Kicks",
            ));
            ?>
            <div class="nav navbar-nav navbar-right">
                <a href="https://www.spigotmc.org/resources/litebans.3715/" class="navbar-text"
                   target="_blank">&copy; LiteBans</a>
            </div>
        </nav>
    </div>
</header>
<?php
}
}

?>
