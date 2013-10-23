<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * FAQ Module
 *
 * This is a sample module for PyroCMS
 * that illustrates how to use the streams core API
 * for data management. It is also a fully-functional
 * FAQ module so feel free to use it on your sites.
 *
 * Most of these functions use the Streams API CP driver which
 * is designed to handle repetitive CP tasks, down to even loading the page.
 *
 * @author 		Adam Fairholm - PyroCMS Dev Team
 * @package 	PyroCMS
 * @subpackage 	Streams Sample Module
 */
class Admin extends Admin_Controller
{
    // This will set the active section tab
    protected $section = 'paket';

    public function __construct()
    {
        parent::__construct();

        $this->lang->load('ujian');
        $this->load->driver('Streams');
    }

    /**
     * List all FAQs using Streams CP Driver
     *
     * We are using the Streams API to grab
     * data from the faqs database. It handles
     * pagination as well.
     *
     * @return	void
     */
    public function index()
    {
        $extra = array();
        $extra['title'] = 'lang:ujian:paket';
        $extra['buttons'] = array(
            array(
                'label' => lang('ujian:atur'),
                'url' => 'admin/ujian/group/-entry_id-'
            ),
            array(
                'label' => lang('ujian:edit'),
                'url' => 'admin/ujian/edit/-entry_id-'
            ),
            array(
                'label' => lang('global:delete'),
                'url' => 'admin/ujian/delete/-entry_id-',
                'confirm' => true
            )
            
        );
        $this->streams->cp->entries_table('paket', 'streams', 5, 'admin/ujian/index', true, $extra);
    }

    public function group($paket_id = false){

        $data['paket'] = $this->streams->entries->get_entry($paket_id, 'paket', 'streams');
        

        $params = array(
                'stream'        => 'group_soal',
                'namespace'     => 'streams',
                'paginate'      => 'yes',
                'limit'         => 10,
                'page_segment'  => 4,
                'where'         => "paket_id = $paket_id"
                );

        $data['entries'] = $this->streams->entries->get_entries($params);

        // $group['datagroup'] = $this->load->view('admin/group_v', array('entries'=>$entries), true);

        $this->template->build('admin/group_v', $data);

    }

    public function soal($paket_id = false, $group_id = false){
        
        $data['group'] = $this->streams->entries->get_entry($group_id, 'group_soal', 'streams');

            $params = array(
                'stream'        => 'soal',
                'namespace'     => 'streams',
                'paginate'      => 'yes',
                'limit'         => 10,
                'page_segment'  => 4,
                'where'         => SITE_REF."_to_soal.group_id = $group_id"
                );

            $data['entries'] = $this->streams->entries->get_entries($params);

        // $group['datagroup'] = $this->load->view('admin/group_v', array('entries'=>$entries), true);

            $this->template->build('admin/soal_v', $data);
        //);

        //$this->streams->cp->entry_form('paket', 'streams', 'edit', $id, true, $extra); 
    }    

    public function tambah_paket(){
        $extra = array (
            //'return' => 'admin/paket',
            //'success_message' => lang('faq:submit_success'),
            //'failure_message' => lang('faq:submit_failure'),
            //'title' => 'lang:ujian:atur'
            'return' => 'admin/ujian/',
            'success_message' => lang('ujian:submit_success'),
            'failure_message' => lang('ujian:submit_failure'),
            'title' => 'lang:ujian:new_paket',
         );

        //$this->streams->cp->entry_form('faqs', 'faq', 'new', null, true, $extra);

        $this->streams->cp->entry_form('paket', 'streams', 'new', true, $extra);
    
    }

    public function tambah_group($paket_id = false){
        $extra = array(
            'return' => 'admin/ujian/group/'.$paket_id,
            'success_message' => lang('ujian:submit_success'),
            'failure_message' => lang('ujian:submit_failure'),
            'title' => lang('ujian:new_group'),
        );

        $hidden = array('paket_id');

        $default = array('paket_id' => $paket_id);

        $this->streams->cp->entry_form('group_soal', 'streams', 'new', null, true, $extra, array(), false, $hidden, $default);
    }

    public function tambah_soal($paket_id = false, $group_id = false){
        $extra = array (
            'return' => 'admin/ujian/soal/'.$paket_id.'/'.$group_id,
            'success_message' => lang('ujian:submit_success'),
            'failure_message' => lang('ujian:submit_failure'),
            'title' => lang('ujian:new_soal'),
        );
        $hidden = array('group_id', 'paket_id');
        $default = array('group_id' => $group_id, 'paket_id' => $paket_id );

        $this->streams->cp->entry_form('soal', 'streams', 'new', null, true, $extra, array(),false, $hidden, $default);

    }

    public function edit($id = 0)
    {
        $extra = array(
            'return' => 'admin/ujian',
            'success_message' => lang('ujian:submit_success'),
            'failure_message' => lang('ujian:submit_failure'),
            'title' => lang('ujian:edit'),
        );

        $this->streams->cp->entry_form('paket', 'streams', 'edit', $id, true, $extra);
    }

    public function edit_group($id = 0)
    {
        $extra = array(
            'return' => 'admin/ujian/group',
            'success_message' => lang('ujian:submit_success'),
            'failure_message' => lang('ujian:submit_failure'),
            'title' => lang('ujian:edit'),
        );

        $this->streams->cp->entry_form('group_soal', 'streams', 'edit', $id, true, $extra);
    }

    public function edit_soal($id = 0)
    {
        $extra = array(
            'return' => 'admin/ujian/soal',
            'success_message' => lang('ujian:submit_success'),
            'failure_message' => lang('ujian:submit_failure'),
            'title' => lang('ujian:edit'),
        );

        $this->streams->cp->entry_form('soal', 'streams', 'edit', $id, true, $extra);
    }


    public function delete($id = 0)
    {
        $this->streams->entries->delete_entry($id, 'paket', 'streams');
        $this->session->set_flashdata('success', lang('ujian:deleted'));
        
        if($this->uri->segment(3) == $group_id ) {
            $this->streams->entries->delete_entry($id, 'group_soal', 'streams');
        }

        if($this->uri->segment(4) == $soal_id ) {
            $this->streams->entries->delete_entry($id, 'soal', 'streams');
        }

        redirect('admin/ujian/');
    }

    public function delete_group($id = 0)
    {
        $this->streams->entries->delete_entry($id, 'group_soal', 'streams');
        $this->session->set_flashdata('success', lang('ujian:deleted'));
 
        redirect('admin/ujian/group/');
    }

    public function delete_soal($id = 0)
    {
        $this->streams->entries->delete_entry($id, 'soal', 'streams');
        $this->session->set_flashdata('success', lang('ujian:deleted'));
 
        redirect('admin/ujian/soal/');
    }

}