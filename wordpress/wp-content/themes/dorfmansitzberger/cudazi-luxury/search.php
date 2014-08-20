<?php 
	get_header(); 
	$col = get_column_data($custom_settings["layout"]["columns"]); // get column data for layout - can be overriden
?>
<div class="clearfix">
	<?php echo "<div class='grid_".$col[1]."'>"; ?>
		<?php if (have_posts()) : ?>
			<h2>Search Results</h2>
			<ul class="searchresults clean">
			<?php while (have_posts()) : the_post(); ?>
				<li><div <?php post_class('clearfix') ?>>
					<h5 id="post-<?php the_ID(); ?>"><?php the_time('m.d.y'); ?> // <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h5>
				</div></li>
			<?php endwhile; ?>
			</ul>
			<?php lux_post_nav(); ?>
		<?php else : ?>
			<h2>No posts found</h2>
		<?php endif; ?>
	</div><!-- //col 1-->
	<?php if($col[2]){ ?><div class="grid_<?php echo $col[2]; ?>"><?php } ?>
		<?php if($col[2]){ ?>
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('column_2') ) : endif; ?>
			<?php /*if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('column_2_page') ) : endif;*/ ?>
		<?php } ?>
	<?php if($col[2]){ ?></div><?php } ?>
	
	
	<?php if($col[3]){ ?><div class="grid_<?php echo $col[3]; ?>"><?php } ?>
		<?php if($col[3]){ ?>
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('column_3') ) : endif; ?>
			<?php /*if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('column_3_page') ) : endif;*/ ?>
		<?php } ?>
	<?php if($col[3]){ ?></div><?php } ?>
</div><!-- //clearfix -->
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer') ) : endif; ?>
<?php get_footer(); ?>