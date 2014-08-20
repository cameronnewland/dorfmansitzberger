<?php
/*
* Plugin Name: Cudazi Ad Manager Widget
* Plugin URI: http://www.curtziegler.com/
* Description: Display custom ads.
* Version: 1.0
* Author: Curt Ziegler
* Author URI: http://www.curtziegler.com/
*/

add_action( 'widgets_init', 'ad_manager_load_widgets' );

/* Register widget */
function ad_manager_load_widgets() {
	register_widget( 'Cudazi_Ad_Manager_Widget' );
}

/* Widget class: Settings, form, display, and update. */
class Cudazi_Ad_Manager_Widget extends WP_Widget {

	function Cudazi_Ad_Manager_Widget() {
		$widget_ops = array( 'classname' => 'cudazi_ad_manager_style', 'description' => __('An custom widget that displays ads, designed for 125x125 ads but can be modified.', 'cudazi_ad_manager_style') );
		$control_ops = array( 'width' => 600, 'height' => 500, 'id_base' => 'ad_manager-widget' );
		$this->WP_Widget( 'ad_manager-widget', __('Cudazi Ad Manager Widget', 'cudazi_ad_manager_style'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );
		echo $before_widget;
		for($i = 1; $i <= 6; $i++)
		{
			if(!empty($instance["ad_0".$i."_image"]) && !empty($instance["ad_0".$i."_url"]))
			{
				$output .= "<li><a href='" . $instance["ad_0".$i."_url"] . "'><img src='" . $instance["ad_0".$i."_image"] . "' alt='Visit' /></a></li>";
			}
		}
		if(!empty($output))
		{
			echo "<div class='widget clearfix'><ul class='ads clean'>";
			echo $output;
			echo "</ul></div>";
		}
		echo $after_widget;
	}

	/* Update settings. */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		for($i = 1; $i <= 6; $i++)
		{
			$instance["ad_0".$i."_image"] = $new_instance["ad_0".$i."_image"];
			$instance["ad_0".$i."_url"] = $new_instance["ad_0".$i."_url"];
		}
		return $instance;
	}

	function form( $instance ) {
		?>
		<div style="clear:both; border-bottom:1px solid #aaa; margin-bottom:10px;">
			<div style="float:left;"><strong>Image URL</strong><br />(Ex: http://www.site.com/images/ad01.jpg)</div>
			<div style="float:right;"><strong>Link</strong><br />(Ex: http://www.site.com/)</div>
			<br clear="all" />
            
		</div>
		<?php
			for($i = 1; $i <= 6; $i++)
			{
				?><p><input id="<?php echo $this->get_field_id( "ad_0".$i."_image" ); ?>" name="<?php echo $this->get_field_name( "ad_0".$i."_image" ); ?>" value="<?php echo $instance["ad_0".$i."_image"]; ?>" style="width:48%;" /><input id="<?php echo $this->get_field_id( "ad_0".$i."_url" ); ?>" name="<?php echo $this->get_field_name( "ad_0".$i."_url" ); ?>" value="<?php echo $instance["ad_0".$i."_url"]; ?>" style="width:48%;" /></p><?php
			}
		?>
		

	<?php
	}
}
?>