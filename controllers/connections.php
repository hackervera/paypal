<?php
class Connections extends Site_Controller
{
  function __construct()
  {
      parent::__construct();

  }
			
	function index()
	{		
    $this->response("testing",200);
  }
  
  function add(){
    $this->render();
  }
}