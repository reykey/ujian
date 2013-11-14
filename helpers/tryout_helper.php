<?php defined('BASEPATH') or exit('No direct script access allowed');

function diff_times($date1, $date2){
	$data['now'] = time();
	$data['date1_sec'] = is_string($date1)? strtotime($date1) : $date1;
	$data['date2_sec'] = is_string($date2)? strtotime($date2) : $date2;
	$data['mark'] = ($data['date1_sec'] > $data['date2_sec'])? "positive" : "negative";

	$data['compare'] = $date1 - $date2;
	$data['cute'] = cute_date($data['compare']);

	return $data;
}

function cute_date($date){
	$temp = $date;

	$data['months'] = floor($temp / (60*60*24*30));
	$temp = $temp % (60*60*24*30);

	$data['days'] = floor($temp / (60*60*24));
	$temp = $date % (60*60*24);

	$data['hours'] = floor($temp / (60*60));
	$temp = $temp % (60*60);
	
	$data['minutes'] = floor($temp / (60));
	$data['seconds'] = $temp % 60;

	return $data;
}

function set_to_user_expired($uid){
	$ci = &get_instance();
	$data['status_pengerjaan'] = 'expired';
	$ci->db->update('so_to_user', $data, array('id' => $uid));
}