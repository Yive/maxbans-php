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
        <p><?php echo $statement; ?></p>
      </div>
    </div> <!-- /container -->
<?php
include 'includes/footer.php';
?>
