<?php 
	get_header(); 
	$col = get_column_data($custom_settings["layout"]["columns"]); // get column data for layout - can be overriden
?>
<div class="clearfix">
	<?php echo "<div class='grid_".$col[1]."'>"; ?>
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<div class="post clearfix" id="post-<?php the_ID(); ?>">
				<?php get_thumbnail_above_post($post->ID, $col[1]); ?>
				<h2 class="posttitle"><?php the_title(); ?></h2>
				<?php if(has_excerpt()) { the_excerpt(); }else{ the_content(custom_readmore()); } ?>
				<?php wp_link_pages(array('before' => '<p><strong>Pages: ', 'after' => '</strong></p>', 'next_or_number' => 'number')); ?>
			</div>
			<?php endwhile; endif; ?>
		<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
	</div><!-- //col 1-->
	
	<?php if($col[2]){ ?><div class="grid_<?php echo $col[2]; ?>"><?php } ?>
		<?php if($col[2]){ ?>
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('column_2') ) : endif; ?>
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('column_2_page') ) : endif; ?>
		<?php } ?>
	<?php if($col[2]){ ?></div><?php } ?>
	
	
	<?php if($col[3]){ ?><div class="grid_<?php echo $col[3]; ?>"><?php } ?>
		<?php if($col[3]){ ?>
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('column_3') ) : endif; ?>
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('column_3_page') ) : endif; ?>
		<?php } ?>
	<?php if($col[3]){ ?></div><?php } ?>
</div><!-- //clearfix -->

<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer') ) : endif; ?>
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer_page') ) : endif; ?>
<?php get_footer(); ?>