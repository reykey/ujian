<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Tryout extends Public_Controller
{
    // This will set the active section tab
    public $section = 'ujian';

    public function __construct()
    {
        parent::__construct();

        if(! $this->current_user->id) redirect('users/login');

        $this->lang->load('ujian');
        $this->load->driver('Streams');
        $this->load->model('soal_m');
        $this->load->helper('tryout');
        $this->template->append_css('module::tryout.css');
        $this->template->append_js('module::jquery-1.9.1.min.js');
        $this->template->append_js('module::jquery.countdown.js');
        // $this->template->append_js('module::bootstrap-paginator.min.js');
    }

    
    public function index()
    {
        if($this->session->userdata('jam_selesai'))
            redirect('tryout/mulai/'.$this->session->userdata('paket_id'));

        $params = array(
                'stream'    => 'to_user',
                'namespace' => 'streams',
                'order_by'  => 'paket_id',
                'sort'      => 'asc',
                'where'     => SITE_REF.'_so_to_user.user_id = '.$this->current_user->id
                );
        
        $data['paket'] = $this->streams->entries->get_entries($params);

        $user_id = $this->current_user->id;
        $data['paket_user']=$this->soal_m->get_user_paket($user_id);

        $this->template->build('index', $data);
    }

    public function prepare($paket_id = false)
    {
        // cek apakah user benar sudah punya akses ke tryout ini
        $this->cek_akses_to($paket_id, $this->current_user->id) or die('Maaf Anda tidak punya akses atau sudah menyelesaikan tryout ini.');

        if($this->session->userdata('jam_selesai'))
            redirect('tryout/mulai/'.$this->session->userdata('paket_id'));

        $items['id'] = $paket_id;
        $this->template->build('prepare_v',$items);
    }    

    public function inisiasi($paket_id = false)
    {
        // cek apakah user benar sudah punya akses ke tryout ini
        $this->cek_akses_to($paket_id, $this->current_user->id) or die('Maaf Anda tidak punya akses atau sudah menyelesaikan tryout ini.');

        if($this->session->userdata('jam_selesai'))
            redirect('tryout/mulai/'.$this->session->userdata('paket_id'));
        
        // cek apakah 
        $paket = $this->streams->entries->get_entry($paket_id, 'paket', 'streams');
        // dump($paket);

        $jam_mulai = new DateTime('now');
        $jam_selesai = new DateTime($jam_mulai->format('Y-m-d H:i:s'));
        $jam_selesai->add(new DateInterval('P'.$paket->alokasi_waktu.'M'));
 
        $this->soal_m->inisiasi(date("Y-m-d H:i:s", $jam_mulai->getTimestamp()), $this->current_user->id, $paket_id);
        $this->session->set_userdata('jam_mulai', $jam_mulai->getTimestamp());
        $this->session->set_userdata('jam_selesai', $jam_selesai->getTimestamp());
        $this->session->set_userdata('paket_id', $paket_id);
        // dump($jam_mulai->getTimestamp());
        // dump($this->session->userdata('jam_mulai'));
        redirect('tryout/mulai/'.$paket_id);
        
    }

    public function cek_akses_to($paket_id, $user_id){
        $data = $this->soal_m->get_detail_to_user(array('so_to_user.paket_id' => $paket_id, 'so_to_user.user_id' => $user_id));

        // kalo waktu sekarang lebih dari tanggal buka
        $sudah_buka = diff_times(date("Y-m-d H:i:s"), $data->tanggal_buka);

        // kalo waktu sekarang kurang dari tanggal tutup
        $sudah_tutup = diff_times(date("Y-m-d H:i:s"), $data->tanggal_tutup);

        if($data->status_pengerjaan == 'belum' && $sudah_buka['mark'] == 'positive' && $sudah_tutup['mark'] == 'negative')
            return true;

        return false;
    }

    public function mulai($paket_id = false)
    {
        $items['perpage'] = $this->settings->records_per_page;

        $items['paketSoal'] = $this->streams->entries->get_entry($paket_id, 'paket', 'streams');
        // dump($items['paketSoal']);

        $params = array(
                'stream'        => 'group_soal',
                'namespace'     => 'streams',
                'order_by'      => 'ordering_count',
                'sort'          => 'asc',
                'where'         => "paket_id = $paket_id"
                );
        $groups = $this->streams->entries->get_entries($params);
        // dump($groups);

        if($groups['total'] > 0){
            foreach ($groups['entries'] as &$group) {
                $paramsoal = array(
                    'stream'        => 'soal',
                    'namespace'     => 'streams',
                    'order_by'      => 'ordering_count',
                    'sort'          => 'asc',
                    'where'         => SITE_REF."_to_soal.group_id = {$group['id']}"
                    );
                $soal = $this->streams->entries->get_entries($paramsoal);

                $group['soal'] = $soal['entries'];
            }
        }
        
        $user_id = $this->current_user->id;
        $items['jawaban'] = $this->soal_m->get_jawaban_user($paket_id, $user_id);
        //dump($jawaban);
        
        $items['group'] = $groups['entries'];

        $this->template
            ->set('id', $paket_id)
            ->build('tryout', $items);
    }

    function simpan_jawaban(){
        if('$_POST'){
            $data = array(
                'jawaban' => strtoupper(substr($this->input->post('jawaban'), 8)),
                'soal_id' => substr($this->input->post('soal'), 8),
                'user_id' => $this->current_user->id,
                'paket_id' => $this->input->post('paket')
                );
            //
            // dump($data);

            $exist = $this->streams->entries->get_entries(
                array('stream' => 'jawaban',
                    'namespace' => 'streams',
                    'where' => "`soal_id` = {$data['soal_id']} AND `user_id` = {$data['user_id']}"
                    )

                );
            // dump($exist);
            if($exist['total']>0){
                $this->streams->entries->update_entry($exist['entries'][0]['id'], array('jawaban' => $data['jawaban'] ), 'jawaban', 'streams');
            }else{
                $this->streams->entries->insert_entry($data,'jawaban','streams');
            }
            
        }
    }
    
    public function selesai($paket_id = false){
        //$items['id'] = $paket_id;

        $jam_selesai = new DateTime('now');

        $this->soal_m->set_selesai(date("Y-m-d H:i:s", $jam_selesai->getTimestamp()), $this->current_user->id, $paket_id);
        $this->session->set_userdata('jam_selesai', $jam_selesai->getTimestamp()); 

        // dump($jam_selesai->getTimestamp());
        // dump($this->session->userdata('jam_selesai'));

        redirect('tryout/hasil/'.$paket_id);
        // $params = array(
        //         'stream' => 'jawaban',
        //         'namespace' => 'streams',
        //         'where' => "" 
        //         );

        // $this->soal_m->selesai($this->current_user->id, $paket_id);
    }

    public function hasil($paket_id = false){
        $total_benar = 0;
        $total_salah = 0;
        $total_kosong = 0;

        $userId = $this->current_user->id;

        $paket = $this->streams->entries->get_entry($paket_id, 'paket', 'streams');
        $soalsoal = $this->soal_m->selesai($userId, $paket_id);

        if(strpos($paket->judul_paket, 'STIS')){
            $pengali = 3;
        } else {
            $pengali = 4;
        }

        $result = array();
        foreach ($soalsoal as $soal) {
            if($soal->jawaban_user == $soal->jawaban){
                $total_benar +=1;
                $data['total_benar'] = $total_benar;
            }else{
                $total_salah +=1;
                $data['total_salah'] = $total_salah;
            }

            $cat = $soal->category;
            if (isset($result[$cat])) {
                if($soal->jawaban_user == $soal->jawaban){
                    $result[$cat]['benar'] += 1;
                    $result[$cat]['nilai'] += $pengali;
                }else{
                    $result[$cat]['salah'] += 1;
                    $result[$cat]['nilai'] -= 1;
                }

                $result[$cat]['data'][] = $soal;

            } else {
                $result[$cat]['benar'] = 0;
                $result[$cat]['salah'] = 0;
                $result[$cat]['nilai'] = 0;

                if($soal->jawaban_user == $soal->jawaban){
                    $result[$cat]['benar'] += 1;
                    $result[$cat]['nilai'] += $pengali;
                }else{
                    $result[$cat]['salah'] += 1;
                    $result[$cat]['nilai'] -= 1;
                }
                
                $result[$cat]['data'] = array($soal);
            }

            $result[$cat]['kosong'] = $this->hitung_jumlah_soal($paket_id, $soal->category_id) - $result[$cat]['benar'] - $result[$cat]['salah'];
            // echo $this->hitung_jumlah_soal($paket_id, $soal->category_id);
        }

        $status_ujian = 'lulus';

        foreach ($result as $namakategori => $kategori) {
            $total_kosong += $kategori['kosong'];

            // cek nilai mati pada tryout stan
            if(strpos($paket->judul_paket, 'STAN')){
                // mati jika soal benar TPA < 40
                if(stripos($namakategori, 'AKADEMIK')){
                    if($kategori['benar'] < 40)
                        $status_ujian = 'mati'; // dead
                }
                // mati jika soal benar B Inggris < 20
                if(stripos($namakategori, 'INGGRIS')){
                    if($kategori['benar'] < 20)
                        $status_ujian = 'mati'; // dead
                }
            }
        }
        
        $nilai['result'] = $result;
        $nilai['total_benar'] = $total_benar;
        $nilai['total_salah'] = $total_salah;
        $nilai['total_kosong'] = $total_kosong;
        $nilai['status_ujian'] =$status_ujian;

        $nilai['paket'] = $paket;

        $nilai['total'] = ($total_benar*$pengali) + ($total_salah*(-1));

        // dump($result);
        
        $this->reset_session();

        $this->soal_m->simpan_nilai($paket_id, $userId, $nilai['total'], $nilai['status_ujian']);

        $this->template->build('hasil',$nilai);

        //$this->soal_m->selesai($this->current_user->id, $paket_id);
    }

    public function soal(){
        // $dapat=$this->soal_m->test();
        // $this->template->build('tryout',$dapat);
        // $dapat=$this->soal_m->test();
        // $this->template->build('tryout',$dapat);

    }

    function reset_session()
    {
        if($this->session->userdata('jam_mulai')) $this->session->unset_userdata('jam_mulai');
        if($this->session->userdata('jam_selesai')) $this->session->unset_userdata('jam_selesai');
        if($this->session->userdata('paket_id')) $this->session->unset_userdata('paket_id');

    }

    function hitung_jumlah_soal($paket_id, $cat_id = false)
    {
        if(! $cat_id){
            $cat = $this->soal_m->get_used_category($paket_id);

            $jml = array();
            foreach ($cat as $value) {
                $jml[] = $value + array('count' => $this->soal_m->count_soal($paket_id, $value['id']));
            }
        } else {
            $jml = $this->soal_m->count_soal($paket_id, $cat_id);
        }


        return $jml;
    }

}