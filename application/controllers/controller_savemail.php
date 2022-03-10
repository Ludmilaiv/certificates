<?php
class Controller_Savemail extends Controller
{
	function __construct()
	{
		$this->model = new Model_Savemail();
	}

	function action_index()
	{	
		$this->model->get_data();
	}
}

?>