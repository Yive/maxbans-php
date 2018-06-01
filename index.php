<?php include "include/settings.php"; ?>
<!DOCTYPE html>
<html>
	<head>
		<?php echo "<title>".$name"</title>" ?>
		<link rel="stylesheet" href="include/css/style.css">
		<link href='https://fonts.googleapis.com/css?family=Martel Sans' rel='stylesheet'>
		<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	<body>
		<div id="main" class="col-sm-12 container-fluid">
		<?php
		if($_GET["bans"] === "") { include 'bans.php'; }
		else if($_GET["ipbans"] === "") { include 'ipbans.php'; }
		else if($_GET["mutes"] === "") { include 'mutes.php'; }
		else if($_GET["warn"] === "") { include 'warnings.php'; }
		else { include 'bans.php'; }
		?>
		</div>
	</body>
</html>