<?php
   include 'includes/head.php';
   include 'includes/header.php';
   ?>
<head>
   <title>Bans/Tempbans - <?php echo $name; ?></title>
</head>
<?php
   // <<-----------------Database Connection------------>> //
   $page = 1;
   if(!empty($_GET['p'])) {
      $page = $_GET['p'];
   }   
   $limit = 20;
   $offset = ($page - 1) * $limit;

   require 'includes/data/database.php';
   $sql = 'SELECT name, reason, banner, time, expires FROM bans ORDER BY time DESC LIMIT 20';
   $retval = $conn->query($sql);
   ?>
<body>
   <div class="container content">
      <div class="row">
         <div class="col-lg-12">
            <h1 class="page-header">Bans</h1>
            
            <?php
            $next = ($page + 1);
            $previous = ($page - 1);
            echo '<a href="bans.php?p='.$previous.'">Previous Page</a> | <a href="bans.php?p='.$next.'">Next Page</a>';
            ?>
            
            <table class="table table-hover table-bordered table-condensed">
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
                  <td><?php echo "<img src='https://mcapi.ca/avatar/2d/" . $row['name'] . "/25' style='margin-bottom:5px;margin-right:5px;border-radius:2px;' />" . $row['name'];?></td>
                  <td><?php echo "<img src='https://mcapi.ca/avatar/2d/" . $row['banner'] . "/25'  style='margin-bottom:5px;margin-right:5px;border-radius:2px;' />" . $row['banner'];?></td>
                  <td style="width: 30%;"><?php echo $row['reason'];?></td>
                  <td><?php echo $timeResult;?></td>
                  <td><?php if($row['expires'] == 0) {
                     echo 'Permanent Ban';
                     } else {
                     echo $expiresResult; }?></td>
               </tr>
               <?php }
                  $conn->close();
                  echo "</tbody></table>";
                  ?>
         </div>
      </div>
      <?php include 'includes/footer.php'; ?>
   </div>
