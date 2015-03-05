<?php
   include 'includes/head.php';
   include 'includes/header.php';
   ?>
<head>
   <title>Warnings - <?php echo $name; ?></title>
</head>
<?php
   // <<-----------------Database Connection------------>> //
   require 'includes/data/database.php';
   $sql = 'SELECT name, reason, banner, expires FROM warnings ORDER BY expires DESC LIMIT 20';
   $retval = $conn->query($sql);
   ?>
<body>
   <div class="container content">
   <div class="row">
   <div class="col-lg-12">
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
            <th>
               <center>Reason</center>
            </th>
            <th>
               <center>Warned Until</center>
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
            <td style="width: 30%;"><?php echo $row['reason'];?></td>
            <td><?php if($row['expires'] == 0) {
               echo 'Permanent Warning';
               } else {
               echo $expiresResult; }?></td>
         </tr>
         <?php }
            $conn->close();
            echo "</tbody></table>";
            ?>
   </div>
   <?php include 'includes/footer.php'; ?>