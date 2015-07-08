<?php require './includes/page.php'; ?>
<title>Tempbans - <?php echo $name; ?></title>
<div class="container">
    <?php print_page_header("Bans"); ?>
    <br/>
    <!-- Ban check form -->
    <form onsubmit="captureForm(event);" class="form-inline">
        <div class="form-group">
            <input type="text" class="form-control" id="user" placeholder="Player">
        </div>
        <button type="submit" class="btn btn-default">Check</button>
    </form>
    <script type="text/javascript">
        function captureForm(e) {
            $.ajax({
                type: 'POST',
                url: 'check.php',
                data: {name: document.getElementById('user').value, table: 'bans'}
            }).done(function (msg) {
                document.getElementById('output').innerHTML = msg;
            });
            e.preventDefault();
            return false;
        }
    </script>
    <div id="output"></div>
    <!-- End ban check form -->
    <div class="row" style="margin-bottom:60px;">
        <div class="col-lg-12">
            <table class="table table-hover table-bordered table-condensed">
                <?php
                print_table_headers(array("Name", "Banned By", "Reason", "Banned On", "Banned Until"));
                global $table_bans, $conn, $show_inactive_bans;
                $result = run_query($table_bans);
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
                                $expiresResult = 'Permanent Ban';
                            }
                            if ($show_inactive_bans && !$row['active']) {
                                $expiresResult .= ' (Unbanned)';
                            }
                            echo $expiresResult;
                            ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
    <?php include './includes/footer.php'; ?>
</div>