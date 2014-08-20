<?php 
	get_header(); 
	$col = get_column_data($custom_settings["layout"]["columns"]); // get column data for layout - can be overriden
?>
		<div class="clearfix">
			<?php echo "<div class='grid_".$col[1]."'>"; ?>
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						
						<div <?php post_class('clearfix') ?> id="post-<?php the_ID(); ?>">
							<?php get_thumbnail_above_post($post->ID, $col[1]); ?>
							<?php lux_title(); ?>
							<?php lux_meta(); ?>
							<?php the_content(custom_readmore()); ?>
							<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
							<?php lux_meta_bottom(); ?>
                            
                            <?php if(!$custom_settings["disable_bio"] && !get_post_meta($post->ID, "disable_bio", true)) { ?>
                                <div class="bio clearfix">
                                    <div class="pic"><?php echo get_avatar(get_the_author_meta('ID'),$size='80');  ?></div>
                                    <div class="details">
                                    	<?php $user_url = ""; $url = ""; ?>
                                        <?php $url = get_the_author_meta('user_url'); if($url) { $user_url = " | <strong><a href='".$url."'>Visit Website</a></strong>"; } ?>
                                        <h5>About <?php echo get_the_author_meta('user_login'); ?>:</h5>
                                        <?php if(get_the_author_meta('user_description')) { ?><p><?php echo get_the_author_meta('user_description'); ?></p><?php } ?>
                                        <p><strong><a href="<?php echo bloginfo('url'); ?>/?author=<?php echo get_the_author_meta('ID'); ?>">Find all posts by <?php echo get_the_author_meta('user_login'); ?></a></strong><?php echo $user_url; ?></p>
                                    </div>
                                </div><!-- end bio -->
                            <?php } ?>
                            
						</div>
					<?php comments_template(); ?>
					<?php endwhile; else: ?>
						<p>Sorry, no posts matched your criteria.</p>
				<?php endif; ?>
			</div><!-- //col 1-->
			
			<?php if($col[2]){ ?><div class="grid_<?php echo $col[2]; ?>"><?php } ?>
				<?php if($col[2]){ ?>
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('column_2') ) : endif; ?>
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('column_2_single') ) : endif; ?>
				<?php } ?>
			<?php if($col[2]){ ?></div><?php } ?>
			
			
			<?php if($col[3]){ ?><div class="grid_<?php echo $col[3]; ?>"><?php } ?>
				<?php if($col[3]){ ?>
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('column_3') ) : endif; ?>
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('column_3_single') ) : endif; ?>
				<?php } ?>
			<?php if($col[3]){ ?></div><?php } ?>
		</div><!-- //clearfix -->
		
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer') ) : endif; ?>
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer_single') ) : endif; ?>
<?php get_footer(); ?>