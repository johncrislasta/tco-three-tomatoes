<h1>
    <?php esc_html_e( 'Three Tomatoes Catering Report Tool.', TTC_TEXT_DOMAIN ); ?>
</h1>


<form action="" method="GET">
<input type="hidden" name="page" value="<?php echo $_GET['page']; ?>">
<?php

$report = new \TCo_Three_Tomatoes\Report_Table();
$report->prepare_items();
$report->display();

?>
</form>