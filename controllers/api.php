<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:			Social Igniter : Paypal : API Controller
* Author: 		firepony
* 		  		tjgillies@gmail.com
* 
* Project:		http://social-igniter.com
* 
* Description: This file is for the Paypal API Controller class
*/
class Api extends Oauth_Controller
{
    function __construct()
    {
        parent::__construct();
     /*   
        		$config['Sandbox'] = TRUE;
$config['APIVersion'] = '85.0';
$config['APIUsername'] = $config['Sandbox'] ? 'tjgill_1342254503_biz_api1.gmail.com' : 'PRODUCTION_USERNAME_GOES_HERE';
$config['APIPassword'] = $config['Sandbox'] ? '1342254527' : 'PRODUCTION_PASSWORD_GOES_HERE';
$config['APISignature'] = $config['Sandbox'] ? 'An5ns1Kso7MWUdW4ErQKJJJ4qi4-A2.oNj-q1ACiwa2FlftkxYynZWAS' : 'PRODUCTION_SIGNATURE_GOES_HERE';
$config['DeviceID'] = $config['Sandbox'] ? '' : 'PRODUCTION_DEVICE_ID_GOES_HERE';
$config['ApplicationID'] = $config['Sandbox'] ? 'APP-80W284485P519543T' : 'PRODUCTION_APP_ID_GOES_HERE';
$config['DeveloperEmailAccount'] = $config['Sandbox'] ? 'tjgillies@gmail.com' : 'PRODUCTION_DEV_EMAIL_GOES_HERE';
*/

  $config['Sandbox'] = TRUE;
  $config['APIVersion'] = '85.0';
  $config['ApplicationID'] = 'APP-80W284485P519543T';
  $config['APIUsername'] = config_item('paypal_username');
  $config['APIPassword'] = config_item('paypal_password');
  $config['APISignature'] = config_item('paypal_signature');


		$this->load->library('paypal_adaptive', $config);

	}

    /* Install App */
	function install_get()
	{
		// Load
		$this->load->library('installer');
		$this->load->config('install');        

		// Settings & Create Folders
		$settings = $this->installer->install_settings('paypal', config_item('paypal_settings'));
	
		if ($settings == TRUE)
		{
            $message = array('status' => 'success', 'message' => 'Yay, the Paypal App was installed');
        }
        else
        {
            $message = array('status' => 'error', 'message' => 'Dang Paypal App could not be installed');
        }		
		
		$this->response($message, 200);
	} 
	
	
	function make_payment_post(){
	  error_log("calling make payment method");
	  		// Prepare request arrays
		$PayRequestFields = array(
								'ActionType' => 'PAY', 								// Required.  Whether the request pays the receiver or whether the request is set up to create a payment request, but not fulfill the payment until the ExecutePayment is called.  Values are:  PAY, CREATE, PAY_PRIMARY
								'CancelURL' => 'http://www.google.com', 									// Required.  The URL to which the sender's browser is redirected if the sender cancels the approval for the payment after logging in to paypal.com.  1024 char max.
								'CurrencyCode' => 'USD', 								// Required.  3 character currency code.
								'FeesPayer' => 'SENDER', 									// The payer of the fees.  Values are:  SENDER, PRIMARYRECEIVER, EACHRECEIVER, SECONDARYONLY
								'IPNNotificationURL' => base_url().'/api/paypal/ipn', 						// The URL to which you want all IPN messages for this payment to be sent.  1024 char max.
								'Memo' => 'Why wont memo set', 										// A note associated with the payment (text, not HTML).  1000 char max
								'Pin' => '', 										// The sener's personal id number, which was specified when the sender signed up for the preapproval
								'PreapprovalKey' => '', 							// The key associated with a preapproval for this payment.  The preapproval is required if this is a preapproved payment.  
								'ReturnURL' => 'http://wwww.google.com', 									// Required.  The URL to which the sener's browser is redirected after approvaing a payment on paypal.com.  1024 char max.
								'ReverseAllParallelPaymentsOnError' => '', 			// Whether to reverse paralel payments if an error occurs with a payment.  Values are:  TRUE, FALSE
								'SenderEmail' => '', 								// Sender's email address.  127 char max.
								'TrackingID' => ''									// Unique ID that you specify to track the payment.  127 char max.
								);
								
		$ClientDetailsFields = array(
								'CustomerID' => '', 								// Your ID for the sender  127 char max.
								'CustomerType' => '', 								// Your ID of the type of customer.  127 char max.
								'GeoLocation' => '', 								// Sender's geographic location
								'Model' => '', 										// A sub-identification of the application.  127 char max.
								'PartnerName' => ''									// Your organization's name or ID
								);
								
		$FundingTypes = array('ECHECK', 'BALANCE', 'CREDITCARD');
		
		$Receivers = array();
		# TODO change email to email in settings
		$Receiver = array(
						'Amount' => $this->input->post('amount'), 											// Required.  Amount to be paid to the receiver.
						'Email' => 'tjgillies@gmail.com', 												// Receiver's email address. 127 char max.
						'InvoiceID' => '', 											// The invoice number for the payment.  127 char max.
						'PaymentType' => 'PERSONAL', 										// Transaction type.  Values are:  GOODS, SERVICE, PERSONAL, CASHADVANCE, DIGITALGOODS
						'PaymentSubType' => '', 									// The transaction subtype for the payment.
						'Phone' => array('CountryCode' => '', 'PhoneNumber' => '', 'Extension' => ''), // Receiver's phone number.   Numbers only.
						'Primary' => ''												// Whether this receiver is the primary receiver.  Values are:  TRUE, FALSE
						);
		array_push($Receivers,$Receiver);
		
		$SenderIdentifierFields = array(
										'UseCredentials' => ''						// If TRUE, use credentials to identify the sender.  Default is false.
										);
										
		$AccountIdentifierFields = array(
										'Email' => '', 								// Sender's email address.  127 char max.
										'Phone' => array('CountryCode' => '', 'PhoneNumber' => '', 'Extension' => '')								// Sender's phone number.  Numbers only.
										);
										
		$PayPalRequestData = array(
							'PayRequestFields' => $PayRequestFields, 
							'ClientDetailsFields' => $ClientDetailsFields, 
							'FundingTypes' => $FundingTypes, 
							'Receivers' => $Receivers, 
							'SenderIdentifierFields' => $SenderIdentifierFields, 
							'AccountIdentifierFields' => $AccountIdentifierFields
							);	
							
		$PayPalResult = $this->paypal_adaptive->Pay($PayPalRequestData);
		error_log("Getting result data");
		
		if(!$this->paypal_adaptive->APICallSuccessful($PayPalResult['Ack']))
		{
			$errors = array('Errors'=>$PayPalResult['Errors']);
			//$this->load->view('paypal_error',$errors);
		}
		if(!isset($errors)){
		  redirect($PayPalResult['RedirectURL']);
		}
		
      $this->data['errors'] = $errors;
      $this->data['payload'] = $PayPalResult;
      $this->render();
	}
	
	function ipn_post(){
	  error_log('ipn function called');
	  $req = 'cmd=' . urlencode('_notify-validate');
 
    foreach ($_POST as $key => $value) {
      $value = urlencode(stripslashes($value));
      $req .= "&$key=$value";
    }
     
     
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.sandbox.paypal.com/cgi-bin/webscr');
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.sandbox.paypal.com'));
    error_log("req is $req");
    $res = curl_exec($ch);
    curl_close($ch);
     
     
    // assign posted variables to local variables
    $item_name = $_POST['item_name'];
    $item_number = $_POST['item_number'];
    $payment_status = $_POST['payment_status'];
    $payment_amount = $_POST['mc_gross'];
    $payment_currency = $_POST['mc_currency'];
    $txn_id = $_POST['txn_id'];
    $receiver_email = $_POST['receiver_email'];
    $payer_email = $_POST['payer_email'];
     
     
    if (strcmp ($res, "VERIFIED") == 0) {
      // check the payment_status is Completed
      // check that txn_id has not been previously processed
      // check that receiver_email is your Primary PayPal email
      // check that payment_amount/payment_currency are correct
      // process payment
    }
    else if (strcmp ($res, "INVALID") == 0) {
      // log for manual investigation
    }
    
    error_log("We got $res");
	}

}