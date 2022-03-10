<?php
class Controller_Mass extends Controller
{
	function __construct()
	{
		$this->model = new Model_Mass();
		$this->view = new View();
	}


	function action_index()
	{	
		$data = $this->model->get_data();
		$this->view->generate('mass_view.php', 'template_view_1.php', $data);
	}
}

?>