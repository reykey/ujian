<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Pyro Simple Order Controller
 * admin.php
 *
 * @author 		Hendri Lubis
 * @package 	PyroCMS
 * @subpackage 	PyroSimpleOrder Module
**/


class Admin_to_user extends Admin_Controller {

	protected $section = 'to_user';

	public function __construct() {
		parent::__construct();

        $this->lang->load('ujian');
		$this->load->driver('Streams');
		$this->load->model('soal_m');
	}


	public function index() {
		$params = array(
				'stream'		=> 'to_user',
				'namespace'		=> 'streams',
				'paginate' 		=> 'yes',
				'limit'			=> 10,
				'page_segment' 	=> 4
				);
		$entries = $this->streams->entries->get_entries($params);

		$order['paket'] = $this->soal_m->get_paket();

		$order['datax'] = $this->load->view('admin/to_user_table', array('entries'=>$entries), true);

		$this->template->build('admin/to_user', $order);
	}



	public function table() {

		$where = '';

		if($this->input->post('status')){
			$where .= SITE_REF."_so_to_user.status_pengerjaan= '".$this->input->post('status')."' ";
		}

		if($this->input->post('paket') != 'all'){
			$where .= "AND ".SITE_REF."_so_to_user.paket_id= ".$this->input->post('paket')." ";
		}

		// print_r($this->input->post('status'));
		$params = array(
				'stream'		=> 'to_user',
				'namespace'		=> 'streams',
				'paginate' 		=> 'yes',
				'limit'			=> 10,
				'page_segment' 	=> 4,
				'where'			=> $where,
				'order_by'			=> 'created'

				);
		$entries = $this->streams->entries->get_entries($params);
		echo $order['datax'] = $this->load->view('admin/to_user_table', array('entries'=>$entries), true);

	}

	// public function edit($id = 0) {

	// 	$extra = array(
 //            'return' => 'so/admin_orderto',
 //            'success_message' => lang('simple_order:success_message'),
 //            'failure_message' => lang('simple_order:failure_message'),
 //            'title' => anchor('so/admin_orderto/edit/'.$id, 'Order').' &raquo; '.lang('simple_order:edit')
 //        );

 //        $skips = array('paket_id', 'user_id', 'nilai', 'jam_mulai', 'jam_selesai');

 //        $this->streams->cp->entry_form('to_user', 'streams', 'edit', $id, true, $extra, $skips);

	// }

	// public function update($id, $update){
	// 	$entry_data = array(
 //        	'status'    => $update
 //   		);
		
	// 	$this->streams->entries->update_entry($id, $entry_data, 'to_user', 'streams');
	// }
}