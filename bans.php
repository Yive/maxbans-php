<?php include 'includes/head.php'; ?>
<?php include 'includes/header.php'; ?>
<head>
    <title>Bans/Tempbans - <?php echo $name; ?></title>
</head>
<?php
// <<-----------------mysql Database Connection------------>> //
require 'includes/data/database.php';

$sql = 'SELECT time,until,reason,name,banned_by_name FROM bans INNER JOIN history on bans.uuid=history.uuid WHERE active=1 ORDER BY time DESC LIMIT 20';

$retval = mysql_query($sql, $conn);
if (!$retval) {
    die('Could not get data: ' . mysql_error($conn));
}
?>
<body>
<div class="container">
    <!-- Example row of columns -->
    <div class="row">

        <div class="col-lg-12">
            <h1 class="page-header">Bans</h1>
            <ol class="breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li class="active">Bans</li>
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
                        <div style="text-align: center;">Banned By</div>
                    </th>
                    <th>
                        <div style="text-align: center;">Reason</div>
                    </th>
                    <th>
                        <div style="text-align: center;">Banned On</div>
                    </th>
                    <th>
                        <div style="text-align: center;">Banned Until</div>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = mysql_fetch_assoc($retval)) {
                    // <<-----------------Ban Date Converter------------>> //
                    $timeEpoch = $row['time'];
                    $timeConvert = $timeEpoch / 1000;
                    $timeResult = date('F j, Y, g:i a', $timeConvert);
                    // <<-----------------Expiration Time Converter------------>> //
                    $expiresEpoch = $row['until'];
                    $expiresConvert = $expiresEpoch / 1000;
                    $expiresResult = date('F j, Y, g:i a', $expiresConvert);
                    ?>
                    <tr>
                        <td><?php $banned = $row['history.name'];
                            echo "<img src='https://minotar.net/avatar/" . $banned . "/25' style='margin-bottom:5px;margin-right:5px;border-radius:2px;' />" . $banned; ?></td>
                        <td><?php $banner = $row['banned_by_name'];
                            echo "<img src='https://minotar.net/avatar/" . $banner . "/25'  style='margin-bottom:5px;margin-right:5px;border-radius:2px;' />" . $row['banned_by_name']; ?></td>
                        <td style="width: 30%;"><?php echo $row['reason']; ?></td>
                        <td><?php echo $timeResult; ?></td>
                        <td><?php if ($row['expires'] <= 0) {
                                echo 'Permanent Ban';
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