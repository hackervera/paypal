<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:			Social Igniter : Paypal : Controller
* Author: 		firepony
* 		  		tjgillies@gmail.com
* 
* Project:		http://social-igniter.com
* 
* Description: This file is for the public Paypal Controller class
*/
class Paypal extends Site_Controller
{
    function __construct()
    {
        parent::__construct();       
	}
	
	function index()
	{
		$this->data['page_title'] = 'Paypal';
		$this->render();
	}

	function view() 
	{		
		// Basic Content Redirect	
		$this->render();
	}
	
}
