<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Soal_m extends MY_Model{


	function __construct()
	{
		parent::__construct();
	}
	
	function test($paket_id = false)
	{
		$group = $this->db->select('to_group_soal.*')
		->from('to_group_soal')
		->where('paket_id',$paket_id)
		->get()->result();
		
		foreach ($group as $row)
			$temp = $this->db->select('to_soal.*')
		->from('to_soal')
		->where('group_id',$row->id)
		->get()->result();
		$row['soal'] = $temp;

		return $row;
	}

	function get_user_paket($id_user = false){
		return $this->db->select('so_to_user.*')
		->from('so_to_user')
		->where('user_id',$id_user and 'status_pengerjaan',"belum")
		->get()->row();
	}

	function inisiasi($jam_mulai, $user_id, $paket_id){
		$data = array(
			'jam_mulai'=>$jam_mulai
			); 
		$this->db->where('user_id',$user_id);
		$this->db->where('paket_id',$paket_id);
		$this->db->update('so_to_user',$data);
		return $this->db->affected_rows();
	}

	function set_selesai($jam_selesai, $user_id, $paket_id){
		$data = array(
			'jam_selesai' => $jam_selesai,
			'status_pengerjaan' => 'sudah'
			);

		$this->db->where('user_id',$user_id);
		$this->db->where('paket_id',$paket_id);
		$this->db->update('so_to_user',$data);
	}

	function selesai($user_id, $paket_id){
		
		return $this->db->select('to_jawaban.jawaban as jawaban_user, to_categories.category, to_group_soal.category_id, to_jawaban.soal_id, to_jawaban.paket_id, to_soal.jawaban, to_soal.id')
		->from('to_soal')
		->join('to_jawaban','to_jawaban.soal_id = to_soal.id')
		->join('to_group_soal','to_group_soal.id = to_soal.group_id')
		->join('to_categories','to_categories.id = to_group_soal.category_id')
		->where('to_jawaban.user_id',$user_id)
		->where('to_jawaban.paket_id',$paket_id)
		->order_by('to_categories.ordering_count')
		->get()
		->result();
	}

	function count_soal($paket_id, $category_id = false)
	{
		$this->db->from('to_soal s')
				->join('to_group_soal g', 'g.id = s.group_id')
				->where('g.paket_id', $paket_id);

		if($category_id)
			$this->db->where('g.category_id', $category_id);

		return $this->db->count_all_results();
	}

	function simpan_nilai($paket_id, $uid, $nilai, $status)
	{
		$this->db->where('paket_id', $paket_id)
				->where('user_id', $uid)
				->update('so_to_user', array('nilai'=>$nilai, 'status_ujian'=>$status));

		return $this->db->affected_rows();
	}

	function get_group($where)
	{
		return $this->db->from('to_group_soal')
		->where($where)
		->get()->row_array();

	}

	function get_category($where)
	{
		return $this->db->from('to_categories')
			->where($where)
			->get()->row_array();
	}

	function get_used_category($paket_id)
	{
		return $this->db->select('c.id, category')
			->from('to_group_soal g')
			->join('to_categories c', 'c.id = g.category_id')
			->where('paket_id', $paket_id)
			->group_by('category_id')
			->get()->result_array();

	}

	public function get_paket()
	{

		$data = $this->db->distinct()->select('id, judul_paket')->get('to_paket')->result();
		$paket = array();
		foreach ($data as $value) {
			$paket[$value->id] = $value->judul_paket;
		}
		
		return $paket;

	}

	public function get_jawaban_user($paket_id, $user_id)
	{
		return $this->db->select('soal_id, jawaban')
				->from('to_jawaban')
				->where('paket_id', $paket_id)
				->where('user_id', $user_id)
				->get()->result_array();
	}

	function get_detail_to_user($where)
	{
		return $this->db->from('so_to_user')
				->join('to_paket', 'to_paket.id = so_to_user.paket_id')
				->join('so_to_order', 'so_to_order.user_id = so_to_user.user_id')
				->where($where)
				->get()->row();
	}

	function get_paket_expired()
	{
		$tgl = $this->db->where('tanggal_tutup <', date("Y-m-d H:i:s"))
						->get('to_paket')->result();

		if($tgl){
			$paket_ids = array();	
			foreach ($tgl as $value) {
				$paket_ids[] = $value->id;
			}
			return $paket_ids;
		}

		return false;
	}

	public function search_name($name){

		$data = $this->db->select('user_id')->from('profiles')->like('display_name', $name, 'both')->get()->result();
		$result = '';
		foreach ($data as $hasil){
			$result .= $hasil->user_id.',';
		}
		return substr($result, 0, strlen($result) - 1);

	}
}

