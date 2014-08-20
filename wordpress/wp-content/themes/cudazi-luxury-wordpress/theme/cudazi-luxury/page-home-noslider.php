<?php
/*
	Template Name: Home - No Slider
*/
get_header(); ?>

			<!--// updated 6.20.10 -->
       		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<?php 
                    // Updated 2.24.10
                    // If home_slider_replacement widget is in use, display  it's contents (replaces the slider)
                    if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('home_slider_replacement') ) : endif; 
                    
                    // Check if this page has content, if so display it in a 12 column wide area
                    $content = get_the_content(); 
                    if($content)
                    {
                        echo "<div class='grid_12'>".$content . "<hr /></div>";
                    }
                ?>
            <?php endwhile; endif; ?>
            <!--// updated 6.20.10 -->
            
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