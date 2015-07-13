<?php
require_once './includes/page.php';
$page = new Page();
?>
<title>Warnings - <?php echo $page->settings->name; ?></title>
<div class="container">
    <?php
    $page->print_page_header("Warnings");
    ?>
    <div class="row" style="margin-bottom:60px;">
        <div class="col-lg-12">
            <table class="table table-hover table-bordered table-condensed">
                <?php
                $page->print_table_headers(array("Name", "Warned By", "Reason", "Warned Until", "Received Warning?"));
                $result = $page->run_query($page->settings->table_warnings);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $player_name = $page->get_name($row['uuid']);
                    if ($player_name === null) continue;
                    $until = $page->millis_to_date($row['until']);
                    ?>
                    <tr>
                        <td><?php echo $page->get_avatar($player_name); ?></td>
                        <td><?php echo $page->get_avatar($page->get_banner_name($row)); ?></td>
                        <td style="width: 30%;"><?php echo $page->clean($row['reason']); ?></td>
                        <td>
                            <?php if ($row['until'] <= 0) {
                                $until = 'Permanent Warning';
                            }
                            if ($page->settings->show_inactive_bans && !$row['active']) {
                                $until .= ' (Expired)';
                            }
                            echo $until;
                            ?>
                        </td>
                        <td>
                            <?php echo $row['warned'] ? "Yes" : "No"; ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
    <?php include './includes/footer.php'; ?>
</div>
