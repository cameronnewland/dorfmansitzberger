<?php
/*
* Plugin Name: Cudazi Home Posts Widget
* Plugin URI: http://www.curtziegler.com/
* Description: Display recent posts in a custom format.
* Version: 1.0
* Author: Curt Ziegler
* Author URI: http://www.curtziegler.com/
*/

add_action( 'widgets_init', 'recent_posts_load_widgets' );

/* Register widget */
function recent_posts_load_widgets() {
	register_widget( 'Recent_Posts_Widget' );
}

/* Widget class: Settings, form, display, and update. */
class Recent_Posts_Widget extends WP_Widget {

	function Recent_Posts_Widget() {
		$widget_ops = array( 'classname' => 'custom_posts', 'description' => __('An custom widget that displays recent posts.', 'custom_posts') );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'custom_posts-widget' );
		$this->WP_Widget( 'custom_posts-widget', __('Cudazi Home Posts Widget', 'custom_posts'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', $instance['title'] );
		$max = $instance['max'];
		$cats = $instance['cats'];

		echo $before_widget;
		
		$my_query = new WP_Query("cat=".$cats."&showposts=".$max);
		while ($my_query->have_posts()) : $my_query->the_post(); 
			$sidebar_post_count++;
			if ($sidebar_post_count & 1) { $sidebar_post_class = " class='highlight' "; }else{ $sidebar_post_class = ""; }
			$sidebar_posts .= "<li>";
			$sidebar_posts .= "<p><a href='". get_permalink() ."' ".$sidebar_post_class.">";
			$sidebar_posts .= "<span>" . get_the_time('m.d') . "</span> ";
			$sidebar_posts .= get_the_title();
			$sidebar_posts .= "</a></p>";
			$sidebar_posts .= "</li>";
		endwhile;
		if(!empty($sidebar_posts))
		{
			//echo "<div class='pod'><a href='". get_bloginfo('rss2_url') . "' class='rss right'></a>";
			echo $before_title.$title.$after_title;
			echo "<ul class='clean latest-posts'>" . $sidebar_posts . "</ul>";
			//echo "</div>";
		}
			
		echo $after_widget;
	}

	/* Update settings. */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['cats'] = $new_instance['cats'];
		if(is_numeric($new_instance['max']))
			$instance['max'] = $new_instance['max'];
		return $instance;
	}

	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Recent Posts', 'custom_posts'), 'max' => 5);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'max' ); ?>"><?php _e('Max:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'max' ); ?>" name="<?php echo $this->get_field_name( 'max' ); ?>" value="<?php echo $instance['max']; ?>" style="width:100%;" />
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