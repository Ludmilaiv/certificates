<?php
class Controller_Cert extends Controller
{
	function __construct()
	{
		$this->model = new Model_Cert();
		$this->view = new View();
	}


	function action_index()
	{	
		$data = $this->model->get_data();
		$this->view->generate('cert_view.php', 'template_view.php', $data);
	}
}

?>