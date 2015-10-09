<?php
namespace litebans;

require_once './includes/page.php';
$page = new Page("index");
$page->print_title();

?>
<div class="container">
    <div class="jumbotron">
        <div style="text-align: center;"><h2>Welcome to <?php echo $page->settings->name; ?>'s Ban List.</h2></div>

        <div style="text-align: center;"><p>Here is where all of our punishments are listed.</p></div>
    </div>
</div>
<?php $page->print_footer(false); ?>
