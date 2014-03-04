<?php 
  $data = json_decode( file_get_contents('http://minecraft-api.com/v1/get/?server=' .$serverip ), true);
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
         <p class="navbar-text" style="font-size:15px;color:white;">&copy; <a href="http://dev.bukkit.org/profiles/netherfoam/" target="_blank">Plugin Creator</a> | <a href="http://dev.bukkit.org/profiles/MCTempestCraft" target="_blank">PHP Script Creator</a></p>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
			 <p class="navbar-text" style="color:white;"><?php echo 'Players: ' . $data['players']['online'] . '/' . $data['players']['max']; ?> </p>
          </ul>
        </div>
      </div>
    </div>
<div class="modal fade" id="about" tabindex="-1" role="dialog" aria-labelledby="aboutlabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="aboutlabel">About Script</h4>
      </div>
      <div class="modal-body">
<div class="well well-sm">
    <h4>Credits</h4>
    <p><a href="http://dev.bukkit.org/profiles/netherfoam/" target="_blank">netherfoam - Plugin Developer</a>
	<br/>
	<a href="http://dev.bukkit.org/profiles/MCTempestCraft/" target="_blank">TempestCraft - PHP Developer</a>
	<br/>
	<a href="https://twitter.com/boboman_13" target="_blank">boboman_13 - Helped with censoring IPs</a>
	<br/>
	<a href="https://twitter.com/Senither" target="_blank">Alexis Tan - API service that provides the player count in the bottom right.</a>
	</p>
</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="includes/js/bootstrap.min.js"></script>