<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin_categories extends Admin_Controller
{
    // This will set the active section tab
    protected $section = 'categories';

    public function __construct()
    {
        parent::__construct();

        $this->lang->load('ujian');
        $this->load->driver('Streams');
    }

    public function index()
    {
        $extra['title'] = lang('ujian:categories');
        
        $extra['buttons'] = array(
            array(
                'label' => lang('global:edit'),
                'url' => 'admin/ujian/categories/edit/-entry_id-'
            ),
            array(
                'label' => lang('global:delete'),
                'url' => 'admin/ujian/categories/delete/-entry_id-',
                'confirm' => true
            )
        );

        $this->streams->cp->entries_table('categories', 'categories', 3, 'admin/ujian/categories/index', true, $extra);
    }

    public function create()
    {
		$extra['title'] = lang('ujian:new');

        $extra = array(
            'return' => 'admin/ujian/categories/index',
            'success_message' => lang('ujian:submit_success'),
            'failure_message' => lang('ujian:submit_failure'),
            'title' => lang('ujian:categories:new')
        );

        $this->streams->cp->entry_form('categories', 'categories', 'new', null, true, $extra);
    }

    public function edit($id = 0)
    {
        $this->template->title(lang('ujian:edit'));

        $extra = array(
            'return' => 'admin/ujian/categories/index',
            'success_message' => lang('ujian:submit_success'),
            'failure_message' => lang('ujian:submit_failure'),
            'title' => lang('ujian:edit')
        );

        $this->streams->cp->entry_form('categories', 'categories', 'edit', $id, true, $extra);
    }

    public function delete($id = 0)
    {
        $this->streams->entries->delete_entry($id, 'categories', 'categories');
        $this->session->set_flashdata('success', lang('ujian:deleted'));
        redirect('admin/ujian/categories/index');
    }

}