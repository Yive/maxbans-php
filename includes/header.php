<div class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><?php echo $name; ?></a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="index.php">Home</a></li>
                <li><a data-toggle="modal" data-target="#about">About</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Categories <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="bans.php">Bans</a></li>
                        <li><a href="mutes.php">Mutes</a></li>
                        <li><a href="ipbans.php">IP Bans</a></li>
                        <li><a href="warnings.php">Warnings</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>