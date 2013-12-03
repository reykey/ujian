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
    
        // Set the validation rules
        $this->item_validation_rules = array(
            array(
                'field' => 'file',
                'label' => 'CSV File',
                'rules' => 'trim',
            )
        );
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
        $extra['title'] = lang('ujian:paket');
        $extra['buttons'] = array(
            array(
                'label' => lang('ujian:atur_grup'),
                'url' => 'admin/tryout/group/-entry_id-'
            ),
            array(
                'label' => lang('ujian:edit'),
                'url' => 'admin/tryout/edit/-entry_id-'
            ),
            array(
                'label' => lang('global:delete'),
                'url' => 'admin/tryout/delete/-entry_id-',
                'confirm' => true
            )
            
        );
        $this->streams->cp->entries_table('paket', 'streams', 10, 'admin/tryout/index', true, $extra);
    }

    public function group($paket_id = false, $offset = 0){

        $data['paket'] = $this->streams->entries->get_entry($paket_id, 'paket', 'streams');

        $params = array(
                'stream'        => 'group_soal',
                'namespace'     => 'streams',
                'paginate'      => 'yes',
                'order_by'      => 'ordering_count',
                'sort'          => 'asc',
                'limit'         => 10,
                'pag_segment'   => 5,
                'where'         => "paket_id = $paket_id"
            );

        $data['nomoratas'] = $offset+1;
        $data['entries'] = $this->streams->entries->get_entries($params);
        $data['paket_id'] = $paket_id;

        // $group['datagroup'] = $this->load->view('admin/group_v', array('entries'=>$entries), true);

        $this->template->build('admin/group_v', $data);

    }

    public function soal($paket_id = false, $group_id = false)
    {
        $data['group'] = $this->streams->entries->get_entry($group_id, 'group_soal', 'streams');

        $params = array(
            'stream'        => 'soal',
            'namespace'     => 'streams',
            'paginate'      => 'yes',
            'order_by'      => 'ordering_count',
            'sort'          => 'asc',
            'limit'         => 10,
            'pag_segment'   => 6,
            'where'         => SITE_REF."_to_soal.group_id = $group_id"
            );

        $data['entries'] = $this->streams->entries->get_entries($params);

        $data['paket_id'] = $paket_id;
        $data['group_id'] = $group_id;

        $this->template->build('admin/soal_v', $data);
    }    

    public function tambah_paket(){
        $extra = array(
            'return' => 'admin/tryout/index',
            'success_message' => lang('ujian:submit_success'),
            'failure_message' => lang('ujian:submit_failure'),
            'title' => lang('ujian:new_paket'),
        );

        $this->streams->cp->entry_form('paket', 'streams', 'new', true, $extra);
    
    }

    public function tambah_group($paket_id = false){
        $extra = array(
            'return' => 'admin/tryout/group/'.$paket_id,
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
            'return' => 'admin/tryout/soal/'.$paket_id.'/'.$group_id,
            'success_message' => lang('ujian:submit_success'),
            'failure_message' => lang('ujian:submit_failure'),
            'title' => lang('ujian:new_soal'),
        );
        $hidden = array('group_id', 'paket_id', 'pembahasan');
        $default = array('group_id' => $group_id, 'paket_id' => $paket_id );

        $this->streams->cp->entry_form('soal', 'streams', 'new', null, true, $extra, array(),false, $hidden, $default);

    }

    public function edit($id = 0)
    {
        $extra = array(
            'return' => 'admin/tryout',
            'success_message' => lang('ujian:submit_success'),
            'failure_message' => lang('ujian:submit_failure'),
            'title' => lang('ujian:edit'),
        );

        $this->streams->cp->entry_form('paket', 'streams', 'edit', $id, true, $extra);
    }

    public function edit_group($id = 0, $paket_id = false)
    {
        $extra = array(
            'return' => 'admin/tryout/group/'.$paket_id,
            'success_message' => lang('ujian:submit_success'),
            'failure_message' => lang('ujian:submit_failure'),
            'title' => lang('ujian:edit'),
        );

        $skips = array('paket_id');

        $this->streams->cp->entry_form('group_soal', 'streams', 'edit', $id, true, $extra, $skips);
    }

    public function edit_soal($id = 0, $paket_id = false, $group_id = false)
    {
        $extra = array(
            'return' => 'admin/tryout/soal/'.$paket_id.'/'.$group_id,
            'success_message' => lang('ujian:submit_success'),
            'failure_message' => lang('ujian:submit_failure'),
            'title' => lang('ujian:edit'),
        );
        $skips = array('group_id', 'paket_id', 'pembahasan');

        $this->streams->cp->entry_form('soal', 'streams', 'edit', $id, true, $extra, $skips);
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

        redirect('admin/tryout/');
    }

    public function delete_group($id = 0)
    {
        $this->streams->entries->delete_entry($id, 'group_soal', 'streams');
        $this->session->set_flashdata('success', lang('ujian:deleted'));
 
        redirect(getenv('HTTP_REFERER'));
    }

    public function delete_soal($id = 0)
    {
        $this->streams->entries->delete_entry($id, 'soal', 'streams');
        $this->session->set_flashdata('success', lang('ujian:deleted'));
 
        redirect(getenv('HTTP_REFERER'));
    }

    public function import($paket_id = false)
    {
        $soal = new StdClass();
        
        $this->form_validation->set_rules($this->item_validation_rules);

        // check if the form validation passed
        if($this->form_validation->run())
        {
            $this->load->model('soal_m');

            $config['upload_path'] = './'.UPLOAD_PATH.'soal';
            $config['allowed_types'] = 'csv';
            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('file')){
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('admin/tryout/import');
            } else {
                $file =  $this->upload->data();
                $csv = explode("\n", trim(file_get_contents($file['full_path'])));

                // konversi ke array
                $array_csv = array();
                foreach ($csv as $row) {
                    $array_csv[] = str_getcsv(trim($row));
                }

                // dump($array_csv);

                // simpan ke database
                $group_id = 0; // siapkan var buat nyimpen id grup
                foreach ($array_csv as $row){
                    // dump($row);
                    if(trim($row[0]) != ''){ // kalo bukan baris kosong, maka kerjakan

                        // kalo ini baris kategori, maka kerjakan
                        if($row[0] != '#'){
                            // kalo kategori belum ada
                            if(! $kategori = $this->soal_m->get_category(array('category' => $row[0]))){
                                $kategori_id = $this->streams->entries->insert_entry(array('category' => $row[0]), 'categories', 'streams');
                            } else {
                                $kategori_id = $kategori['id'];
                            }
                        }

                        // kalo ini baris grup soal
                        else if($row[1] != '##') {
                            $group_id = $this->streams->entries->insert_entry(
                                array('judul_grup' => $row[1], 'instruksi' => $row[2], 'paket_id' => $paket_id, 'category_id' => $kategori_id),
                                    'group_soal',
                                    'streams'
                                );
                        }

                        // berarti ini baris soal
                        else {
                            $this->streams->entries->insert_entry(
                                    array(
                                        'group_id' => $group_id, 
                                        'pertanyaan' => $row[2],
                                        'gambar_soal' => '',
                                        'pilihan_a' => !empty($row[3])? $row[3]: 'A',
                                        'pilihan_b' => !empty($row[4])? $row[4]: 'B',
                                        'pilihan_c' => !empty($row[5])? $row[5]: 'C',
                                        'pilihan_d' => !empty($row[6])? $row[6]: 'D',
                                        'jawaban' => strtoupper($row[7]),
                                        'paket_id' => $paket_id,
                                    ),
                                    'soal',
                                    'streams'
                                );
                        }
                    }
                }

                // selesai

                $this->session->set_flashdata('success', lang('ujian:import_success'));
                redirect('admin/tryout/group/'.$paket_id);
            }
        }
        
        $soal->data = new StdClass();
        foreach ($this->item_validation_rules AS $rule)
        {
            $soal->data->{$rule['field']} = $this->input->post($rule['field']);
        }
        
        // Build the view using sample/views/admin/form.php
        $this->template->title($this->module_details['name'], lang('stream_schema:import'))
                        ->build('admin/import', $soal->data);
    }

}