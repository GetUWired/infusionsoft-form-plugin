<?php
	/*
	Plugin Name: GUW Sales Page Infusionsoft Forms
	Description: Plugin inserts an Infusionsoft form directly onto a sales page 
	Version: 1.0
	Author: GetUWired
	Author URI: http://getuwired.com
	*/


	include('shortcodes.php');

	add_shortcode('createISField','cf_shortcode');

	function cf_shortcode($atts = [], $content = null, $tag = '') {
		$atts = array_change_key_case((array)$atts, CASE_LOWER);
		create_infusionsoft_fields($atts);
		deliver_mail();
	}


	 
	function wporg_shortcodes_init()
	{
		add_shortcode('wporg', 'wporg_shortcode');
	}
	 
	add_action('init', 'wporg_shortcodes_init');



	// this doesn't seem to be working
	add_action('wp_enqueue_scripts', 'add_my_plugin_stylesheet');
	function add_my_plugin_stylesheet() {
		wp_register_style('mypluginstylesheet', '/wp-content/plugins/guw-sales-page-IS-forms/style-forms.css');
		wp_enqueue_style('mypluginstylesheet');	
		echo"howdy";	
	}


	function create_infusionsoft_fields($atts) {

		$html = '<link rel="stylesheet" type="text/css" href="http://mbtstaging.wpengine.com/wp-content/plugins/guw-sales-page-IS-forms/style-forms.css" />';

		$html .= '<script src="http://mbtstaging.wpengine.com/wp-content/plugins/guw-sales-page-IS-forms/script.js"></script>';
	
		$html .= '<div id="form_wrapper" class="form_wrapper" style="max-width: 930px; width:100%;margin:50px auto 0;border: 1px solid #f3ab3c;">';
		$html .= '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '#order_form" id="sales_page_form" style="width:100%;padding:3%;margin:0 auto;" method="post"><div class="lds-ring" id="lds-ring"><div></div><div></div><div></div><div></div></div><div id="form_inner_wrapper">';
		$html .= '<input type="hidden" value="2" name="product_id">';
		// $html .= '<input type="hidden" value="create_product_order" name="action">';
		$html .= '<div style="display:block;margin:10px;"><input required type="text" pattern="[a-zA-Z0-9 ]+" id="first_name" name="first_name" placeholder="First Name" style="width: 49%;">';
		$html .= '<input required type="text" id="last_name" name="last_name" pattern="[a-zA-Z0-9 ]+" placeholder="Last Name" style="width: 49%;margin-left:1%;"></div>';
		$html .= '<div style="display:block;margin:10px;"><input required type="email" id="email" name="email" placeholder="Email" style="width:99%;"></div>';

		$html .= '<div style="display:block;margin:10px;">';
		$html .= '<input required type="text" pattern="[a-zA-Z0-9 ]+" id="street_address" name="street_address" placeholder="Street" style="width:49%;">';
		$html .= '<input required type="text" pattern="[a-zA-Z0-9 ]+" id="city" name="city" placeholder="City" style="width:49%;margin-left:1%;">';
		$html .= '</div>';

		$html .= '<div style="display:block;margin:10px;">';
		$html .= '<input required type="text" id="state" pattern="[a-zA-Z0-9 ]+" name="state" placeholder="State" style="width:39%;">';
		$html .= '<input required type="number" pattern="[0-9]+" id="zip_code" name="zip_code" placeholder="Zip" style="width:19%; margin-left:1%;">';
		$html .= '<input required type="text" pattern="[a-zA-Z0-9 ]+" id="country" name="country" placeholder="Country" style="width:39%;margin-left:1%;">';
		$html .= '</div>';
		$html .= '<div style="display:block;margin:10px;"><input type="text" required maxlength="16" pattern="\d{16}" id="credit_card" name="credit_card" placeholder="Card Number" style="width: 49%;">';

		$html .= '<input required type="text" id="expiration_date_month" maxlength="2" pattern="\d{2}" name="expiration_date_month" placeholder="Month" style="width:11%;margin-left:1%;">';
		$html .= '<input required type="text" id="expiration_date_year" maxlength="4" pattern="\d{4}" name="expiration_date_year" placeholder="Year" style="width:11%;margin-left:1%;">';

		$html .= '<input required type="text" id="csv" name="csv" maxlength="3" pattern="\d{3}" placeholder="CSV" style="width:25%;margin-left:1%;"></div>';
		$html .= '<input type="hidden" id="product_id" name="product_id" value="'.$atts['product_id'].'">';
		$html .= '<input type="hidden" id="subscription_id" name="subscription_id" value="'.$atts['subscription_id'].'">';
		$html .= '<input type="hidden" id="price" name="price" value="'.$atts['price'].'">';
		$html .= '<p id="product_description" style="margin:0 0 0 1%;padding:1%;color:#343434;font-family: "Montserrat",sans-serif;">'.$atts['description'].'</p>';
		$html .= '<div style="display:block;margin:10px auto; text-align:center;"><input id="formsubmit" onclick="lockdownform()" value="Submit My Order" type="submit" name="submit" placeholder="Submit My Order" style="margin:0 auto;text-align:center;background:#ffa800;color:#ffffff;border-radius:15px;font-family: Montserrat,sans-serif;text-transform: uppercase;font-weight:700;font-size:16px;border: 1px solid #ffa800;padding:2%;cursor:pointer;"></div></div>';
		$html .= '</form></div>';

		echo $html;

	}

function deliver_mail() {

 	// if the submit button is clicked, send the email
	if ( isset( $_POST['submit'] ) ) {
		
		echo "<style>#form_wrapper{min-height:500px;}#form_inner_wrapper{visibility:hidden;}</style><script>document.getElementById('form_wrapper').innerHTML='<p id=\"messages\">Your order has been submitted.</p>';</script>";
		
		//echo "submitting<br/>";

		// sanitize form values
		$fname = sanitize_text_field( $_POST["first_name"] );
		$lname = sanitize_text_field( $_POST["last_name"] );
		$name = $fname . ' ' . $lname;
		$email = sanitize_email( $_POST["email"] );
		$street_address = sanitize_text_field( $_POST["street_address"] );
		$city = sanitize_text_field( $_POST["city"] );
		$state = sanitize_text_field( $_POST["state"] );
		$zip = sanitize_text_field( $_POST["zip_code"] );
		$country = sanitize_text_field( $_POST["country"] );		
		$credit_card = sanitize_text_field( $_POST["credit_card"] );
		$expiration_date_year = $_POST["expiration_date_year"];
		$expiration_date_month = $_POST["expiration_date_month"];
		$csv = sanitize_text_field( $_POST["csv"] );
		$product_id = $_POST["product_id"];
		$subscription_id = $_POST["subscription_id"];
		$price = $_POST["price"];

		create_order($fname, $lname, $email, $street_address, $city, $state, $zip, $country, $credit_card, $expiration_date_year, $expiration_date_month, $csv, $product_id, $subscription_id, $price);

	}
}

function create_order($fname, $lname, $email, $street_address, $city, $state, $zip, $country, $credit_card, $expiration_date_year, $expiration_date_month, $csv, $product_id, $subscription_id, $price) {
	global $i2sdk;
	$app = $i2sdk->isdk;

	$debug = 0;

	if($debug){echo "create order<br/>";}

	// $conDat = array('JobTitle' => 'connect2');
	// $conID = $app->updateCon(24782, $conDat);

	//collect information about the contact
	$conData = array(
		'FirstName' => $fname,
		'LastName' => $lname,
		'Email' => $email,
		'StreetAddress1' => $street_address,
		'Country' => $country,
		'City' => $city,
		'State' => $state,
		'PostalCode' => $zip,
	);

	//look to see if a contact exists
	$returnFields = array('Id', 'FirstName','LastName');
	$contact = $app->findByEmail($email, $returnFields);

	//if contact exists, update the contact with the information above 
	//if contact doesn't exist, create a contact	
	if( $contact[0]['Id'] ){
		$contactId = $app->updateCon( $contact[0]['Id'], $conData );
	} else {
		$contactId = $app->addCon($conData);
	}

	if($debug){echo "<br/>".$contactId."<br/>";}

	// inserting credit card into infusionsoft and validating that the card is good
	if ($credit_card[0] == '4') {
		$cardtype = 'Visa';
	} elseif ($credit_card[0] == '5') {
		$cardType = 'Mastercard';
	} elseif ($credit_card[0] == '3') {
		$cardtype = 'American Express';
	} else {
		$cardtype = 'unknown';
	}

	$card = array(
		'CardType' => $cardtype,
        'ContactId' => $contactId,
        'CardNumber' => $credit_card,
        'ExpirationMonth' => $expiration_date_month,
        'ExpirationYear' => $expiration_date_year,
        'CVV2' => $csv
	);
	
	if($debug){
		echo "<br><pre>";
		print_r($card);
		echo "</pre>";
	}

	$result_validate_card = $app->validateCard($card); 

	$last_four = substr($credit_card, -4); 

	$card_query = array(
		'Last4' => $last_four,
		'ContactId' => $contactId,
		'Status' => 3
	);

	if($debug){
		echo "<br/>last four: ";
		echo $last_four;
		echo "<br>";

		echo "<br/>";
		print_r($result_validate_card);
		echo"<br/>";
	}

	$returnFields = array(
        'Id', 'FirstName', 'LastName', 'ExpirationMonth', 'ExpirationYear', 'ContactId', 'Status'
	);

	$result_card_search = $app->dsQuery("CreditCard",99,0,$card_query,$returnFields);

	if($debug){
		echo "<br/>Card dup check:<pre> ";
		print_r($result_card_search);
		echo"</pre><br/> after";
	}

	if ($result_validate_card[Valid] == true) {
			
		if($result_card_search[0][Id]){
			$cardID = $result_card_search[0][Id];
		}else{

			$conID = $app->updateCon($contactId, $card);
			
			if($debug){
				echo "<br/>Card Added: ";
			}

			$data = array(
				'BillName' => $fname ." ". $lname,
				'NameOnCard' => $fname ." ". $lname,
				'FirstName' => $fname,
				'LastName' => $lname,
				'BillAddress1'  => $street_address,
				'BillCity'     => $city,
				'BillCountry'     => $country,
				'BillState'     => $state,
				'BillZip'     => $zip,
				'Email'     => $email,
				'CVV2'     => $csv,
				'CardNumber'     => $credit_card,
				'CardType'     => $cardtype,
				'ContactId'     => $contactId,
				'ExpirationMonth'     => $expiration_date_month,
				'ExpirationYear'     => $expiration_date_year
			);
			$cardID = $app->dsAdd("CreditCard", $data);
			
			if($debug){
				print_r($cardID);
				echo"<br/>";
			}

		}

		$order_date = $app->infuDate(date("d-m-Y"));
		
		if($debug){
			echo "<br/>date:";
			echo $order_date;
		}

		$invoiceId = $app->blankOrder($contactId,"GUW Wordpress Order Form Order", $order_date, 0, 0);
		
		if($debug){
			echo "<br/>invoice id:";
			echo $invoiceId;
			echo "<br>product: ". $product_id."<br>price: ". $price;
		}

		$result_add_order_item = $app->addOrderItem((int)$invoiceId, (int)$product_id, 9, (double)$price, 1, "Order from Plugin", "This order item" );

		if($debug){
			echo "<br/>add order items: ";
			print_r($result_add_order_item);
		}

		if($subscription_id == 7){
			$oneYearOn = date('Y-m-d',strtotime(date("Y-m-d", mktime()) . " + 365 day"));
			$newdate = $app->infuDate($oneYearOn);
			$subscription_price = 59.95;
		}
		
		if($subscription_id == 19){
			$oneMonthOn = date('Y-m-d',strtotime(date("Y-m-d", mktime()) . " + 30 day"));
			$newdate = $app->infuDate($oneMonthOn);
			$subscription_price = 9.95;
		}

		$subscriptionId = $app->addRecurringAdv($contactId,false,(int)$subscription_id,1,(double)$subscription_price,true,2,$cardID,0,0);

		$result_update_subscription = $app->updateSubscriptionNextBillDate($subscriptionId,$newdate);

		if($debug){
			echo "<br/>next year: ";
			print_r($result_update_subscription);
		}

		if($debug){
			echo "<br/>subscription: ";
			print_r($subscriptionId);
		}

		// Pay an invoice
		//$result_pay_invoice = $app->chargeInvoice($invoiceId, "Paying invoice through API", $cardID, 2, false);
		
		if($debug){
			echo "<br/>Paying the invoice: <pre>";
			print_r($result_pay_invoice);
			echo "</pre> <br/>after";
		}

		if($result_pay_invoice[Successful] == 1){

		}else{
			echo "<script>document.getElementById('form_wrapper').innerHTML='<p>There was an error. Please <a href='https://musicbusinesstoolbox.com/contact-us/'>contact support</a> to complete your order.</p>';</script>";
		}

	} else {
		echo "<script>document.getElementById('form_wrapper').innerHTML='<p>Invalid Card. Please Try Again.</p>';</script>";
	}

}

?>