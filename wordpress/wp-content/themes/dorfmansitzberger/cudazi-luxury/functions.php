<?php
	global $custom_settings;
	$custom_settings = get_custom_settings(); 
	
	automatic_feed_links();
	
	// Adding Custom Admin Settings Page
	add_action('admin_menu', 'theme_settings'); 
	add_action('admin_head', 'theme_styles');
	function theme_settings() { 
		add_menu_page('Theme Settings', 'Theme Settings', 'edit_themes', __FILE__, 'theme_settings_form');
	}
	
	
	
	// Must be on wordpress 2.9 or above
	if ( function_exists( 'add_theme_support' ) ) {
		
		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary' => __( 'Primary Navigation', 'cudazi' )
		) );
		
		add_theme_support( 'post-thumbnails' );
		
		/*
			THUMBNAIL SIZES
			please note - you must delete and re-add the thumbnails on each post/page if you change 
			the sizes below or you will not see the change take effect.
		*/
		set_post_thumbnail_size( 80, 80 ); // 50 pixels wide by 50 pixels tall, box resize mode
		add_image_size( 'single-post-thumbnail-620', 620, 9999 );
		add_image_size( 'single-post-thumbnail-780', 780, 9999 );
		add_image_size( 'single-post-thumbnail-940', 940, 9999 );
		add_image_size( 'single-post-thumbnail-60', 60, 60, true); // footer thumbnails
		add_image_size( 'home-slider-image', 940, 400, true); // home slider size
		
		add_image_size( 'portfolio-thumb', 220, 220, true); // 4 column portfolio size
		
	}
	
	
	

	function add_stylesheets()
	{	
		global $custom_settings;
		
		echo "<link rel='stylesheet' href='" . get_bloginfo('template_directory') . "/css/960.css' type='text/css' media='screen' />";
		echo "<link rel='stylesheet' href='" . get_bloginfo('template_directory') . "/css/screen.css' type='text/css' media='screen' />";
		echo "<link rel='stylesheet' href='" . get_bloginfo('template_directory') . "/css/print.css' type='text/css' media='print' />";
		echo "<link rel='stylesheet' href='" . get_bloginfo('template_directory') . "/css/superfish.css' type='text/css' media='screen' />";
		echo "<link rel='stylesheet' href='" . get_bloginfo('template_directory') . "/style.css' type='text/css' media='screen' />";
		
		// Dynamic CSS - allows additional styles to be included from the WP admin area
		echo "<style type='text/css'>";
			include_once(TEMPLATEPATH . "/css/dynamic-css.php");
		echo "</style>";
	}
	
	
	function add_javascript()
	{	
		// no longer used, moved to header.php
	}
	
	
	// Function to help with layout of the 960.gs used in each page template
	function get_column_data($columns)
	{
		$d = array();
		if($columns == '3'){
			$d[1] = 8;
			$d[2] = 2;
			$d[3] = 2;
		}else if($columns == '2'){
			$d[1] = 10;
			$d[2] = 2;
			$d[3] = "";
		}else if($columns == '2w'){
			$d[1] = 8;
			$d[2] = 4;
			$d[3] = "";
		}else{
			$d[1] = 12;
			$d[2] = "";
			$d[3] = "";
		}
		return $d;
	}


	// convert the number of columns in the 960gs system over to an actual
	// pixel size for use in the thumbnails and so on
	function get_col_width_pixels($col)
	{
		if($col == 8)
			return 620;
		if($col == 10)
			return 780;
		if($col == 12)
			return 940;
	}
	
	
	// used to grab the thumbnail used above each post
	function get_thumbnail_above_post($postID, $col_span)
	{
		global $custom_settings;
		if(is_single() && $custom_settings["layout"]["hide_thumbnail_on_single"] == 'y'){ 
			// do nothing, it is set to hide on single post
		}else{
			$col_width_px = get_col_width_pixels($col_span);
			if(has_post_thumbnail())
			{
				echo "<div class='post-thumbnail'><a href='".get_permalink()."'>";
				the_post_thumbnail("single-post-thumbnail-".$col_width_px);
				echo "</a></div>";
			}
		}
	}


	// site-wide control of the "Read more" tag.
	// You can override per post using <!--more YOUR TEXT HERE-->
	function custom_readmore()
	{
		global $custom_settings;
		if(!$custom_settings["readmore"])
		{
			return "Read More..."; //default
		}else{
			return $custom_settings["readmore"]; // customized in wp-admin
		}
	}


	
	
	
	/*
		Custom Meta box under posts/pages
		- control columns
		- control thumbnail hiding on posts
	*/
	
	$key = "custom_meta_values";
	$meta_boxes = array(
		"columns" => array("name" => "columns","title" => "Columns","description" => "Number of Columns"),
		"hide_thumbnail_on_single" => array("name" => "hide_thumbnail_on_single","title" => "Hide Thumbnail on Full","description" => "Do you want to hide the post thumbnail above post title on the full page?")
	);
	
	function create_custom_meta_box() {
		global $key;
		if( function_exists( 'add_meta_box' ) ) {
			add_meta_box( 'new-meta-boxes', 'Custom Post Options', 'display_meta_box', 'post', 'normal', 'high' );
			add_meta_box( 'new-meta-boxes-page', 'Custom Page Options', 'display_meta_box_page', 'page', 'normal', 'high' );
		}
	}
	
	
	// DISPLAY META BOX FOR POSTS
	function display_meta_box() {
		global $post, $meta_boxes, $key;
		?>
		<div class="form-wrap">
			<?php wp_nonce_field( plugin_basename( __FILE__ ), $key . '_wpnonce', false, true ); ?>
			<p><em>Override the theme defaults below, remember to save the post/page.</em></p><?php
			foreach($meta_boxes as $meta_box) {
				$data = get_post_meta($post->ID, $key, true);
				$columns = get_post_meta($post->ID, "columns", true);
			?>
				<?php if( $meta_box['name'] == 'columns'){ ?>
					<div class="form-field">
						<table width="90%">
							<tr>
								<td width="200" align="right"><strong><?php echo $meta_box[ 'title' ]; ?></strong></td>
								<td>
									<select name="<?php echo $meta_box[ 'name' ]; ?>">
										<option value="">Use Theme Default</option>
										<option value="1"<?php if($data["columns"] == '1'){ echo " selected='selected'"; } ?>>1 Column</option>
										<option value="2"<?php if($data["columns"] == '2'){ echo " selected='selected'"; } ?>>2 Columns</option>
										<option value="2w"<?php if($data["columns"] == '2w'){ echo " selected='selected'"; } ?>>2 Columns (large sidebar)</option>
										<option value="3"<?php if($data["columns"] == '3'){ echo " selected='selected'"; } ?>>3 Columns</option>
									</select>
								</td>
							</tr>
						</table>
					</div>
				<?php }else if( $meta_box['name'] == 'hide_thumbnail_on_single'){ ?>
					<div class="form-field">
					
						<table width="90%">
							<tr>
								<td width="200" align="right"><strong><?php echo $meta_box[ 'title' ]; ?></strong></td>
								<td>
									<select name="<?php echo $meta_box[ 'name' ]; ?>">
										<option value="">Use Theme Default</option>
										<option value="y"<?php if($data["hide_thumbnail_on_single"] == 'y'){ echo " selected='selected'"; } ?>>Yes</option>
										<option value="n"<?php if($data["hide_thumbnail_on_single"] == 'n'){ echo " selected='selected'"; } ?>>No</option>
									</select>
								</td>
							</tr>
							<tr><td>&nbsp;</td><td><small><?php echo $meta_box[ 'description' ]; ?></small></td></tr>
						</table>
					</div>
				<?php }else{ ?>
					<div class="form-field">
						<label for="<?php echo $meta_box[ 'name' ]; ?>"><?php echo $meta_box[ 'title' ]; ?></label>
						<input type="text" name="<?php echo $meta_box[ 'name' ]; ?>" value="<?php echo htmlspecialchars( $data[ $meta_box[ 'name' ] ] ); ?>" />
						<p><?php echo $meta_box[ 'description' ]; ?></p>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
		<?php
	}

	
	// DISPLAY META BOX FOR PAGES
	function display_meta_box_page() {
		global $post, $meta_boxes, $key;
		?>
		<div class="form-wrap">
			<?php wp_nonce_field( plugin_basename( __FILE__ ), $key . '_wpnonce', false, true ); ?>
			<p><em>Override the theme defaults below, remember to save.</em></p><?php
			foreach($meta_boxes as $meta_box) {
				$data = get_post_meta($post->ID, $key, true);
				$columns = get_post_meta($post->ID, "columns", true);
			?>
				<?php if( $meta_box['name'] == 'columns'){ ?>
					<div class="form-field">
						<table width="90%">
							<tr>
								<td width="200" align="right"><strong><?php echo $meta_box[ 'title' ]; ?></strong></td>
								<td>
									<select name="<?php echo $meta_box[ 'name' ]; ?>">
										<option value="">Use Theme Default</option>
										<option value="1"<?php if($data["columns"] == '1'){ echo " selected='selected'"; } ?>>1 Column</option>
										<option value="2"<?php if($data["columns"] == '2'){ echo " selected='selected'"; } ?>>2 Columns</option>
										<option value="2w"<?php if($data["columns"] == '2w'){ echo " selected='selected'"; } ?>>2 Columns (large sidebar)</option>
										<option value="3"<?php if($data["columns"] == '3'){ echo " selected='selected'"; } ?>>3 Columns</option>
									</select>
								</td>
							</tr>
						</table>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
		<?php
		}

	// SAVE POST/PAGE META INFORMATION
	function save_custom_meta_box( $post_id ) {
		global $post, $meta_boxes, $key;
		
		foreach( $meta_boxes as $meta_box ) {
			$data[ $meta_box[ 'name' ] ] = $_POST[ $meta_box[ 'name' ] ];
		}
		
		if ( !wp_verify_nonce( $_POST[ $key . '_wpnonce' ], plugin_basename(__FILE__) ) )
			return $post_id;
		
		if ( !current_user_can( 'edit_post', $post_id ))
			return $post_id;
		
		update_post_meta( $post_id, $key, $data );
	}
	
	add_action( 'admin_menu', 'create_custom_meta_box' );
	add_action( 'save_post', 'save_custom_meta_box' );









	//
	// Gets custom settings array.
	// If not set in DB, inserts defaults below for the first run
	// and then pulls in settings from DB from then on.
	//
	function get_custom_settings()
	{
		$custom_settings_array = get_option("custom_settings");
		
		if(!empty($custom_settings_array))
		{
			// Custom Settings set, pull from DB
			$s = $custom_settings_array;
		}else{
			// set defaults into DB
			update_option("custom_settings", $s);
		}
		return $s;
	}





// display the settings form on the custom settings admin page
function theme_settings_form(){ 
	global $custom_settings;
	
    if(isset($_POST['submit-updates']) && $_POST['submit-updates'] == "yes"){

		$custom_settings["layout"]["columns"] = $_POST["options"]["layout"]["columns"]; // 1, 2, 3 (excluding left menu column)
		//$custom_settings["layout"]["hide_thumbnail_on_single"] = $_POST["options"]["layout"]["hide_thumbnail_on_single"]; // default - show thumbnail of attached image above post
		
		
		$custom_settings["menu_exclude"] = $_POST["options"]["menu_exclude"];
		$custom_settings["search_disabled"] = $_POST["options"]["search_disabled"];
		$custom_settings["dropdown_disabled"] = $_POST["options"]["dropdown_disabled"];
		$custom_settings["additional_js"] = stripslashes(htmlentities($_POST["options"]["additional_js"], ENT_QUOTES));
		$custom_settings["promo_featured_category_id"] = $_POST["options"]["promo_featured_category_id"];
		$custom_settings["promo_nav_disabled"] = $_POST["options"]["promo_nav_disabled"];
		$custom_settings["readmore"] = $_POST["options"]["readmore"];
		$custom_settings["footer_text"] = stripslashes(htmlentities($_POST["options"]["footer_text"], ENT_QUOTES));
		$custom_settings["layout"]["hide_thumbnail_on_single"] = $_POST["options"]["layout"]["hide_thumbnail_on_single"];
		$custom_settings["layout"]["home_widget_layout"] = $_POST["options"]["layout"]["home_widget_layout"];
		$custom_settings["logo"] = $_POST["options"]["logo"];
		$custom_settings["font"] = $_POST["options"]["font"];
		$custom_settings["css"]["extra"] = $_POST["options"]["css"]["extra"];
		$custom_settings["logo_text_based"] = $_POST["options"]["logo_text_based"];
		
		$custom_settings["promo_disable_links"] = $_POST["options"]["promo_disable_links"];
		$custom_settings["disable_bio"] = $_POST["options"]["disable_bio"];
		
		for($i=0; $i<=10; $i++){
              $custom_settings["social"][$i]["url"] = $_POST["options"]["social"][$i]["url"];
			  $custom_settings["social"][$i]["icon"] = $_POST["options"]["social"][$i]["icon"];
		}
		
		// *************************************************
		// pass array into custom settings row in DB
		update_option("custom_settings", $custom_settings);
		// *************************************************
		
        echo "<div id=\"message\" class=\"updated fade\"><p><strong>Saved Settings!</strong></p></div>";
    }
?>
<div class="wrap">
	<form method="post" name="brightness" target="_self" class="adminoptions">
		<h1>Theme Settings</h1>
		<h3 style="color:#990000;">Please read through all settings to find helpful tips and best practice information.</h3>
		<input type="submit" name="Submit" value="Save Settings" />
		
        
        <div class="field inset">
			<label>Number of Columns</label>
            <div>You can override this setting in the custom write panel on a per page/post basis.</div>
			<small>(does not apply to home page template)</small>
			<select name="options[layout][columns]">
				<option value="1" <?php if($custom_settings["layout"]["columns"] == '1'){ echo " selected='selected'"; } ?>>1 Column</option>
				<option value="2" <?php if($custom_settings["layout"]["columns"] == '2'){ echo " selected='selected'"; } ?>>2 Columns</option>
				<option value="2w" <?php if($custom_settings["layout"]["columns"] == '2w'){ echo " selected='selected'"; } ?>>2 Columns (large sidebar)</option>
				<option value="3" <?php if($custom_settings["layout"]["columns"] == '3'){ echo " selected='selected'"; } ?>>3 Columns</option>
			</select>
		</div>
        
        <div class="field inset">
			<label>Home page promo category</label>
			<small>Enter the category id/ids to exclude and separate each with a comma.<br />Example: 1&nbsp;&nbsp;Example: 1,2&nbsp;&nbsp; Example:12,-22</small>
            <small>Note: To override this and specify exact images and urls, just use custom fields on the page using the home page template: promo_image and promo_url</small>
			<div><input name="options[promo_featured_category_id]" value="<?php echo $custom_settings["promo_featured_category_id"]; ?>" class="textbox-small" /></div>
            <small>ALL IMAGES USED BY THE SLIDER MUST BE THE SAME HEIGHT</small>
			<small><a href="http://www.google.com/search?q=how+to+find+category+ID+wordpress" target="_blank">How to find category IDs</a></small>
		</div>
        <div class="field inset">
			<label>Disable Home Promo Navigation</label>
			<label class="normal"><input name="options[promo_nav_disabled]" value="y" type="checkbox" <?php if($custom_settings["promo_nav_disabled"] == 'y'){ echo " checked='checked'"; } ?> /> Hide navigation under home page promos</label>
		</div>
        
        <div class="field inset">
			<label>Home Page: Disable Links on Promo Images</label>
			<label class="normal"><input name="options[promo_disable_links]" value="y" type="checkbox" <?php if($custom_settings["promo_disable_links"] == 'y'){ echo " checked='checked'"; } ?> /> Disable Links on Promo Images</label>
		</div>
        
        
        <div class="field inset">
			<label>Home Page Widget Layout</label>
			<small>Page must be using the "Home" page template (page-home.php)</small>
            <small>Columns use these widget areas: Home Column A, Home Column B, Home Column C</small>
            <small>The numbers in parentheses represent the column width based on the 960.gs framework.</small>
			<label class="normal"><input type="radio" value="4|4|4" name="options[layout][home_widget_layout]" <?php if($custom_settings["layout"]["home_widget_layout"]=='4|4|4'){echo"checked='checked'";}?> />Three Columns: (4/4/4) - Default</label>
            <label class="normal"><input type="radio" value="12|0|0" name="options[layout][home_widget_layout]" <?php if($custom_settings["layout"]["home_widget_layout"]=='12|0|0'){echo"checked='checked'";}?>/>One Full Column: (12)</label>
            <label class="normal"><input type="radio" value="4|8|0" name="options[layout][home_widget_layout]" <?php if($custom_settings["layout"]["home_widget_layout"]=='4|8|0'){echo"checked='checked'";}?>/>Two Columns: (4/8)</label>
            <label class="normal"><input type="radio" value="8|4|0" name="options[layout][home_widget_layout]" <?php if($custom_settings["layout"]["home_widget_layout"]=='8|4|0'){echo"checked='checked'";}?>/>Two Columns: (8/4)</label>
            <label class="normal"><input type="radio" value="6|6|0" name="options[layout][home_widget_layout]" <?php if($custom_settings["layout"]["home_widget_layout"]=='6|6|0'){echo"checked='checked'";}?>/>Two Columns: (6/6)</label>
            <label class="normal"><input type="radio" value="10|2|0" name="options[layout][home_widget_layout]" <?php if($custom_settings["layout"]["home_widget_layout"]=='10|2|0'){echo"checked='checked'";}?>/>Two Columns: (10/2)</label>
            <label class="normal"><input type="radio" value="2|10|0" name="options[layout][home_widget_layout]" <?php if($custom_settings["layout"]["home_widget_layout"]=='2|10|0'){echo"checked='checked'";}?>/>Two Columns: (2/10)</label>
            <label class="normal"><input type="radio" value="8|2|2" name="options[layout][home_widget_layout]" <?php if($custom_settings["layout"]["home_widget_layout"]=='8|2|2'){echo"checked='checked'";}?>/>Three Columns: (8/2/2)</label>
            <label class="normal"><input type="radio" value="2|2|8" name="options[layout][home_widget_layout]" <?php if($custom_settings["layout"]["home_widget_layout"]=='2|2|8'){echo"checked='checked'";}?>/>Three Columns: (2/2/8)</label>
            <label class="normal"><input type="radio" value="2|8|2" name="options[layout][home_widget_layout]" <?php if($custom_settings["layout"]["home_widget_layout"]=='2|8|2'){echo"checked='checked'";}?>/>Three Columns: (2/8/2)</label>
		</div>
        
		
		<div class="field inset">
			<label>Logo URL</label>
			<small>Enter the full URL to your logo below <strong>or simply overwrite the logo.gif in the /images/ folder of this theme</strong>.</small>
			<small>The default logo size is: 170x74</small>
			<div><input name="options[logo]" value="<?php echo $custom_settings["logo"]; ?>" class="textbox-large" /></div>
		</div>
		
		<div class="field inset">
			<label>Text-Based Logo - (This will override the Logo URL above!)</label>
			<small>If you want to use a text-based logo instead, enter a title below:</small>
			<small>Text will be surrounded by H1 tags, suggested CSS to position logo vertically: .logo H1 A { display:block; padding-top:40px; }</small>
			<small>Add to "Additional CSS" setting below.</small>
			<div><input name="options[logo_text_based]" value="<?php echo $custom_settings["logo_text_based"]; ?>" class="textbox-large" /></div>
		</div>
		
        <div class="field inset">
			<label>Disable Header Search</label>
			<label class="normal"><input name="options[search_disabled]" value="y" type="checkbox" <?php if($custom_settings["search_disabled"] == 'y'){ echo " checked='checked'"; } ?> /> Hide search in header</label>
		</div>
        
		<div class="field inset">
			<label>Disable Drop Down Menu</label>
			<label class="normal"><input name="options[dropdown_disabled]" value="y" type="checkbox" <?php if($custom_settings["dropdown_disabled"] == 'y'){ echo " checked='checked'"; } ?> /> Hide drop down menus</label>
		</div>
        
        <div class="field inset">
			<label>Post Thumbnails</label>
			<div>You can override this setting in the custom write panel on a per page/post basis.</div>
			<label><input name="options[layout][hide_thumbnail_on_single]" value="y" type="checkbox" <?php if($custom_settings["layout"]["hide_thumbnail_on_single"] == 'y'){ echo " checked='checked'"; } ?> /> Hide post thumbnail on Single/Full post page</label>
		</div>
		<div class="field inset">
			<label>Exclude From Main Menu</label>
			<small>Enter the page id/ids to exclude and separate each with a comma.<br />Example: 1&nbsp;&nbsp;Example: 1,2&nbsp;&nbsp; Example:12,22</small>
			<div><input name="options[menu_exclude]" value="<?php echo $custom_settings["menu_exclude"]; ?>" class="textbox-small" /></div>
			<small><a href="http://www.google.com/search?q=how+to+find+page+ID+wordpress" target="_blank">How to find Page IDs</a></small>
            <small>For use with basic menu, not WordPress 3.0 menu system.</small>
		</div>
		<div class="field inset">
			<label>Default "Read More..." text</label>
			<small>Don't forget, you can always customize the &lt;--more--&gt; tag per post - <a href="http://codex.wordpress.org/Write_Post_SubPanel#Quicktags" target="_blank">get details</a>.</small>
			<div><input name="options[readmore]" value="<?php echo $custom_settings["readmore"]; ?>" class="textbox-small" /></div>
		</div>
        
        <div class="field inset">
        	<label>Disable Author Bios</label>
			<small>Disable author bio on single post by checking this option.<br />
			Or, hide the bio on a per post basis: set a custom field called <strong>disable_bio</strong> with a value of anything except blank.</small>
			<label><input type="checkbox" value="1" name="options[disable_bio]" <?php if($custom_settings["disable_bio"]){ echo "checked='checked'"; } ?> /> Hide Author Bio</label>
		</div>
        
        <div class="field inset">
        	<label>Social Media Icons (footer)</label>
            <small>Add your own in this theme's folder: images/social_icons/ and they will appear in the dropdown.</small>
			<?php for($i=0; $i<=10; $i++){ ?>
                <div>
                	<select name="options[social][<?php echo $i; ?>][icon]"><?php echo list_social_icons($custom_settings["social"][$i]["icon"]); ?></select>
                    <input name="options[<?php echo "social][".$i."][url"; ?>]" value="<?php echo $custom_settings["social"][$i]["url"]; ?>" class="textbox-medium" />
                </div>
            <?php } ?>
            <small>Your RSS URL is: <?php bloginfo('rss2_url'); ?></small>
        </div>
		
		
		<div class="field inset">
        	<label>Cufon Fonts</label>
            <small>Add your own in this theme's folder: js/fonts/ and they will appear in the dropdown.</small>
			<small>Generate your own JavaScript font file at <a href="http://cufon.shoqolate.com/generate/" target="_blank">http://cufon.shoqolate.com/generate/</a></small>
			<div>
				<select name="options[font]">
					<?php echo list_fonts($custom_settings["font"]); ?>
				</select>
			</div>
        </div>
        
		
		
		
        
        <div class="field inset">
			<label>Footer Text</label>
			<small>Enter your own content here to replace my default text.</small>
			<small>Please do not enter odd symbols such as &copy; use the HTML entity:<strong> &amp;copy;</strong> - <a href="http://www.google.com/search?q=html+entities" target="_blank">More html entities...</a></small>
			<small>Enter <strong>CURRENTYEAR</strong> to insert the current year and put an end to forgetting to update your copyright!</small>
			<label><textarea name="options[footer_text]" class="textarea-large"><?php echo $custom_settings["footer_text"]; ?></textarea></label>
			<small>Basic HTML allowed, this text will be output between &lt;p&gt; tags.</small>
		</div>
        
        <div class="field inset">
			<label>Additional Javascript / Google Analytics / Etc...</label>
			<small>Applied in the footer of the website.<br />Surround JavaScript with proper &lt;script type='text/javascript'&gt;&lt;/script&gt; tags.</small>
			<label><textarea name="options[additional_js]" class="textarea-large"><?php echo $custom_settings["additional_js"]; ?></textarea></label>
		</div>
		
		
		
		<div class="field inset">
			<label>Additional CSS</label>
			<small>Use this to override all theme styles without having to edit the CSS file directly. <a href="http://www.google.com/search?q=css+tutorial" target="_blank">CSS Tutorials...</a></small>
			<label><textarea name="options[css][extra]" class="textarea-huge"><?php echo $custom_settings["css"]["extra"]; ?></textarea></label>
			<small>Enter the CSS directly without the opening/closing &lt;style&gt; tags: <strong>body { color:#990000; }</strong></small>
		</div>
		
		<hr />
		<input type="submit" name="Submit" value="Save Settings" />
		<input name="submit-updates" type="hidden" value="yes" />
		<br /><br /><br /><br />
	</form>
</div>
<?php 
}









// Add Dashboard Head CSS to custom settings page 
function theme_styles() { 
	echo "<style type=\"text/css\"> 
	.adminoptions label { display: block; font-weight:bold; } 
	.adminoptions label.normal { font-weight:normal; }
	.adminoptions .field { padding:5px 0; } 
	.adminoptions small { display:block; } 
	.adminoptions .textbox-small { width:100px; } 
	.adminoptions .textbox-med-small { width:175px; } 
	.adminoptions .textbox-medium { width:250px; } 
	.adminoptions .textbox-large { width:350px; } 
	.adminoptions .textarea-small { width:350px; height:50px; } 
	.adminoptions .textarea-medium { width:450px; height:50px; } 
	.adminoptions .textarea-large { width:500px; height:100px; } 
	.adminoptions .textarea-huge { width:500px; height:300px; } 
	.adminoptions .inset { padding-left:20px; margin:15px 0;  border-left:2px dotted #ccc; } 
	
	</style>";
}


if ( function_exists('register_sidebar') ) {
    
	register_sidebar(array(
        'id' => 'column_2',
        'name' => 'Column Two - All',
        'description' => '',
        'before_widget' => '<div id="%1$s" class="widget sidebarwidget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));
	register_sidebar(array(
        'id' => 'column_2_single',
        'name' => 'Column Two - Single Post',
        'description' => '',
        'before_widget' => '<div id="%1$s" class="widget sidebarwidget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));
	register_sidebar(array(
        'id' => 'column_2_page',
        'name' => 'Column Two - Page',
        'description' => '',
        'before_widget' => '<div id="%1$s" class="widget sidebarwidget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));
	
	register_sidebar(array(
        'id' => 'column_3',
        'name' => 'Column Three - All',
        'description' => '',
        'before_widget' => '<div id="%1$s" class="widget sidebarwidget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));
	register_sidebar(array(
        'id' => 'column_3_single',
        'name' => 'Column Three - Single Post',
        'description' => '',
        'before_widget' => '<div id="%1$s" class="widget sidebarwidget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));
	register_sidebar(array(
        'id' => 'column_3_page',
        'name' => 'Column Three - Page',
        'description' => '',
        'before_widget' => '<div id="%1$s" class="widget sidebarwidget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));
	
	
	register_sidebar(array(
        'id' => 'home_col_a',
        'name' => 'Home Column A',
        'description' => '',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2>',
        'after_title' => '</h2>',
    ));
	register_sidebar(array(
        'id' => 'home_col_b',
        'name' => 'Home Column B',
        'description' => '',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2>',
        'after_title' => '</h2>',
    ));
	register_sidebar(array(
        'id' => 'home_col_c',
        'name' => 'Home Column C',
        'description' => '',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2>',
        'after_title' => '</h2>',
    ));
	
 
    register_sidebar(array(
        'id' => 'footer',
        'name' => 'Footer - All',
        'description' => '',
        'before_widget' => '<div class="grid_12 clearfix"><hr /></div><!--//grid_12--><div id="%1$s" class="widget footerwidget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));
	
	register_sidebar(array(
        'id' => 'footer_single',
        'name' => 'Footer - Single Post',
        'description' => '',
        'before_widget' => '<div class="grid_12 clearfix"><hr /></div><!--//grid_12--><div id="%1$s" class="widget footerwidget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));
	
	register_sidebar(array(
        'id' => 'footer_page',
        'name' => 'Footer - Page',
        'description' => '',
        'before_widget' => '<div class="grid_12 clearfix"><hr /></div><!--//grid_12--><div id="%1$s" class="widget footerwidget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));
	
	register_sidebar(array(
        'id' => 'footer_home',
        'name' => 'Footer - Home',
        'description' => '',
        'before_widget' => '<div class="grid_12 clearfix"><hr /></div><!--//grid_12--><div id="%1$s" class="widget footerwidget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));
	
	register_sidebar(array(
        'id' => 'home_slider_replacement',
        'name' => 'Home Slider Replacement',
        'description' => 'Use this with the custom page Home - No Slider to use a widget vs. the built in slider.',
        'before_widget' => '<div class="grid_12 clearfix"><div id="%1$s" class="widget promo-widget clearfix %2$s">',
        'after_widget' => '</div></div><!--//grid_12-->',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));
	
	register_sidebar(array(
        'id' => 'footer_portfolio',
        'name' => 'Footer - Portfolio',
        'description' => 'Use this with the portfolio page template.',
        'before_widget' => '<div class="grid_12 clearfix"><hr /></div><!--//grid_12--><div id="%1$s" class="widget footerwidget grid_12 %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));
	
}





function list_social_icons($id)
{
	$list_of_icons = "";
	$list_of_icons .= "<option value=''>None</option>";
	if ($handle = opendir(TEMPLATEPATH . "/images/social_icons")) {
		while (false !== ($file = readdir($handle))) {
			if (preg_match("/^.*\.(jpg|jpeg|png|gif)$/i", $file)) {
				
				if($file == $id)
				{
					$list_of_icons .= "<option selected='selected'>";
				}else{
					$list_of_icons .= "<option>";
					//$list_of_icons .= "<!-- test: File: ".$file." id: " .$id. "-->";
				}
				$list_of_icons .= "$file</option>";
			}
			
		}
		closedir($handle);
		
		return $list_of_icons;
	}
}


function list_fonts($id)
{
	$list_of_icons = "";
	$list_of_icons .= "<option value=''>Use Default</option>";
	if ($handle = opendir(TEMPLATEPATH . "/js/fonts")) {
		while (false !== ($file = readdir($handle))) {
			if (preg_match("/^.*\.(js)$/i", $file)) {
				
				if($file == $id)
				{
					$list_of_icons .= "<option selected='selected'>";
				}else{
					$list_of_icons .= "<option>";
				}
				$list_of_icons .= "$file</option>";
			}
			
		}
		closedir($handle);
		
		return $list_of_icons;
	}
}







	// custom comment list (not discussion, see below)
	function mytheme_comment($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment; ?>
			<li <?php comment_class('clearfix'); ?> id="li-comment-<?php comment_ID() ?>">
			<div id="comment-<?php comment_ID(); ?>" class="clearfix">
				<div class="left">
					  <div class="comment-author vcard">
						 <?php echo get_avatar($comment,$size='60',$default='<path_to_url>' ); ?>
						 <?php echo "<div class='author'>".get_comment_author_link()."</div>"; ?>
						 <?php echo "<div class='comment-date'><small>".get_comment_date('m.d.Y')."</small></div>"; ?>
					  </div>
				  </div><!-- end left -->
				  
				  <div class="right-comments">
					<div class="comment-text">
						<?php if ($comment->comment_approved == '0') : ?><p class="moderated">Your comment is awaiting moderation.</p><?php endif; ?>
						<?php comment_text() ?>
					</div>
					
					<div class="reply"><?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?></div>
				 </div><!-- end right-comments -->
				 </div>
		<?php
	}
	
	// custom comment list for trackbacks
	function mytheme_comment_trackback($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment; 
		?>
			<li <?php comment_class('clearfix'); ?> id="li-comment-<?php comment_ID() ?>">
			<div id="comment-<?php comment_ID(); ?>" class="clearfix">
					<?php echo "<div class='author'><em>Trackback:</em> ".get_comment_author_link()."</div>"; ?>
                	<?php echo strip_tags(substr(get_comment_text(),0, 110)) . "..."; ?>
                    <?php comment_author_url_link('', '<small>', '</small>'); ?>
             </div>
		<?php
	}
	
	// custom comment list for pingbacks
	function mytheme_comment_pingback($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment; 
		?>
			<li <?php comment_class('clearfix'); ?> id="li-comment-<?php comment_ID() ?>">
			<div id="comment-<?php comment_ID(); ?>" class="clearfix">
					<?php echo "<div class='author'><em>Pingback:</em> ".get_comment_author_link()."</div>"; ?>
                	<?php echo strip_tags(substr(get_comment_text(),0, 110)) . "..."; ?>
                    <?php comment_author_url_link('', '<small>', '</small>'); ?>
             </div>
		<?php
	}
	
	
	
	
	

	function lux_meta()
	{	
		?><p class="postmeta clearfix"><a class="comment-count" href="<?php comments_link(); ?>"><?php comments_number('0 Comments', '1 Comment', '% Comments'); ?></a><?php the_time('l'); ?> &bull; <?php the_time('F j'); ?>, <?php the_time('Y'); ?> &bull; by <?php the_author_posts_link(); ?><?php edit_post_link('edit', ' // ', ''); ?></p><?php
	}
	function lux_meta_bottom()
	{	
		$tag_list = get_the_tag_list( $before = '<p class="postmeta clearfix">Tagged with: ', $sep = ', ', $after = '</p>' )
		?>
			<p class="postmeta clearfix">Posted in: <?php the_category(', ') ?></p>
			<?php echo $tag_list; ?>
		<?php
	}
	function lux_title()
	{
		?><h2 class="posttitle"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2><?php
	}

	function lux_post_nav()
	{
		?><div class="pagenavigation clearfix"><div class="left"><?php next_posts_link('Older Entries') ?></div><div class="right"><?php previous_posts_link('Newer Entries') ?></div></div><?php
	}
	
