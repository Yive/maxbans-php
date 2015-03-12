<?php require 'includes/page.php'; ?>
<title>Tempbans - <?php echo $name; ?></title>
<div class="container">
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
                <?php
                global $table_bans, $conn;
                $result = run_query($table_bans);
                while ($row = $result->fetch_assoc()) {
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
                                $expiresResult = 'Permanent Ban';
                            }
                            if ($row['active'] == 0) {
                                $expiresResult .= ' (Unbanned)';
                            }
                            echo $expiresResult;
                            ?>
                        </td>
                    </tr>
                <?php }
                $result->free();
                $conn->close();
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</div>