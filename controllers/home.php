<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:			Social Igniter : Paypal : Home Controller
* Author: 		firepony
* 		  		tjgillies@gmail.com
* 
* Project:		http://social-igniter.com
* 
* Description: This file is for the Paypal Home Controller class
*/
class Home extends Dashboard_Controller
{
    function __construct()
    {
        parent::__construct();

		$this->load->config('paypal');

		$this->data['page_title'] = 'Paypal';
		/*
		$config['Sandbox'] = TRUE;
$config['APIVersion'] = '85.0';
$config['APIUsername'] = $config['Sandbox'] ? 'tjgill_1342254503_biz_api1.gmail.com' : 'PRODUCTION_USERNAME_GOES_HERE';
$config['APIPassword'] = $config['Sandbox'] ? '1342254527' : 'PRODUCTION_PASSWORD_GOES_HERE';
$config['APISignature'] = $config['Sandbox'] ? 'An5ns1Kso7MWUdW4ErQKJJJ4qi4-A2.oNj-q1ACiwa2FlftkxYynZWAS' : 'PRODUCTION_SIGNATURE_GOES_HERE';
$config['DeviceID'] = $config['Sandbox'] ? '' : 'PRODUCTION_DEVICE_ID_GOES_HERE';
$config['ApplicationID'] = $config['Sandbox'] ? '' : 'PRODUCTION_APP_ID_GOES_HERE';
$config['DeveloperEmailAccount'] = $config['Sandbox'] ? '' : 'PRODUCTION_DEV_EMAIL_GOES_HERE';
*/
  $config['Sandbox'] = TRUE;
  $config['APIVersion'] = '85.0';
  $config['APIUsername'] = config_item('paypal_username');
  $config['APIPassword'] = config_item('paypal_password');
  $config['APISignature'] = config_item('paypal_signature');
		$this->load->library('paypal_adaptive', $config);
	}
	
  function make_payment(){
    $this->render();
  }	
  
	function custom()
	{
		$this->data['sub_title'] = 'Custom';
	
		$this->render();
	}
	
	function personal_data()
	{
		// Prepare request arrays
		$AttributeList = array(
						'http://axschema.org/namePerson/first'
					);
							
		// Pass data into class for processing with PayPal and load the response array into $PayPalResult
		$PayPalResult = $this->paypal_adaptive->GetBasicPersonalData($AttributeList);
		
		if(!$this->paypal_adaptive->APICallSuccessful($PayPalResult['Ack']))
		{
			$errors = array('Errors'=>$PayPalResult['Errors']);
			//$this->load->view('paypal_error',$errors);
		}
      $this->data['errors'] = $errors;
      $this->data['payload'] = $PayPalResult;
      $this->render();
	}
	
	
	function execute_payment()
	{
		// Prepare request arrays
		$ExecutePaymentFields = array(
									'PayKey' => 'PA-0TJ018768B7623541', 								// The pay key that identifies the payment to be executed.  This is the key returned in the PayResponse message.
									'FundingPlanID' => '' 							// The ID of the funding plan from which to make this payment.
									);
									
		$PayPalRequestData = array('ExecutePaymentFields' => $ExecutePaymentFields);	
		$PayPalResult = $this->paypal_adaptive->ExecutePayment($PayPalRequestData);
		
		if(!$this->paypal_adaptive->APICallSuccessful($PayPalResult['Ack']))
		{
			$errors = array('Errors'=>$PayPalResult['Errors']);
		}
    $this->data['errors'] = $errors;
    $this->data['payload'] = $PayPalResult;
    $this->render();

	}
	
	function pay()
	{
		// Prepare request arrays
		$PayRequestFields = array(
								'ActionType' => 'PAY', 								// Required.  Whether the request pays the receiver or whether the request is set up to create a payment request, but not fulfill the payment until the ExecutePayment is called.  Values are:  PAY, CREATE, PAY_PRIMARY
								'CancelURL' => 'http://www.google.com', 									// Required.  The URL to which the sender's browser is redirected if the sender cancels the approval for the payment after logging in to paypal.com.  1024 char max.
								'CurrencyCode' => 'USD', 								// Required.  3 character currency code.
								'FeesPayer' => 'SENDER', 									// The payer of the fees.  Values are:  SENDER, PRIMARYRECEIVER, EACHRECEIVER, SECONDARYONLY
								'IPNNotificationURL' => 'http://requestb.in/1452nhl1', 						// The URL to which you want all IPN messages for this payment to be sent.  1024 char max.
								'Memo' => '', 										// A note associated with the payment (text, not HTML).  1000 char max
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
		$Receiver = array(
						'Amount' => '1', 											// Required.  Amount to be paid to the receiver.
						'Email' => 'test@tester.com', 												// Receiver's email address. 127 char max.
						'InvoiceID' => '', 											// The invoice number for the payment.  127 char max.
						'PaymentType' => '', 										// Transaction type.  Values are:  GOODS, SERVICE, PERSONAL, CASHADVANCE, DIGITALGOODS
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
	
	function preapprove()
	{
		// Prepare request arrays
		$PreapprovalFields = array(
								   'CancelURL' => 'http://www.google.com',  								// Required.  URL to send the browser to after the user cancels.
								   'CurrencyCode' => 'USD', 							// Required.  Currency Code.
								   'DateOfMonth' => '', 							// The day of the month on which a monthly payment is to be made.  0 - 31.  Specifying 0 indiciates that payment can be made on any day of the month.
								   'DayOfWeek' => '', 								// The day of the week that a weekly payment should be made.  Allowable values: NO_DAY_SPECIFIED, SUNDAY, MONDAY, TUESDAY, WEDNESDAY, THURSDAY, FRIDAY, SATURDAY
								   'EndingDate' => '2012-09-10Z', 								// Required.  The last date for which the preapproval is valid.  It cannot be later than one year from the starting date.
								   'IPNNotificationURL' => '', 						// The URL for IPN notifications.
								   'MaxAmountPerPayment' => '', 					// The preapproved maximum amount per payment.  Cannot exceed the preapproved max total amount of all payments.
								   'MaxNumberOfPayments' => '', 					// The preapproved maximum number of payments.  Cannot exceed the preapproved max total number of all payments. 
								   'MaxTotalAmountOfAllPaymentsPerPeriod' => '', 	// The preapproved maximum number of all payments per period.
								   'MaxTotalAmountOfAllPayments' => '', 			// The preapproved maximum total amount of all payments.  Cannot exceed $2,000 USD or the equivalent in other currencies.
								   'Memo' => '', 									// A note about the preapproval.
								   'PaymentPeriod' => '', 							// The pament period.  One of the following:  NO_PERIOD_SPECIFIED, DAILY, WEEKLY, BIWEEKLY, SEMIMONTHLY, MONTHLY, ANNUALLY
								   'PinType' => 'NOT_REQUIRED', 								// Whether a personal identification number is required.  It is one of the following:  NOT_REQUIRED, REQUIRED
								   'ReturnURL' => 'http://www.google.com', 								// URL to return the sender to after approving at PayPal.
								   'SenderEmail' => '', 							// Sender's email address.  If not specified, the email address of the sender who logs on to approve is used.
								   'StartingDate' => '2012-07-14Z', 							// Required.  First date for which the preapproval is valid.  Cannot be before today's date or after the ending date.
								   'FeesPayer' => '', 								// The payer of the PayPal fees.  Values are:  SENDER, PRIMARYRECEIVER, EACHRECEIVER, SECONDARYONLY
								   'DisplayMaxTotalAmount' => ''					// Whether to display the max total amount of this preapproval.  Values are:  true/false
								   );
		
		$ClientDetailsFields = array(
									 'CustomerID' => '', 						// Your ID for the sender.
									 'CustomerType' => '', 						// Your ID of the type of customer.
									 'GeoLocation' => '', 						// Sender's geographic location.
									 'Model' => '', 							// A sub-id of the application
									 'PartnerName' => ''						// Your organization's name or ID.
									 );
		
		$PayPalRequestData = array(
							 'PreapprovalFields' => $PreapprovalFields, 
							 'ClientDetailsFields' => $ClientDetailsFields
							 );	
		
		$PayPalResult = $this->paypal_adaptive->Preapproval($PayPalRequestData);
		
		if(!$this->paypal_adaptive->APICallSuccessful($PayPalResult['Ack']))
		{
			$errors = array('Errors'=>$PayPalResult['Errors']);
			//$this->load->view('paypal_error',$errors);
		}
		
		$this->data['errors'] = $errors;
    $this->data['payload'] = $PayPalResult;
    $this->render();

		
		
	}
	
}