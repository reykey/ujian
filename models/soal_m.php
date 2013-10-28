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

	function cekOrder($id_user = false){
		return $this->db->select('so_to_user.*')
		->from('so_to_user')
		->where('user_id',$id_user and 'status_pengerjaan',"belum")
		->get()->result();
	}

	function getMulai($jam_mulai, $user_id, $paket_id){
		$data = array(
			'jam_mulai'=>$jam_mulai
			); 
		$this->db->where('user_id',$user_id);
		$this->db->where('paket_id',$paket_id);
		$this->db->update('so_to_user',$data);
		return $this->db->affected_rows();
	}

	function getSelesai($jam_selesai, $user_id, $paket_id){
		$data = array(
			'jam_selesai'=>$jam_selesai
			);

		$this->db->where('user_id',$user_id);
		$this->db->where('paket_id',$paket_id);
		$this->db->update('so_to_user',$data);
	}

	function selesai($user_id, $soal_id){
		$this->db->select("to_jawaban.jawaban as jawaban_user, to_soal.jawaban, to_jawaban.soal_id")
		->from('to_soal')
		->join('to_jawaban','to_jawaban.soal_id=soal_id')
		->where('to_jawaban.user_id',$user_id)
		->get()
		->result();

	    	// $data = array(
	    	// 	'user_id' => $user_id,
	    	// 	'paket_id' => $paket_id,
	    	// 	'soal_id' => $soal_id,
	    	// 	'jawaban' => $jawaban
	    	// 	 );
	    	// $this->db->insert('to_jawaban',$jawaban);
	}

	function get_group($where)
	{
		return $this->db->from('to_group_soal')
		->where($where)
		->get()->row_array();

	}

	public function get_paket()
	{

		$data = $this->db->distinct()->select('id, judul')->get('to_paket')->result();
		$paket = array();
		foreach ($data as $value) {
			$paket[$value->id] = $value->judul;
		}
		
		return $paket;

	}
}

