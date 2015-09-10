<?php
namespace litebans;
use PDO;

require_once './includes/page.php';
$page = new Page("warnings");
$page->print_title();
$headers = array("Name", "Warned By", "Reason", "Warned Until", "Received Warning?");
?>
<div class="container">
    <?php
    $page->print_page_header();
    ?>
    <div class="row" style="margin-bottom:60px;">
        <div class="col-lg-12">
            <?php
            $page->table_begin();
            $page->table_print_headers($headers);
            $result = $page->run_query();
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $player_name = $page->get_name($row['uuid']);
                if ($player_name === null) continue;

                $page->print_table_rows($row, array(
                    'Name'              => $page->get_avatar($player_name),
                    'Warned By'         => $page->get_avatar($page->get_banner_name($row)),
                    'Reason'            => $page->clean($row['reason']),
                    'Warned Until'      => $page->expiry($row),
                    'Received Warning?' => $row['warned'] ? "Yes" : "No",
                ));
            }
            $page->table_end();
            $page->print_pager();
            ?>
        </div>
    </div>
    <?php $page->print_footer(); ?>
</div>
