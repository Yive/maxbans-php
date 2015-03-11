<head>
    <title>TempMutes - <?php echo $name; ?></title>
</head>
<?php
require 'includes/page.php';

$table = $table_mutes;
$sql = 'SELECT * FROM ' . $table . ' INNER JOIN ' . $table_history . ' on ' . $table . '.uuid=' . $table_history . '.uuid ' . $active_query .
    ' GROUP BY name ORDER BY time DESC LIMIT ' . $limit_per_page;

if (!$result = $conn->query($sql)) {
    die('Query error [' . $conn->error . ']');
}
?>
<body>
<div class="container">
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
                <?php while ($row = $result->fetch_assoc()) {
                    // <<-----------------Ban Date Converter------------>> //
                    date_default_timezone_set("UTC");
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
                        <td><?php $banner = get_banner_name($row['banned_by_name']);
                            echo "<img src='https://minotar.net/avatar/" . $banner . "/25'  style='margin-bottom:5px;margin-right:5px;border-radius:2px;' />" . $banner; ?></td>
                        <td style="width: 30%;"><?php echo $row['reason']; ?></td>
                        <td><?php echo $timeResult; ?></td>
                        <td>
                            <?php if ($row['until'] <= 0) {
                                $expiresResult = 'Permanent Mute';
                            }
                            if ($row['active'] == 0) {
                                $expiresResult .= ' (Unmuted)';
                            }
                            echo $expiresResult;
                            ?>
                        </td>
                    </tr>
                <?php }
                $result->free();
                echo "</tbody></table>";
                ?>
        </div>
    </div>
    <?php
    include 'includes/footer.php';
    ?>
</div>