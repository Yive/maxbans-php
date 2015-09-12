<?php
namespace litebans;

use PDO;

require_once './includes/page.php';
$page = new Page("bans");
$page->print_title();
$headers = array("Name", "Banned By", "Reason", "Banned On", "Banned Until");

$page->print_page_header();

$page->print_check_form();

$page->table_begin();
$page->table_print_headers($headers);

$result = $page->run_query();
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $player_name = $page->get_name($row['uuid']);
    if ($player_name === null) continue;

    $page->print_table_rows($row, array(
        'Name'         => $page->get_avatar($player_name, $row['uuid']),
        'Banned By'    => $page->get_avatar($page->get_banner_name($row), $row['banned_by_uuid']),
        'Reason'       => $page->clean($row['reason']),
        'Banned On'    => $page->millis_to_date($row['time']),
        'Banned Until' => $page->expiry($row),
    ));
}
$page->table_end();
$page->print_pager();

$page->print_footer();
