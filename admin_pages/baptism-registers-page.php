<?php
require_once(plugin_dir_path(__FILE__) . '../classes/class.baptism-registers-table.php');  
//Create an instance of our package class...
$testListTable = new Baptism_Registers_Table();
//Fetch, prepare, sort, and filter our data...
$testListTable->prepare_items();

?>
    <div class="wrap">

        <h2>Baptism Pre-registers</h2>
        <img width=200 src="<?php echo plugin_dir_url(__FILE__) . 'media/images/outline-logo-b.png' ?>" />

        <form action="admin-post.php" target="_blank" id="registries_export" method="post">
            <h3>Generate Sitting Chart</h3>
            <input type="hidden" name="action" value="export_registries">
            <?php wp_nonce_field('placita_export_registries'); ?>
            <span>Date:</span><input type="text" class="registries_export_date" name="export_date">
            <button type="submit"class="button-primary">Generate</button>
        </form>

        <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
        <form id="movies-filter" method="get">
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <!-- Search Form -->
            <?php $testListTable->search_box('Search', 'search'); ?>
            <!-- Now we can render the completed list table -->
            <?php $testListTable->display() ?>
        </form>

    </div>