<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ujian extends Public_Controller
{
    // This will set the active section tab
    public $section = 'ujian';

    public function __construct()
    {
        parent::__construct();

        $this->lang->load('ujian');
        $this->load->driver('Streams');
        $this->load->model('soal_m');
        $this->template->append_js('module::jquery-1.9.1.min.js');
        $this->template->append_js('module::jquery.countdown.js');

    }

    
    public function index()
    {
        $params = array(
                'stream'        => 'to_user',
                'namespace'     => 'streams',
                'paginate'      => 'yes',
                'limit'         => 10,
                'page_segment'  => 4
                );

        
        $sama=$this->current_user->id;
        $data['nilai']=$this->soal_m->cekOrder($sama);
        //print_r($nilai);

        $data['pengguna'] = $this->streams->entries->get_entries($params);


        $this->template->build('index',$data);
        

    }

     public function prepare($paket_id = false)
    {
        $items['id'] = $paket_id;
        $this->template->build('prepare_v',$items);

    }    

    public function getMulai($paket_id = false){

        $jam_mulai = new DateTime('now');
 
        $this->soal_m->getMulai(date("Y-m-d H:i:s", $jam_mulai->getTimestamp()), $this->current_user->id, $paket_id);
        $this->session->set_userdata('jam_mulai', $jam_mulai->getTimestamp());
        dump($jam_mulai->getTimestamp());
        dump($this->session->userdata('jam_mulai'));
        redirect('ujian/groupSoal/'.$paket_id);
        
    }
    // public function mulai($paket_id = false){
    //     $items['paketSoal'] = $this->streams->entries->get_entry($paket_id, 'paket', 'streams');

    // }

    public function groupSoal($paket_id = false)
    {
        $items['paketSoal'] = $this->streams->entries->get_entry($paket_id, 'paket', 'streams');
        // dump($items['paketSoal']);

        $params = array(
                'stream'        => 'group_soal',
                'namespace'     => 'streams',
                'where'         => "paket_id = $paket_id"
                );
        $groups = $this->streams->entries->get_entries($params);
        // dump($groups);

        if($groups['total'] > 0){
            foreach ($groups['entries'] as &$group) {
                $paramsoal = array(
                    'stream'        => 'soal',
                    'namespace'     => 'streams',
                    'where'         => "default_to_soal.group_id = {$group['id']}"
                    );
                $soal = $this->streams->entries->get_entries($paramsoal);

                $group['soal'] = $soal['entries'];
            }

        }
        
        $user_id = $this->current_user->id;
        $params = array('stream' => 'jawaban',
                        'namespace' => 'streams',
                        'where' => "default_to_jawaban.paket_id = $paket_id AND default_to_jawaban.user_id = $user_id");

        
        $items['jawaban'] = $this->streams->entries->get_entries($params);
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

            $exist = $this->streams->entries->get_entries(
                array('stream' => 'jawaban',
                    'namespace' => 'streams',
                    'where' => "`soal_id` = {$data['soal_id']} AND `user_id` = {$data['user_id']}"
                    )

                );
            //print_r($data);
            if($exist['total']>0){
                $this->streams->entries->update_entry($exist['entries'][0]['id'], array('jawaban' => $data['jawaban'] ), 'jawaban','streams');
                }else{
                    $this->streams->entries->insert_entry($data,'jawaban','streams');
                }
            
        }
    }


    
    public function getSelesai($paket_id = false){
        //$items['id'] = $paket_id;

        $jam_selesai = new DateTime('now');

        $this->soal_m->getSelesai(date("Y-m-d H:i:s", $jam_selesai->getTimestamp()), $this->current_user->id, $paket_id);
        $this->session->set_userdata('jam_selesai', $jam_selesai->getTimestamp()); 

        dump($jam_selesai->getTimestamp());
        dump($this->session->userdata('jam_selesai'));

        redirect('ujian/hasil/'.$paket_id);
        // $params = array(
        //         'stream' => 'jawaban',
        //         'namespace' => 'streams',
        //         'where' => "" 
        //         );

        // $this->soal_m->selesai($this->current_user->id, $paket_id);
    }

    public function hasil($paket_id = false){
        $total_benar = 0;
        //dump($paket_id);
        $userId = $this->current_user->id;
        //dump($paket_id);
        $soalsoal = $this->soal_m->selesai($userId, $paket_id);
        //dump($soalsoal);
        // dump($total_benar);
        foreach ($soalsoal as $soal) {
            if($soal->jawaban_user == $soal->jawaban){
                $total_benar +=1;
                dump($total_benar);
                // $soal->status_benar = 1;
                // $data['status_benar'] = $soal->status_benar;
                $data['total_benar'] = $total_benar;


            }
        }
        
        
        $nilai['nilai_benar'] = $total_benar * 4;
        //dump($nilai_benar);
        $this->template->build('hasil',$nilai);

        
        



        //$this->soal_m->selesai($this->current_user->id, $paket_id);

    }

    public function soal(){
        // $dapat=$this->soal_m->test();
        // $this->template->build('tryout',$dapat);
        // $dapat=$this->soal_m->test();
        // $this->template->build('tryout',$dapat);

    }



}