<?php 
	get_header(); 
	$col = get_column_data($custom_settings["layout"]["columns"]); // get column data for layout - can be overriden
?>
<!-- index.php -->
<div class="clearfix">
	<?php echo "<div class='grid_".$col[1]."'>"; ?>
	
		
		
		<?php /* If this is a category archive */ if (is_category()) { ?>
		<p><strong>Archive for the &#8216;<?php single_cat_title(); ?>&#8217; Category</strong></p>
		<?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
		<p><strong>Posts Tagged &#8216;<?php single_tag_title(); ?>&#8217;</strong></p>
		<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<p><strong>Archive for <?php the_time('F jS, Y'); ?></strong></p>
		<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<p><strong>Archive for <?php the_time('F, Y'); ?></strong></p>
		<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<p><strong>Archive for <?php the_time('Y'); ?></strong></p>
		<?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<p><strong>Author Archive</strong></p>
		<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<p><strong>Blog Archives</strong></p>
		<?php } ?>
		
		
		
		<?php if (have_posts()) : ?>
		
			<?php while (have_posts()) : the_post(); ?>
				<div <?php post_class('clearfix') ?> id="post-<?php the_ID(); ?>">
					<?php get_thumbnail_above_post($post->ID, $col[1]); ?>
					<?php lux_title(); ?>
					<?php lux_meta(); ?>
					<?php if(has_excerpt()) { the_excerpt(); }else{ the_content(custom_readmore()); } ?>
				</div>	
				<hr />
			<?php endwhile; ?>
			<?php lux_post_nav(); ?>
		<?php else : ?>
			<h2>Not Found</h2>
			<p>Sorry, but you are looking for something that isn't here.</p>
		<?php endif; ?>
	</div><!-- //col 1-->
			
	<?php if($col[2]){ ?><div class="grid_<?php echo $col[2]; ?>"><?php } ?>
		<?php if($col[2]){ ?>
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('column_2') ) : endif; ?>
			<?php /*if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('column_2_single') ) : endif;*/ ?>
		<?php } ?>
	<?php if($col[2]){ ?></div><?php } ?>
	
	
	<?php if($col[3]){ ?><div class="grid_<?php echo $col[3]; ?>"><?php } ?>
		<?php if($col[3]){ ?>
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('column_3') ) : endif; ?>
			<?php /*if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('column_3_single') ) : endif;*/ ?>
		<?php } ?>
	<?php if($col[3]){ ?></div><?php } ?>
</div><!-- //clearfix -->



<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer') ) : endif; ?>
<?php get_footer(); ?>