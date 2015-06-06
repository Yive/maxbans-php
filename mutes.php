<?php require 'includes/page.php'; ?>
<title>TempMutes - <?php echo $name; ?></title>
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
                <?php
                global $table_mutes, $conn;
                $result = run_query($table_mutes);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    date_default_timezone_set("UTC");
                    $timeResult = date('F j, Y, g:i a', $row['time'] / 1000);
                    $expiresResult = date('F j, Y, g:i a', $row['until'] / 1000);
                    ?>
                    <tr>
                        <td><?php echo get_avatar($row['name']); ?></td>
                        <td><?php echo get_avatar($row['banned_by_name']); ?></td>
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
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</div>