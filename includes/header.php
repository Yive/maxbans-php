<?php
namespace litebans;

require_once './includes/settings.php';
$settings = new Settings(false);

function navbar($links) {
    echo '<ul class="nav navbar-nav">';
    foreach ($links as $page => $title) {
        $li = "li";
        if ((substr($_SERVER['SCRIPT_NAME'], -strlen($page))) === $page) {
            $li .= ' class="active"';
        }
        echo "<$li><a href=\"$page\">$title</a></li>";
    }
    echo '</ul>';
}

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
    <link href="includes/css/bootstrap.css" rel="stylesheet">
    <link href="includes/css/custom.css" rel="stylesheet">
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
            <a class="navbar-brand" href="<?php echo $settings->name_link; ?>"><?php echo $settings->name; ?></a>
        </div>
        <nav id="litebans-navbar" class="collapse navbar-collapse">
            <?php
            navbar(array(
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
