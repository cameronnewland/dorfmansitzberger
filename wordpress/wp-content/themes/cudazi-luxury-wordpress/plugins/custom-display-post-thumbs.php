<?php
/*
* Plugin Name: Cudazi Footer Thumbnail Widget
* Plugin URI: http://www.curtziegler.com/
* Description: Display post thumbnails (latest work, etc...) in the footer widget.
* Version: 1.0
* Author: Curt Ziegler
* Author URI: http://www.curtziegler.com/
*/

add_action( 'widgets_init', 'post_thumbs_load_widgets' );

/* Register widget */
function post_thumbs_load_widgets() {
	register_widget( 'Post_Thumbs_Widget' );
}

/* Widget class: Settings, form, display, and update. */
class Post_Thumbs_Widget extends WP_Widget {

	function Post_Thumbs_Widget() {
		$widget_ops = array( 'classname' => 'custom_post_thumbs', 'description' => __('An custom widget that displays post thumbnails, designed to be used in Luxury theme footer.', 'custom_post_thumbs') );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'custom_post_thumbs-widget' );
		$this->WP_Widget( 'custom_post_thumbs-widget', __('Cudazi Footer Thumbnail Widget', 'custom_post_thumbs'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', $instance['title'] );
		$description = $instance['description'];
		$cats = $instance['cats'];

		echo $before_widget;
		
		$my_query = new WP_Query("cat=".$cats."&showposts=12");
		while ($my_query->have_posts()) : $my_query->the_post(); 
			if(has_post_thumbnail())
			{
				$img = get_the_post_thumbnail($my_query->ID,"single-post-thumbnail-60");
			}else{
				$img = "<!-- not set -->";
			}
			$the_post_thumbs .= "<div class='grid_1'><a href='". get_permalink() ."' title='".get_the_title()."' class='latest-work-thumbs'>".$img."</a></div>";
			
		endwhile;
		if(!empty($the_post_thumbs))
		{
			echo "<div class='grid_2'><h3>".$title."</h3></div><!--//grid_2-->";
            echo "<div class='grid_10'><p>".$description."&nbsp;</p></div><!--//grid_2-->";
            echo "<div class='clear'></div>";
			echo $the_post_thumbs;
			
		}
		//echo "(".$img;
			
		echo $after_widget;
	}

	/* Update settings. */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['cats'] = $new_instance['cats'];
		$instance['description'] = $new_instance['description'];
		return $instance;
	}

	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Latest Work', 'custom_post_thumbs'), 'description' => '');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e('description:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>" value="<?php echo $instance['description']; ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'cats' ); ?>"><?php _e('Categories to include/exclude:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'cats' ); ?>" name="<?php echo $this->get_field_name( 'cats' ); ?>" value="<?php echo $instance['cats']; ?>" style="width:100%;" />
            <small>Use comma separated list of category IDs to include/exclude. Adding a minus sign means exclude: -44 vs 44 (set blank or -0 to show all)</small>
		</p>

	<?php
	}
}
?>