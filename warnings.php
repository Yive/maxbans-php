<?php require './includes/page.php'; ?>
<title>Warnings - <?php echo $name; ?></title>
<div class="container">
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
                    <th>
                        <div style="text-align: center;">Received Warning?</div>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php
                global $table_warnings, $conn, $show_inactive_bans;
                $result = run_query($table_warnings);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $expiresResult = millis_to_date($row['until']);
                    ?>
                    <tr>
                        <td><?php echo get_avatar($row['name']); ?></td>
                        <td><?php echo get_avatar($row['banned_by_name']); ?></td>
                        <td style="width: 30%;"><?php echo clean($row['reason']); ?></td>
                        <td>
                            <?php if ($row['until'] <= 0) {
                                $expiresResult = 'Permanent Warning';
                            }
                            if ($show_inactive_bans && $row['active'] == 0) {
                                $expiresResult .= ' (Expired)';
                            }
                            echo $expiresResult;
                            ?>
                        </td>
                        <td>
                            <?php echo $row['warned'] == 1 ? "Yes" : "No"; ?>
                        </td>
                    </tr>
                <?php }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php include './includes/footer.php'; ?>
</div>