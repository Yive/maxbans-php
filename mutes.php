<?php require './includes/page.php'; ?>
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
                <?php
                print_table_headers(array("Name", "Muted By", "Reason", "Muted On", "Muted Until"));
                global $table_mutes, $conn, $show_inactive_bans;
                $result = run_query($table_mutes);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $timeResult = millis_to_date($row['time']);
                    $expiresResult = millis_to_date($row['until']);
                    ?>
                    <tr>
                        <td><?php echo get_avatar(get_name($row['uuid'])); ?></td>
                        <td><?php echo get_avatar(get_banner_name($row)); ?></td>
                        <td style="width: 30%;"><?php echo clean($row['reason']); ?></td>
                        <td><?php echo $timeResult; ?></td>
                        <td>
                            <?php if ($row['until'] <= 0) {
                                $expiresResult = 'Permanent Mute';
                            }
                            if ($show_inactive_bans && !$row['active']) {
                                $expiresResult .= ' (Unmuted)';
                            }
                            echo $expiresResult;
                            ?>
                        </td>
                    </tr>
                <?php }
                ?>
            </table>
        </div>
    </div>
    <?php include './includes/footer.php'; ?>
</div>