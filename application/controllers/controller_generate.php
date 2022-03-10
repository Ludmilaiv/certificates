<?php
class Controller_Generate extends Controller
{
	function __construct()
	{
		$this->model = new Model_Generate();
	}

	function action_index()
	{	
		$this->model->get_data();
	}
}

?>