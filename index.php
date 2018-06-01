<?php include "includes/settings.php"; ?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="includes/style.css">
		<link href='https://fonts.googleapis.com/css?family=Martel Sans' rel='stylesheet'>
		<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php echo "<style>body {background-image:url($background);}</style>;"?>
	</head>
	<body>
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<div class="navbar-header">
					<a class="navbar-brand" href="#"><?php echo $name;?></a>
				</div>
				<ul class="nav navbar-nav">
					<li><a href="?bans">Bans</a></li>
					<li><a href="?ipbans">IP-bans</a></li>
					<li><a href="?mutes">Mutes</a></li>
					<li><a href="?warn">Warnings</a></li>
				</ul>
			</div>
		</nav>
		<div id="main" class="col-sm-11">
		<?php
		if($_GET["bans"] === "") { include 'bans.php'; echo "<title>$name - Bans</title>";}
		else if($_GET["ipbans"] === "") { include 'ipbans.php'; echo "<title>$name - IP-Bans</title>";}
		else if($_GET["mutes"] === "") { include 'mutes.php'; echo "<title>$name - Mutes</title>";}
		else if($_GET["warn"] === "") { include 'warnings.php'; echo "<title>$name - Warns</title>";}
		else { include 'bans.php'; }
		?>
		</div>
	</body>
</html>
