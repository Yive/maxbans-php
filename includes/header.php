<?php
namespace litebans;

require_once './includes/settings.php';
$settings = new Settings(false);
?>
<div class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="<?php echo $settings->name_link; ?>"><?php echo $settings->name; ?></a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="index.php">Home</a></li>
                <li><a href="bans.php">Bans</a></li>
                <li><a href="mutes.php">Mutes</a></li>
                <li><a href="warnings.php">Warnings</a></li>
                <li><a href="kicks.php">Kicks</a></li>
            </ul>
            <p class="navbar-text" style="float: right; font-size:15px;color:white;">&copy;
                <a href="https://www.spigotmc.org/resources/litebans.3715/" target="_blank">LiteBans</a>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="includes/js/bootstrap.min.js"></script>
