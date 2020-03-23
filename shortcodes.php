<?php

$path = $_SERVER['DOCUMENT_ROOT'];

include_once $path . '/wp-config.php';
include_once $path . '/wp-load.php';
include_once $path . '/wp-includes/wp-db.php';
include_once $path . '/wp-includes/pluggable.php';

// require("../guw-subscription-renewal-post/iSDK/isdk.php");


function create_infusionsoft_field() {
	
	// global $i2sdk;
	// $app = $i2sdk->isdk;

	// $returnFields = array('Email', 'FirstName', 'LastName', '_myCustomField');
	// $conDat = $app->loadCon(24782, $returnFields);

	// wp_enqueue_style('style-forms', '');

	$html = '<div class="form_wrapper" style="max-width: 925px; width:100%;margin:50px auto 0;border: 1px solid #f3ab3c;">';
	$html .= '<form id="sales_page_form" style="width:100%;padding:3%;margin:0 auto;">';
	$html .= '<h1 style="text-align:center;margin: 50px 0;">MBT Order Form</h1>';
	$html .= '<div style="display:block;margin:50px 0;text-align:center;"><input type="radio" id="monthly" name="price" value="Monthly">';
	$html .= '<input type="radio" id="yearly" name="price" value="Yearly"></div>';
	$html .= '<div style="display:block;margin:10px;"><input type="text" id="first_name" name="first_name" placeholder="First Name" style="width: 49%;">';
	$html .= '<input type="text" id="last_name" name="last_name" placeholder="Last Name" style="width: 49%;margin-left:1%;"></div>';
	$html .= '<div style="display:block;margin:10px;"><input type="text" id="street_address" name="street_address" placeholder="Street Address"></div>';
	$html .= '<div style="display:block;margin:10px;"><input type="text" id="city" name="city" placeholder="City">';
	$html .= '<input type="text" id="state" name="state" placeholder="State">';
	$html .= '<input type="text" id="zip_code" name="zip_code" placeholder="Zip"></div>';
	$html .= '<div style="display:block;margin:10px;"><input type="text" id="credit_card" name="credit_card" placeholder="Card Number"></div>';
	$html .= '<div style="display:block;margin:10px;"><input type="text" id="expiration_date" name="expiration_date" placeholder="Expiration Date">';
	$html .= '<input type="text" id="csv" name="csv" placeholder="CSV"></div>';
	$html .= '<button style="margin:0 auto;text-align:center;background:#ffa800;color:#ffffff;border-radius:15px;font-family: Montserrat,sans-serif;text-transform: uppercase;font-weight:700;font-size:16px;border: 1px solid #ffa800;padding:2%;">Submit My Order</button>';
	$html .= '</form></div>';

	echo $html;
	// echo "Shortcode shows here";
}




?>
