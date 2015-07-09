<?php require './includes/page.php'; ?>
<title>TempMutes - <?php echo $name; ?></title>
<div class="container">
    <?php
    print_page_header("Mutes");
    ?>
    <div class="row" style="margin-bottom:60px;">
        <div class="col-lg-12">
            <table class="table table-hover table-bordered table-condensed">
                <?php
                print_table_headers(array("Name", "Muted By", "Reason", "Muted On", "Muted Until"));
                global $table_mutes, $conn, $show_inactive_bans;
                $result = run_query($table_mutes);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $player_name = get_name($row['uuid']);
                    if ($player_name === null) continue;
                    $until = millis_to_date($row['until']);
                    ?>
                    <tr>
                        <td><?php echo get_avatar($player_name); ?></td>
                        <td><?php echo get_avatar(get_banner_name($row)); ?></td>
                        <td style="width: 30%;"><?php echo clean($row['reason']); ?></td>
                        <td><?php echo millis_to_date($row['time']); ?></td>
                        <td>
                            <?php if ($row['until'] <= 0) {
                                $until = 'Permanent Mute';
                            }
                            if ($show_inactive_bans && !$row['active']) {
                                $until .= ' (Unmuted)';
                            }
                            echo $until;
                            ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
    <?php include './includes/footer.php'; ?>
</div>