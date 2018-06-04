<head><link rel="stylesheet" href="includes/tablestyle.css"></head>
<?php
   // <<-----------------Database Connection------------>> //
   require 'includes/data/database.php';
   $sql = 'SELECT name, reason, banner, time, expires FROM bans ORDER BY time DESC LIMIT 20';
   $retval = $conn->query($sql);
   ?>
<body>
	<table class="col-sm-12 table-condensed">
		<thead>
			<tr>
				<th>
					<center>Name</center>
				</th>
				<th>
					<center>Banned By</center>
				</th>
				<th>
					<center>Reason</center>
				</th>
				<th>
					<center>Banned On</center>
				</th>
				<th>
					<center>Banned Until</center>
				</th>
			</tr>
			</thead>
			<tbody>
			   <?php while($row = $retval->fetch_assoc()) { 
				  if($row['banner'] == null) {
					 $row['banner'] = 'Console';
				  }
				  // <<-----------------Ban Date Converter------------>> //
				  $timeEpoch = $row['time'];
				  $timeConvert = $timeEpoch / 1000;
				  $timeResult = date('F j, Y, g:i a', $timeConvert);
				  // <<-----------------Expiration Time Converter------------>> //
				  $expiresEpoch = $row['expires'];
				  $expiresConvert = $expiresEpoch / 1000;
				  $expiresResult = date('F j, Y, g:i a', $expiresConvert);
				  ?>
			   <tr>
				  <td><?php echo "<img src='https://cravatar.eu/avatar/" . $row['name'] . "/25' style='margin-bottom:5px;margin-right:5px;border-radius:2px;' />" . $row['name'];?></td>
				  <td><?php echo "<img src='https://cravatar.eu/avatar/" . $row['banner'] . "/25'  style='margin-bottom:5px;margin-right:5px;border-radius:2px;' />" . $row['banner'];?></td>
				  <td style="width: 30%;"><center><?php echo $row['reason'];?></center></td>
				  <td><?php echo $timeResult;?></td>
				  <td><center><?php if($row['expires'] == 0) {
					 echo 'Permanent Ban';
					 } else {
					 echo $expiresResult; }?></center></td>
			   </tr>
			   <?php }
				  $conn->close();
				  echo "</tbody></table>";
				  ?>
