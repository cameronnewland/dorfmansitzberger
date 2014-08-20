<?php 
	global $custom_settings; 
	/* Allow the overriding of custom settings array on a per post/page basis using custom fields: */
	if(is_single() || is_page()) { $post_or_page_id = $post->ID; }
	$data = get_post_meta($post_or_page_id, "custom_meta_values", true); 
	if($data["hide_thumbnail_on_single"])
		$custom_settings["layout"]["hide_thumbnail_on_single"] = $data["hide_thumbnail_on_single"];
	if($data["columns"])
		$custom_settings["layout"]["columns"] = $data["columns"];		
	/* ------------------------------------------------------------------------------------------- */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<title><?php wp_title(' - ', true, 'right'); ?> <?php bloginfo('name'); ?></title>
	<?php add_action('wp_head', 'add_stylesheets'); ?>
    <?php //add_action('wp_head', 'add_javascript'); ?>
    
    <?php
		// Cufon fonts - use ColaborateLight_400 as default if no other font selected.
		$font = "ColaborateLight_400.font.js"; // default
		if($custom_settings["font"]){ $font = $custom_settings["font"];	}
		echo "<!--[if lt IE 8]><script src='http://ie7-js.googlecode.com/svn/version/2.0(beta3)/IE8.js' type='text/javascript'></script><![endif]-->";
		wp_enqueue_script('jquery'); // load new jquery
		wp_enqueue_script('cudazi_cufon',get_bloginfo('template_directory')."/js/cufon-yui.js");
		wp_enqueue_script('cudazi_fonts',get_bloginfo('template_directory')."/js/fonts/".$font);
		wp_enqueue_script('cudazi_superfish',get_bloginfo('template_directory')."/js/superfish.js");
		wp_enqueue_script('cudazi_supersubs',get_bloginfo('template_directory')."/js/supersubs.js");
		wp_enqueue_script('cudazi_cycle',get_bloginfo('template_directory')."/js/jquery.cycle.min.js");
		wp_enqueue_script('cudazi_general',get_bloginfo('template_directory')."/js/general.js");
		echo html_entity_decode($custom_settings["additional_js"], ENT_QUOTES);
	?>
    
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="outer">
    <div id="main">
		<div class="container_12 clearfix">
			<div id="header" class="clearfix">
				<div class="grid_8">
					<div class="logo">
						<?php if($custom_settings["logo_text_based"]){ ?>
							<h1><a href="<?php bloginfo("url"); ?>"><?php echo $custom_settings["logo_text_based"]; ?></a></h1>
						<?php }else{ ?>
							<a href="<?php bloginfo("url"); ?>"><img src="<?php if($custom_settings["logo"]) { echo $custom_settings["logo"]; }else{ echo get_bloginfo('template_directory') . "/images/logo.gif"; } ?>" alt="Logo" /></a>
						<?php } ?>
					</div><!--//logo-->
				</div><!--//grid2-->
				<div class="grid_4">
                	<?php if($custom_settings["search_disabled"]) { echo "<!-- search disabled-->"; }else{ ?>
					<div class="toolbox-horizontal right">
						<div id="searchform">
							<form action="<?php bloginfo("url"); ?>/" method="get" class="clearfix">
								<input type="image" src="<?php bloginfo('template_directory'); ?>/images/search_icon.gif" value="Search" id="searchbutton" class="right" />
								<input type="text" value="Search Our Site" name="s" id="s" />
							</form>
						</div>
					</div><!--//toolbox-->
                    <?php } ?>
				</div><!--//grid_10-->
				<div class="grid_12">
					
                    <?php wp_nav_menu( 
						array( 
							'container' => 'div', 
							'container_class' => 'menu-horizontal clearfix', 
							'menu_class' => 'sf-menu', 
							'theme_location' => 'primary', 
							'fallback_cb' => 'cudazi_fallback_menu' ) 
					); ?>
                    <?php function cudazi_fallback_menu() { /* backup menu if 3.0 menu is not in use */ ?>
                        <div class="menu-horizontal clearfix">
                            <ul class="sf-menu">
                                <?php if($custom_settings["dropdown_disabled"]) { $depth = 1; } ?>
                                <?php $exclude = $custom_settings["menu_exclude"]; ?>
                                <?php wp_list_pages("title_li=&exclude=".$exclude."&depth=".$depth); ?>
                            </ul>
                        </div><!--// menu-horizontal-->
                    <?php } ?>
                    
				</div><!-- // grid_12-->
			</div><!--//header-->
