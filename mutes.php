<?php include 'includes/head.php'; ?>
<?php include 'includes/header.php'; ?>
<head>
    <title>Mutes/TempMutes - <?php echo $name; ?></title>
</head>
<?php
// <<-----------------mysql Database Connection------------>> //
require 'includes/data/database.php';

$sql = 'SELECT time,until,reason,name,banned_by_name FROM mutes INNER JOIN history on mutes.uuid=history.uuid WHERE active=1 GROUP BY name ORDER BY time DESC LIMIT 20';

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
            <h1 class="page-header">Mutes</h1>
            <ol class="breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li class="active">Mutes</li>
            </ol>
        </div>

    </div>
    <div class="row" style="margin-bottom:60px;">
        <div class="col-lg-12">
            <table class="table table-hover table-bordered table-condensed">
                <thead>
                <tr>
                    <th style="text-align: center;">Name</th>
                    <th style="text-align: center;">Muted By</th>
                    <th style="text-align: center;">Reason</th>
                    <th style="text-align: center;">Muted On</th>
                    <th style="text-align: center;">Muted Until</th>
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
                        <td><?php echo "<img src='https://minotar.net/avatar/" . $row['name'] . "/25' style='margin-bottom:5px;margin-right:5px;border-radius:2px;' />" . $row['name']; ?></td>
                        <td><?php echo "<img src='https://minotar.net/avatar/" . $row['banned_by_name'] . "/25'  style='margin-bottom:5px;margin-right:5px;border-radius:2px;' />" . $row['banned_by_name']; ?></td>
                        <td style="width: 30%;"><?php echo $row['reason']; ?></td>
                        <td><?php echo $timeResult; ?></td>
                        <td><?php if ($row['until'] <= 0) {
                                echo 'Permanent Mute';
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