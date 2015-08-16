<?php
require_once './includes/page.php';
$page = new Page();
?>
<title>Tempbans - <?php echo $page->settings->name; ?></title>
<div class="container">
    <?php
    $page->print_page_header("Kicks");
    ?>
    <div class="row" style="margin-bottom:60px;">
        <div class="col-lg-12">
            <table class="table table-hover table-bordered table-condensed">
                <?php
                $page->print_table_headers(array("Name", "Kicked By", "Reason", "Date"));
                $result = $page->run_query($page->settings->table_kicks);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $player_name = $page->get_name($row['uuid']);
                    if ($player_name === null) continue;
                    ?>
                    <tr>
                        <td><?php echo $page->get_avatar($player_name); ?></td>
                        <td><?php echo $page->get_avatar($page->get_banner_name($row)); ?></td>
                        <td style="width: 30%;"><?php echo $page->clean($row['reason']); ?></td>
                        <td><?php echo $page->millis_to_date($row['time']); ?></td>
                    </tr>
                <?php } ?>
            </table>
            <?php $page->print_pager("kicks.php", $page->settings->table_kicks); ?>
        </div>
    </div>
    <?php $page->print_footer(); ?>
</div>
