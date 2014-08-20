<?php 
/*
	Template Name: Portfolio
*/
	get_header(); 
?>
<div class="clearfix">
        
        
		<?php $count = 0; ?>
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <div class="grid_12 clearfix"><?php get_thumbnail_above_post($post->ID, 12);/*hard coded at 12 columns wide since there is only 1 layout style*/ ?></div>
            <?php if(!get_post_meta($post->ID, "hide_content", true)){ ?>
                <div class="grid_12 clearfix"><?php the_content(); ?></div>
            <?php } ?>
        <?php endwhile; endif; ?>  
          
			<?php $count = 0; ?>
            <?php 
				$thumbnail_width = 3; // # of columns based on 960.js
				/* Get max items by custom field: posts_per_page */
				$max = get_post_meta($post->ID, "posts_per_page", true);
				if($max){ $max = "&posts_per_page=".$max; }
				$categories = get_post_meta($post->ID, "categories", true);
				if($categories) { $param = "cat=".$categories; }else{ $param = "cat=-0"; } 
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; 
				query_posts($param . "&paged=" . $paged . $max);
				global $more; $more = 0;
			?>
            
			<?php if (have_posts()) : ?>
				<?php while (have_posts()) : the_post(); ?>
				
				<?php if ($count % 4 == 0 && $count > 0) { ?>
					<div class="clearfix grid_12"><hr class="clearfix" /></div>
				<?php } ?>
				
				<?php $count++; ?>
				
				<div <?php post_class('grid_3') ?> id="post-<?php the_ID(); ?>">
					<?php 
						if(has_post_thumbnail())
						{
							echo "<div class='post-thumbnail portfolio-thumbnail'><a href='".get_permalink()."'>";
							the_post_thumbnail("portfolio-thumb");
							echo "</a></div>";
						}else{
							echo "<div><!-- No Post Thumbnail --></div>";
						}
					?>
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				</div>	
			<?php endwhile; ?>
			
			<div class="clearfix"></div>
			<?php lux_post_nav(); ?>
			
		<?php else : ?>
			<div class="grid_12">
				<h2>Not Found</h2>
				<p>Sorry, but you are looking for something that isn't here.</p>
			</div>
		<?php endif; ?>
        <?php wp_reset_query(); ?>
        
</div><!-- //clearfix -->

<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer') ) : endif; ?>
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer_portfolio') ) : endif; ?>
<?php get_footer(); ?>