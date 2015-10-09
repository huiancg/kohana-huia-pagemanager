<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Manager_Page_Block_Template extends Controller_Manager_App {

	public function action_index()
	{
		$this->ignore_actions[] = 'containers';
		
		parent::action_index();	
	}

	public function action_edit()
	{
		parent::action_edit();
		$this->labels['containers'] = 'Containers';
		$this->has_many['containers']['far_primary_key'] = 'container_id';
		$this->has_many['containers']['model'] = 'Page_Block_Template';
		$this->has_many['containers']['through'] = 'page_block_template_containers';
	}

	public function save()
	{
		$containers = $this->request->post('containers');
		$this->request->post('containers', array());
		$page_block_template_id = $this->request->param('id');

		Model_Page_Block_Template_Container::save_template($page_block_template_id, $containers);

		parent::save();
	}

}