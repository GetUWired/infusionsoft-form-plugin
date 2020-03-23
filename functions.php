<?php
	/*
	Plugin Name: GUW Sales Page Infusionsoft Forms
	Description: Plugin inserts an Infusionsoft form directly onto a sales page 
	Version: 1.0
	Author: GetUWired
	Author URI: http://getuwired.com
	*/


	include('shortcodes.php');


	add_shortcode('createISField','create_infusionsoft_field');
?>