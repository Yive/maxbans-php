<?php $data = json_decode( file_get_contents('https://mcapi.ca/query/' .$serverip. '/info' ), true); ?>
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
            <li><a href="index">Home</a></li>
            <li><a data-toggle="modal" data-target="#about">About</a></li>
            <li class="dropdown">
               <a href="#" class="dropdown-toggle" data-toggle="dropdown">Categories <b class="caret"></b></a>
               <ul class="dropdown-menu">
                  <li><a href="bans">Bans</a></li>
                  <li><a href="mutes">Mutes</a></li>
                  <li><a href="ipbans">IP Bans</a></li>
                  <li><a href="warnings">Warnings</a></li>
               </ul>
            </li>
         </ul>
         <ul class="nav navbar-nav navbar-right">
            <p class="navbar-text" style="color:white;"><?php echo 'Players Online: ' . $data['players']['online'] . '/' . $data['players']['max']; ?> </p>
         </ul>
      </div>
   </div>
</div>