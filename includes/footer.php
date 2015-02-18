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
                    href="http://www.spigotmc.org/resources/litebans.3715/" target="_blank">LiteBans</a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <p class="navbar-text"
                   style="color:white;"><?php echo 'Players: ' . $data['players']['online'] . '/' . $data['players']['max']; ?> </p>
            </ul>
        </div>
    </div>
</div>
<div class="modal" id="about" tabindex="-1" role="dialog" aria-labelledby="aboutlabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="aboutlabel">About Script</h4>
            </div>
            <div class="modal-body">
                <div class="well well-sm">
                    <h4>Credits</h4>
                    <a href="http://www.spigotmc.org/resources/litebans.3715/" target="_blank">Ruan - LiteBans
                        Developer</a>
                    <br>
                    <a href="http://twitter.com/ItsYive" target="_blank">Yive - Original web interface design</a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -->
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="includes/js/bootstrap.min.js"></script>
