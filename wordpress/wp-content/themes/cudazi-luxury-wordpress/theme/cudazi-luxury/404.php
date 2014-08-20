<?php 
	get_header(); 
	$col = get_column_data($custom_settings["layout"]["columns"]); // get column data for layout - can be overriden
?>
<div class="clearfix">
	<div class='grid_12'><h2>Error 404 - Not Found</h2></div><!-- //col 1-->
</div><!-- //clearfix -->
<?php get_footer(); ?>