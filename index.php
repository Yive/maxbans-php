<?php
include './includes/head.php';
include './includes/header.php';
include_once './includes/settings.php';
?>
<title>Index - <?php echo $settings->name; ?></title>
<div class="container">
    <div class="jumbotron">
        <div style="text-align: center;"><h2>Welcome to <?php echo $settings->name; ?>'s Ban List.</h2></div>

        <div style="text-align: center;"><p>Here is where our Bans, Mutes, and Warnings are listed.</p></div>
    </div>

</div>
<?php include './includes/footer.php'; ?>
