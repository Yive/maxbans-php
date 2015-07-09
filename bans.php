<?php require './includes/page.php'; ?>
<title>Tempbans - <?php echo $name; ?></title>
<div class="container">
    <?php
    print_page_header("Bans");
    print_check_form("bans");
    ?>
    <div class="row" style="margin-bottom:60px;">
        <div class="col-lg-12">
            <table class="table table-hover table-bordered table-condensed">
                <?php
                print_table_headers(array("Name", "Banned By", "Reason", "Banned On", "Banned Until"));
                global $table_bans, $conn, $show_inactive_bans;
                $result = run_query($table_bans);
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
                                $until = 'Permanent Ban';
                            }
                            if ($show_inactive_bans && !$row['active']) {
                                $until .= ' (Unbanned)';
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