<?php
	/*
	if($custom_settings["background"]) { echo "body { background-image:url(".$custom_settings["background"]."); } "; } 
	if($custom_settings["css"]["links"]) { echo " a { color:".$custom_settings["css"]["links"]."; } "; } 
	if($custom_settings["css"]["links_hover"]) { echo " a:hover { color:".$custom_settings["css"]["links_hover"]."; } "; } 
	 
	*/
	if($custom_settings["css"]["extra"]) {
		echo $custom_settings["css"]["extra"];
	}
?>
 /*body { color:green; } */