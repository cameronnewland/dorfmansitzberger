<?php global $custom_settings; ?>
		</div><!--// container_12 -->
	</div><!--//main-->
	<div id="footer" class="clearfix">
		<div class="container_12">
			<div class="grid_6">
				<p><?php 
					$footer_text = $custom_settings["footer_text"];
					if($footer_text) { echo html_entity_decode(str_replace("CURRENTYEAR",date("Y"),$footer_text), ENT_QUOTES); }else{ ?><?php bloginfo('name'); ?> - &copy; <?php echo date("Y"); ?><?php }
				?></p>
			</div><!--//grid6-->
			<div class="grid_6">
            
            	<?php
					
					$social_footer_icons_count = 0;
					for($i=0; $i<=10; $i++){
						if($custom_settings["social"][$i]["url"] && $custom_settings["social"][$i]["icon"])
						{
							$social_footer_icons .= "<li><a href='".$custom_settings["social"][$i]["url"]."'><img src='".get_bloginfo("template_directory") ."/images/social_icons/".$custom_settings["social"][$i]["icon"]."' alt='".substr($custom_settings["social"][$i]["icon"],0,-4)."' /></a></li>";
							$social_footer_icons_count++;
						}
					}
					if($social_footer_icons_count > 0)
					{
						echo "<ul class='clean social softbutton right'>";
						echo $social_footer_icons;
						echo "</ul>";
					}
				?>
			</div><!--//grid6-->
			
		</div><!--//container_12-->
	</div><!--//footer-->    
	
</div><!--//outer-->
<?php wp_footer(); ?>
</body>
</html>