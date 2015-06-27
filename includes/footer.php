<?php
$data = json_decode(file_get_contents('https://mcapi.ca/v2/query/info/?ip=' . $serverip), true);
?>
<div class="navbar navbar-default navbar-fixed-bottom" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <p class="navbar-text" style="font-size:15px;color:white;">&copy; <a
                    href="https://www.spigotmc.org/resources/litebans.3715/" target="_blank">LiteBans</a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <p class="navbar-text"
                   style="color:white;"><?php echo 'Players: ' . $data['players']['online'] . '/' . $data['players']['max']; ?> </p>
            </ul>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="includes/js/bootstrap.min.js"></script>
