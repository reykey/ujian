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

        //$this->template->build('index',$data);
        //$data['nilai_lempar']=$this->streams->entries->get_entries($params);


        // $extra = array();
        // $extra['buttons'] = array(
        //     array(
        //         'label' => lang('ujian:mulai'),
        //         'url' => 'admin/ujian/prepare_v'
        //     )
            
        // );

        $this->template->build('index',$data);
        

    }

     public function prepare()
    {
        $extra = array();
        $extra['buttons'] = array(
            array(
                'label' => lang('ujian:back'),
                'url' => 'admin/ujian/group/-entry_id-'
            ),
            array(
                'label' => lang('ujian:mulai'),
                'url' => 'admin/ujian/edit/-entry_id-',
                'confirm' => true
            )
            
        );

        $this->template->build('prepare_v');

    }    

    public function groupSoal($paket_id = false)
    {
        $items['paketSoal'] = $this->streams->entries->get_entry($paket_id, 'paket', 'streams');

        $params = array(
                'stream'        => 'group_soal',
                'namespace'     => 'streams',
                'paginate'      => 'yes',
                'limit'         => 10,
                'page_segment'  => 4,
                'where'         => "paket_id = $paket_id"
                );

        $items['group'] = $this->streams->entries->get_entries($params);

        $this->template->build('tryout', $items);

        /*$extra = array();
        //$extra['title'] = 'lang:ujian:paket';
        
        $this->streams->cp->entries_table('paket', 'streams', 5, 'ujian/index', true, $extra);*/

    }

    public function soal(){
        // $dapat=$this->soal_m->test();
        // $this->template->build('tryout',$dapat);
        $dapat=$this->soal_m->test();
        $this->template->build('tryout',$dapat);

    }



}