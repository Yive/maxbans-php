 <?php
 include 'includes/head.php';
 include 'includes/header.php';
 ?>
 <head>
    <title>Index - <?php echo $name; ?></title>
  </head>
  <body>

    <div class="container content">

      <!-- Main Page -->
      <div class="jumbotron" style="background:transparent;">
        <h1>Welcome to <?php if(substr($name, -1) == "s"){ echo $name."'"; } else { echo $name."'s";} ?> Ban List</h1>
        <p>Here is where our Bans, IP-Bans, Mutes &amp; Warnings are listed.</p>
      </div>
         <div class="col-lg-6">
<?php
   // <<-----------------Database Connection------------>> //
   require 'includes/data/database.php';
   $sql = 'SELECT ip, reason, banner, time, expires FROM ipbans ORDER BY time DESC LIMIT 3';
   $retval = $conn->query($sql);
   ?>
            <h1 class="page-header">IP Bans</h1>
            <table class="table table-hover table-bordered table-condensed">
            <thead>
               <tr>
                  <th>
                     <center>IP Address</center>
                  </th>
                  <th>
                     <center>Banned By</center>
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
                  <td>
                     <?php
                        $ip = $row['ip'];
                        
                        $array = explode(".", $ip);
                        $numbers = $array[0] . "." . $array[1] . "." . $array[2];
                        $numbers .= ".";
                        
                        for($i = 0; $i < strlen($array[3]); $i++) {
                          $numbers .= "*";
                        }
                        
                        echo $numbers;
                        ?>
                  </td>
                  <td><?php echo "<img src='https://mcapi.ca/avatar/2d/" . $row['banner'] . "/25'  style='margin-bottom:5px;margin-right:5px;border-radius:2px;' />" . $row['banner'];?></td>
               </tr>
               <?php }
                  $conn->close();
                  echo "</tbody></table>";
                  ?>
         </div>
      <div class="col-lg-6">
<?php
   // <<-----------------Database Connection------------>> //
   require 'includes/data/database.php';
   $sql = 'SELECT name, reason, banner, time, expires FROM bans ORDER BY time DESC LIMIT 3';
   $retval = $conn->query($sql);
   ?>
            <h1 class="page-header">Bans</h1>
            <table class="table table-hover table-bordered table-condensed">
            <thead>
               <tr>
                  <th>
                     <center>Name</center>
                  </th>
                  <th>
                     <center>Banned By</center>
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
                  <td><?php echo "<img src='https://mcapi.ca/avatar/2d/" . $row['name'] . "/25' style='margin-bottom:5px;margin-right:5px;border-radius:2px;' />" . $row['name'];?></td>
                  <td><?php echo "<img src='https://mcapi.ca/avatar/2d/" . $row['banner'] . "/25'  style='margin-bottom:5px;margin-right:5px;border-radius:2px;' />" . $row['banner'];?></td>
               </tr>
               <?php }
                  $conn->close();
                  echo "</tbody></table>";
                  ?>
      </div>
      <div class="col-lg-6">
<?php
   // <<-----------------Database Connection------------>> //
   require 'includes/data/database.php';
   $sql = 'SELECT name, reason, muter, time, expires FROM mutes ORDER BY time DESC LIMIT 3';
   $retval = $conn->query($sql);
   ?>
            <h1 class="page-header">Mutes</h1>
            <table class="table table-hover table-bordered table-condensed">
            <thead>
               <tr>
                  <th>
                     <center>Name</center>
                  </th>
                  <th>
                     <center>Muted By</center>
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
                  <td><?php echo "<img src='https://mcapi.ca/avatar/2d/" . $row['name'] . "/25' style='margin-bottom:5px;margin-right:5px;border-radius:2px;' />" . $row['name'];?></td>
                  <td><?php echo "<img src='https://mcapi.ca/avatar/2d/" . $row['banner'] . "/25'  style='margin-bottom:5px;margin-right:5px;border-radius:2px;' />" . $row['banner'];?></td>
               </tr>
               <?php }
                  $conn->close();
                  echo "</tbody></table>";
                  ?>
      </div>
      <div class="col-lg-6">
<?php
   // <<-----------------Database Connection------------>> //
   require 'includes/data/database.php';
   $sql = 'SELECT name, reason, banner, expires FROM warnings ORDER BY expires DESC LIMIT 3';
   $retval = $conn->query($sql);
   ?>
      <h1 class="page-header">Warnings</h1>
      <table class="table table-hover table-bordered table-condensed">
      <thead>
         <tr>
            <th>
               <center>Name</center>
            </th>
            <th>
               <center>Warned By</center>
            </th>
         </tr>
      </thead>
      <tbody>
               <?php while($row = $retval->fetch_assoc()) {
            if($row['banner'] == null) {
               $row['banner'] = 'Console';
            }
                  // <<-----------------Expiration Time Converter------------>> //
                  $expiresEpoch = $row['expires'];
                  $expiresConvert = $expiresEpoch / 1000;
                  $expiresResult = date('F j, Y, g:i a', $expiresConvert);
            ?>
         <tr>
            <td><?php echo "<img src='https://mcapi.ca/avatar/2d/" . $row['name'] . "/25' style='margin-bottom:5px;margin-right:5px;border-radius:2px;' />" . $row['name'];?></td>
            <td><?php echo "<img src='https://mcapi.ca/avatar/2d/" . $row['banner'] . "/25'  style='margin-bottom:5px;margin-right:5px;border-radius:2px;' />" . $row['banner'];?></td>
         </tr>
         <?php }
            $conn->close();
            echo "</tbody></table>";
            ?>
      </div>
    </div> <!-- /container -->
<?php
include 'includes/footer.php';
?>
