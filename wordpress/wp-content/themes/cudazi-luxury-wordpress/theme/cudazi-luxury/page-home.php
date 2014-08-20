<?php
/*
	Template Name: Home
*/
get_header(); ?>

       
            <?php
				$promo_images = get_post_meta($post->ID, "promo_image", false);
				$promo_urls = get_post_meta($post->ID, "promo_url", false);
				
				$disable_promo_link = $custom_settings["promo_disable_links"];
				
				// if promo images array (custom fields) is used,
				if($promo_images[0])
				{
					// use custom field values
					$i =0;
					$promo_images_output = "";
					$promo_navigation_output = "";
					$show = false;
					foreach($promo_images as $p_img)
					{
						if($i > 0){
							// add 'alternate' class to additional to avoid multiple showing at once
							$promo_images_output .= "<div class='promo alternate'>";
						}else{
							$promo_images_output .= "<div class='promo'>";
						}
						// start building the individual slides and navigation
						if($promo_urls[$i]) { $promo_images_output .= "<a href='".$promo_urls[$i]."'>"; };
						$promo_images_output .= "<img src='".$p_img."' alt='Featured Item' class='shadow-large' />";
						$promo_navigation_output .= "<li><a href='#'>".$i."</a></li>";
						if($promo_urls[$i]) { $promo_images_output .= "</a>"; };
						$promo_images_output .= "</div>";
						$i++;
					}
					if($i > 0)
					{
						$show = true;
					}
				}else{
					// use featured category post thumbnails
					$featured_categories = $custom_settings["promo_featured_category_id"];
					if(!$featured_categories) { $featured_categories = 99999999; }
					$my_query = new WP_Query("showposts=-1&cat=".$featured_categories);
					new WP_Query();  
					while ($my_query->have_posts()) : $my_query->the_post(); 
						if(has_post_thumbnail())
						{
							$featured_count++;
							if($i > 0){
								// add 'alternate' class to additional to avoid multiple showing at once
								$promo_images_output .= "<div class='promo alternate'>";
							}else{
								$promo_images_output .= "<div class='promo'>";
							}
							// start building the individual slides and navigation
							
							// added option to disable promo link from Theme Settings page on 2.24.10
							if(!$disable_promo_link){ $promo_images_output .= "<a href='".get_permalink()."'>"; }
							$promo_images_output .= get_the_post_thumbnail($my_query->ID,"home-slider-image");
							$promo_navigation_output .= "<li><a href='#'>".$featured_count."</a></li>";
							if(!$disable_promo_link){ $promo_images_output .= "</a>"; }
							$promo_images_output .= "</div>";
							$featured_count++;
						}
					endwhile;
					
					if($featured_count > 0)
					{
						$show = true;
					}
				}
				

				
				
			?>
            
            <?php if($show == true) { ?>
            <div class="clearfix">
                <div class="grid_12 promo-container">
                    <div id="promos">
                    	<?php echo $promo_images_output; ?>
                    </div>
                    <?php if(!$custom_settings["promo_nav_disabled"]) { ?>
                    <div id="promo-nav" class="clearfix">
                    	<ul class="clean right">
                        	<?php echo $promo_navigation_output; ?>
                        </ul>
                    </div>
                    <?php } ?>
                </div><!--//grid10-->
            </div><!--//clearfix-->
			<?php } ?>
            
            
            
            
            
            
            <?php 
			
				/*
					HOME PAGE WIDGET LAYOUT OPTIONS
					(Controlled through the custom settings page)
				*/
				$home_widgets_columns = explode("|",$custom_settings["layout"]["home_widget_layout"]);
				
				$home_widget_col_a = $home_widgets_columns[0];
				$home_widget_col_b = $home_widgets_columns[1];
				$home_widget_col_c = $home_widgets_columns[2];
				
				// columns must add up to 12!!
				$col_total = $home_widget_col_a + $home_widget_col_b + $home_widget_col_c;
				if($col_total != 12)
				{
					// if not, force into 3 column mode
					$home_widget_col_a = 4;
					$home_widget_col_b = 4;
					$home_widget_col_c = 4;
				}
			?>
            <div class="clearfix widget-area">
                <div class="<?php echo "grid_".$home_widget_col_a; ?> widget">
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('home_col_a') ) : endif; ?>
				</div><!--//grid4-->
                <?php if($home_widget_col_b > 0){ ?>
                    <div class="<?php echo "grid_".$home_widget_col_b; ?> widget">
                        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('home_col_b') ) : endif; ?>
                    </div><!--//grid4-->
                <?php } ?>
                
                <?php if($home_widget_col_c > 0){ ?>
                    <div class="<?php echo "grid_".$home_widget_col_c; ?> widget">
                        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('home_col_c') ) : endif; ?>
                    </div><!--//grid4-->
                <?php } ?>
            </div><!--//clearfix-->
      
	  
	           
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer') ) : endif; ?>
            <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer_home') ) : endif; ?>

			
			
<?php get_footer(); ?>