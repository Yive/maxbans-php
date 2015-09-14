<?php
namespace litebans;

use PDO;

require_once './includes/page.php';
$page = new Page("kicks");
$page->print_title();
$headers = array("Name", "Kicked By", "Reason", "Date");

$page->print_page_header();

$page->print_check_form();

$page->table_begin();
$page->table_print_headers($headers);

$result = $page->run_query();
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $player_name = $page->get_name($row['uuid']);
    if ($player_name === null) continue;

    $page->print_table_rows($row, array(
        'Name'      => $page->get_avatar($player_name, $row['uuid']),
        'Kicked By' => $page->get_avatar($page->get_banner_name($row), $row['banned_by_uuid']),
        'Reason'    => $page->clean($row['reason']),
        'Date'      => $page->millis_to_date($row['time']),
    ));
}
$page->table_end();
$page->print_pager();

$page->print_footer();
