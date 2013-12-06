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
		$this->load->helper('tryout');
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

		$order['paket'] = array('all' => 'Semua Paket');
		$order['paket'] += $this->soal_m->get_paket();

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

		if($this->input->post('nama') && trim($this->input->post('nama')) != ''){
			$name = $this->soal_m->search_name($this->input->post('nama'));
			if($name == '') $name = '0';
			$where .= "AND ".SITE_REF."_so_to_user.user_id IN (".$name.") ";
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

	public function detail($id = 0) 
	{
        $data['detail'] = $this->soal_m->get_detail_to_user(array('so_to_user.id' => $id));
        $data['to_user'] = $this->ion_auth->get_user($data['detail']->user_id);

        // dump($data);

        $this->template->build('admin/detail_user', $data);
	}

	public function delete($id = 0)
	{
		$this->db->delete('so_to_user', array('id' => $id));

		redirect(getenv('HTTP_REFERER'));
	}

	function compare(){
		$date1 = strtotime('2013-11-12 12:00:00');
		$date2 = strtotime('2013-12-14 15:34:21');
		dump(diff_times($date2, $date1));
	}

	function check_expired()
    {
        $data['status_pengerjaan'] = 'expired';

        $paket_ids = $this->soal_m->get_paket_expired();
        // dump($paket_ids);

        if(! empty($paket_ids)){	
        	$this->db->where('status_pengerjaan', 'belum')
        			->where_in('paket_id', $paket_ids);
        	$this->db->update('so_to_user', $data);

        }
        redirect(getenv('HTTP_REFERER'));
    }

}