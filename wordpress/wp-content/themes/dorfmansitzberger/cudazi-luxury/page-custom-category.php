<?php 
/*
	Template Name: Custom Category Display
*/
	get_header(); 
	$col = get_column_data($custom_settings["layout"]["columns"]); // get column data for layout - can be overriden
?>
<div class="clearfix">
	<?php echo "<div class='grid_".$col[1]."'>"; ?>
    	
        
        <!--// updated 6.20.10 -->
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <div class="post">
                    <?php get_thumbnail_above_post($post->ID, $col[1]); ?>
                    <?php $hide_thumbnails_by_custom_field = get_post_meta($post->ID, "hide_thumbnails", true); ?>
                    <?php the_content(custom_readmore()); ?>
                </div>
        	<?php endwhile; endif; ?>
        <!--// updated 6.20.10 -->
        
        
        <?php 
			$categories = get_post_meta($post->ID, "categories", true);
			if($categories) { $param = "cat=".$categories; }else{ $param = ""; } 
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; 
			query_posts($param . "&paged=" . $paged);
			global $more; $more = 0;
		?>
        
		<?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>
				<div <?php post_class('clearfix') ?> id="post-<?php the_ID(); ?>">
					<?php 
						// added if on 1.29.10
						if(!$hide_thumbnails_by_custom_field) { 
							get_thumbnail_above_post($post->ID, $col[1]); 
						} 
					?>
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
        <?php wp_reset_query(); ?>
        
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