<?php include 'includes/head.php'; ?>
<?php include 'includes/header.php'; ?>
<head>
    <title>Warnings - <?php echo $name; ?></title>
</head>
<?php
// <<-----------------mysql Database Connection------------>> //
require 'includes/data/database.php';

$sql = 'SELECT name, reason, banner, expires FROM warnings ORDER BY expires DESC LIMIT 20';

$retval = mysql_query($sql, $conn);
if (!$retval) {
    die('Could not get data: ' . mysql_error());
}
?>
<body>
<div class="container">
    <!-- Example row of columns -->
    <div class="row">

        <div class="col-lg-12">
            <h1 class="page-header">Warnings</h1>
            <ol class="breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li class="active">Warnings</li>
            </ol>
        </div>

    </div>
    <div class="row" style="margin-bottom:60px;">
        <div class="col-lg-12">
            <table class="table table-hover table-bordered table-condensed">
                <thead>
                <tr>
                    <th>
                        <div style="text-align: center;">Name</div>
                    </th>
                    <th>
                        <div style="text-align: center;">Warned By</div>
                    </th>
                    <th>
                        <div style="text-align: center;">Reason</div>
                    </th>
                    <th>
                        <div style="text-align: center;">Warned Until</div>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = mysql_fetch_assoc($retval)) {
                    // <<-----------------Expiration Time Converter------------>> //
                    $expiresEpoch = $row['expires'];
                    $expiresConvert = $expiresEpoch / 1000;
                    $expiresResult = date('F j, Y, g:i a', $expiresConvert);
                    ?>
                    <tr>
                        <td><?php echo "<img src='https://minotar.net/avatar/" . $row['name'] . "/25' style='margin-bottom:5px;margin-right:5px;border-radius:2px;' />" . $row['name']; ?></td>
                        <td><?php echo "<img src='https://minotar.net/avatar/" . $row['banner'] . "/25'  style='margin-bottom:5px;margin-right:5px;border-radius:2px;' />" . $row['banner']; ?></td>
                        <td style="width: 30%;"><?php echo $row['reason']; ?></td>
                        <td><?php if ($row['expires'] == 0) {
                                echo 'Permanent Warning';
                            } else {
                                echo $expiresResult;
                            } ?></td>
                    </tr>
                <?php }
                mysql_close($conn);
                echo "</tbody></table>";
                ?>
        </div>
    </div>
    <?php
    include 'includes/footer.php';
    ?>
</div>