<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Tryout extends Module
{
    public $version = '1.0.0';

    public function info()
    {
        $info = array(
            'name' => array(
                'en' => 'Tryout'
            ),
            'description' => array(
                'en' => 'Modul tryout berisi soal dan jawaban tryout'
            ),
            'frontend' => true,
            'backend' => true,
            // 'menu' => 'content',
            'sections' => array(
                'paket' => array(
                    'name' => 'ujian:paket',
                    'uri' => 'admin/tryout',
                    'shortcuts' => array(
                        'create' => array(
                            'name' => 'ujian:new_paket',
                            'uri' => 'admin/tryout/tambah_paket',
                            'class' => 'add'
                        )
                    )
                ),
                'categories' => array(
                    'name' => 'ujian:categories',
                    'uri' => 'admin/tryout/categories',
                    'shortcuts' => array(
                        'create' => array(
                            'name' => 'ujian:new_category',
                            'uri' => 'admin/tryout/categories/create',
                            'class' => 'add'
                        )
                    )
                ),
                'to_user' => array(
                    'name' => 'Peserta Tryout',
                    'uri' => 'admin/tryout/to_user',
                    'shortcuts' => array(
                        'expired' => array(
                            'name' => 'ujian:check_expired',
                            'uri' => 'admin/tryout/to_user/check_expired'
                        )
                    )
                ),
            )
        );

        if($this->uri->segment(3) == 'group') {
            $info['sections']['paket']['shortcuts'] = array(
                'create' => array(
                            'name' => 'ujian:new_group',
                            'uri' => 'admin/tryout/tambah_group/'.$this->uri->segment(4),
                            'class' => 'add'
                        ),
                'import' => array(
                            'name' => 'ujian:import',
                            'uri' => 'admin/tryout/import/'.$this->uri->segment(4),
                            'class' => 'add'
                        )
            );
        }
        if($this->uri->segment(3) == 'soal') {
            $info['sections']['paket']['shortcuts'] = array(
                'create' => array(
                            'name' => 'ujian:new_soal',
                            'uri' => 'admin/tryout/tambah_soal/'.$this->uri->segment(4).'/'.$this->uri->segment(5),
                            'class' => 'add'
                        )
            );
        }

        return $info;
    }

    public function admin_menu(&$menu)
    {

        // Create our main menu
        add_admin_menu_place('Tryout Online', 2);

        // Assign common items
        $menu['Tryout Online']['Order'] = 'admin/order';
        $menu['Tryout Online']['Tryout']= 'admin/tryout';
    }

    /**
     * Install
     *
     * This function will set up our
     * FAQ/Category streams.
     */
    public function install()
    {
        $this->load->driver('Streams');

        // Get user profile stream
        $profile = $this->streams->streams->get_stream('profiles', 'users');       

        /* PAKET STREAM */
        $namespace = 'streams';
        // Create stream
        $extra = array('title_column' => 'judul', 'view_options' => array("id","judul_paket","tanggal_buka","status_paket"), 'sorting' => 'title', 'menu_path' => '', 'is_hidden' => 'no');
        if( !$this->streams->streams->add_stream('Paket', 'paket', $namespace, 'to_', 'Berisi tentang paket - paket soal TO', $extra) ) return FALSE; 

        // Get paket stream data
        $paket = $this->streams->streams->get_stream('paket', $namespace);

        // Get produk stream data
        $produk = $this->streams->streams->get_stream('product', 'streams');

        // Add fields
        $fields   = array();
        $template = array('namespace' => $namespace, 'assign' => 'paket');

        $fields[] = array('name'=>'Judul', 'slug'=>'judul_paket', 'type'=>'text', 'required' => true, 'unique' => false, 'instructions' => 'Judul Paket Try Out', 'extra'=>array("max_length"=>"50", "default_value"=>""));
        $fields[] = array('name'=>'Deskripsi', 'slug'=>'deskripsi_paket', 'type'=>'textarea', 'required' => true, 'unique' => false, 'instructions' => 'Deskripsi dari Paket Try Out', 'extra'=>array("default_text"=>"", "allow_tags"=>"y", "content_type"=>"text"));
        $fields[] = array('name'=>'Tanggal buka', 'slug'=>'tanggal_buka', 'type'=>'datetime', 'required' => true, 'unique' => false, 'instructions' => '', 'extra'=>array("use_time"=>"yes", "start_date"=>"", "end_date"=>"", "storage"=>"datetime", "input_type"=>"datepicker"));
        $fields[] = array('name'=>'Tanggal Tutup', 'slug'=>'tanggal_tutup', 'type'=>'datetime', 'required' => true, 'unique' => false, 'instructions' => 'Untuk toleransi batas waktu pengerjaan Tryout', 'extra'=>array("use_time"=>"yes", "start_date"=>"", "end_date"=>"", "storage"=>"datetime", "input_type"=>"datepicker"));
        $fields[] = array('name'=>'Status Paket', 'slug'=>'status_paket', 'type'=>'choice', 'required' => true, 'unique' => false, 'instructions' => 'Pilih off jika belum cukup untuk divalidasi, pilih On jika sudah divalidasi', 'extra'=>array("choice_data"=>"on : On\noff : Off", "choice_type"=>"radio", "default_value"=>"off", "min_choices"=>"", "max_choices"=>""));
        $fields[] = array('name'=>'Produk Id', 'slug'=>'produk_id', 'type'=>'relationship', 'required' => true, 'unique' => false, 'instructions' => 'Berisi produk Id dari tabel Order', 'extra'=>array("choose_stream"=>$produk->id, "link_uri"=>null));
        $fields[] = array('name'=>'Alokasi Waktu', 'slug'=>'alokasi_waktu', 'type'=>'integer', 'required' => false, 'unique' => false, 'instructions' => 'Untuk mengalokasikan waktu pengerjaan Tryout', 'extra'=>array("max_length"=>"", "default_value"=>"90"));

        // Combine
        foreach ($fields AS &$field) { $field = array_merge($template, $field); }

        // Add fields to stream
        $this->streams->fields->add_fields($fields);


        // CATEGORIES STREAM
        $namespace = 'streams';
        // Create stream
        $extra = array('title_column' => 'category', 'view_options' => array("category"), 'sorting' => 'title', 'menu_path' => '', 'is_hidden' => 'no');
        if( !$this->streams->streams->add_stream('Categories', 'categories', $namespace, 'to_', '', $extra) ) return FALSE; 

        // Get stream data
        $categories = $this->streams->streams->get_stream('categories', $namespace);

        // Add fields
        $fields   = array();
        $template = array('namespace' => $namespace, 'assign' => 'categories');

        $fields[] = array('name'=>'Category', 'slug'=>'category', 'type'=>'text', 'required' => true, 'unique' => true, 'instructions' => 'Kategori grup tryout', 'extra'=>null);

        // Combine
        foreach ($fields AS &$field) { $field = array_merge($template, $field); }

        // Add fields to stream
        $this->streams->fields->add_fields($fields);


        // GROUP SOAL STREAM
        $namespace = 'streams';
        // Create stream
        $extra = array('title_column' => 'paket_id', 'view_options' => array("judul_grup", "category_id"), 'sorting' => 'custom', 'menu_path' => '', 'is_hidden' => 'no');
        if( !$this->streams->streams->add_stream('Group Soal', 'group_soal', $namespace, 'to_', 'Mengkelompokan soal sesuai paket soal', $extra) ) return FALSE; 

        // Get stream data
        $group_soal = $this->streams->streams->get_stream('group_soal', $namespace);

        // Add fields
        $fields   = array();
        $template = array('namespace' => $namespace, 'assign' => 'group_soal');

        $fields[] = array('name'=>'Judul', 'slug'=>'judul_grup', 'type'=>'text', 'required' => false, 'unique' => false, 'instructions' => 'Judul dari Soal', 'extra'=>array("max_length"=>"50", "default_value"=>""));
        $fields[] = array('name'=>'Instruksi', 'slug'=>'instruksi', 'type'=>'textarea', 'required' => true, 'unique' => false, 'instructions' => 'Instruksi dari grup soal', 'extra'=>array("default_text"=>"", "allow_tags"=>"y", "content_type"=>"text"));
        $fields[] = array('name'=>'Paket id', 'slug'=>'paket_id', 'type'=>'relationship', 'required' => true, 'unique' => false, 'instructions' => 'diambil dari tabel paket', 'extra'=>array("choose_stream"=>$paket->id, "link_uri"=>null));
        $fields[] = array('name'=>'Kategori', 'slug'=>'category_id', 'type'=>'relationship', 'required' => true, 'unique' => false, 'instructions' => 'kategori grup soal', 'extra'=>array("choose_stream"=>$categories->id, "link_uri"=>null));

        // Combine
        foreach ($fields AS &$field) { $field = array_merge($template, $field); }

        // Add fields to stream
        $this->streams->fields->add_fields($fields);


        // SOAL STREAM
        // Create file folder for soal
        $this->load->library('files/files');
        $folder_id = Files::create_folder(0, 'soal');

        $namespace = 'streams';
        // Create stream
        $extra = array('title_column' => 'pertanyaan', 'view_options' => array("pertanyaan","jawaban"), 'sorting' => 'custom', 'menu_path' => '', 'is_hidden' => 'no');
        if( !$this->streams->streams->add_stream('Soal', 'soal', $namespace, 'to_', 'Berisi tentang isi soal dari TO', $extra) ) return FALSE; 

        // Get stream data
        $soal = $this->streams->streams->get_stream('soal', $namespace);

        // Add fields
        $fields   = array();
        $template = array('namespace' => $namespace, 'assign' => 'soal');

        $fields[] = array('name'=>'Group Id', 'slug'=>'group_id', 'type'=>'relationship', 'required' => true, 'unique' => false, 'instructions' => 'Diambil dari tabel group soal', 'extra'=>array("choose_stream" => $group_soal->id, "link_uri"=>null));
        $fields[] = array('name'=>'Pertanyaan', 'slug'=>'pertanyaan', 'type'=>'textarea', 'required' => true, 'unique' => false, 'instructions' => 'Inputkan Pertanyaan', 'extra'=>array("default_text"=>"", "allow_tags"=>"y", "content_type"=>"text"));
        $fields[] = array('name'=>'Gambar Soal', 'slug'=>'gambar_soal', 'type'=>'image', 'required' => false, 'unique' => false, 'instructions' => 'Gambar untuk soal', 'extra'=>array("folder"=>$folder_id, "resize_width"=>"", "resize_height"=>"", "keep_ratio"=>"yes", "allowed_types"=>"jpg|jpeg|png"));
        $fields[] = array('name'=>'Pilihan A', 'slug'=>'pilihan_a', 'type'=>'text', 'required' => true, 'unique' => false, 'instructions' => 'Inputkan pilihan A', 'extra'=>array("max_length"=>"255", "default_value"=>""));
        $fields[] = array('name'=>'Pilihan B', 'slug'=>'pilihan_b', 'type'=>'text', 'required' => true, 'unique' => false, 'instructions' => 'Inputkan pilihan B', 'extra'=>array("max_length"=>"255", "default_value"=>""));
        $fields[] = array('name'=>'Pilihan C', 'slug'=>'pilihan_c', 'type'=>'text', 'required' => true, 'unique' => false, 'instructions' => 'Inputkan pilihan C', 'extra'=>array("max_length"=>"255", "default_value"=>""));
        $fields[] = array('name'=>'Pilihan D', 'slug'=>'pilihan_d', 'type'=>'text', 'required' => true, 'unique' => false, 'instructions' => 'Inputkan pilihan D', 'extra'=>array("max_length"=>"255", "default_value"=>""));
        $fields[] = array('name'=>'Pilihan E', 'slug'=>'pilihan_e', 'type'=>'text', 'required' => false, 'unique' => false, 'instructions' => 'Inputkan Pilihan E (opsional)', 'extra'=>array("max_length"=>"255", "default_value"=>""));
        $fields[] = array('name'=>'Jawaban', 'slug'=>'jawaban', 'type'=>'choice', 'required' => true, 'unique' => false, 'instructions' => 'Inputkan jawaban benar dari pertanyaan', 'extra'=>array("choice_data"=>"A : A\nB : B\nC : C\nD : D\nE : E", "choice_type"=>"radio", "default_value"=>"", "min_choices"=>"", "max_choices"=>""));
        $fields[] = array('name'=>'Pembahasan', 'slug'=>'pembahasan', 'type'=>'textarea', 'required' => false, 'unique' => false, 'instructions' => 'Pembahasan jawaban soal', 'extra'=>array("default_text"=>"", "allow_tags"=>"y", "content_type"=>"html"));

        // Combine
        foreach ($fields AS &$field) { $field = array_merge($template, $field); }

        // Add fields to stream
        $this->streams->fields->add_fields($fields);

        // assign available fields
        $this->streams->fields->assign_field($namespace, 'soal', 'paket_id', array('required' => true, 'unique' => false));


        // JAWABAN STREAM
        $namespace = 'streams';
        // Create stream
        $extra = array('title_column' => '', 'view_options' => array("id","created"), 'sorting' => 'title', 'menu_path' => '', 'is_hidden' => 'no');
        if( !$this->streams->streams->add_stream('Jawaban', 'jawaban', $namespace, 'to_', 'Berisi tentang jawaban dari TO', $extra) ) return FALSE; 

        // Get stream data
        $jawaban = $this->streams->streams->get_stream('jawaban', $namespace);

        // Add fields
        $fields   = array();
        $template = array('namespace' => $namespace, 'assign' => 'jawaban');

        $fields[] = array('name'=>'Soal Id', 'slug'=>'soal_id', 'type'=>'relationship', 'required' => true, 'unique' => false, 'instructions' => 'Isi Soal ID agar sesuai dengan stream soal', 'extra'=>array("choose_stream"=>$soal->id, "link_uri"=>null));

        // Combine
        foreach ($fields AS &$field) { $field = array_merge($template, $field); }

        // Add fields to stream
        $this->streams->fields->add_fields($fields);

        // assign available fields
        $this->streams->fields->assign_field($namespace, 'jawaban', 'user_id', array('required' => true, 'unique' => false));
        $this->streams->fields->assign_field($namespace, 'jawaban', 'paket_id', array('required' => true, 'unique' => false));
        $this->streams->fields->assign_field($namespace, 'jawaban', 'jawaban', array('required' => true, 'unique' => false));


        // TO USER STREAM
        $namespace = 'streams';
        // Create stream
        $extra = array('title_column' => '', 'view_options' => array("id","user_id","status_pengerjaan","nilai","paket_id"), 'sorting' => 'title', 'menu_path' => '', 'is_hidden' => 'no');
        if( !$this->streams->streams->add_stream('Try Out User', 'to_user', $namespace, 'so_', 'Tryout User', $extra) ) return FALSE; 

        // Get stream data
        $to_user = $this->streams->streams->get_stream('to_user', $namespace);

        // Add fields
        $fields   = array();
        $template = array('namespace' => $namespace, 'assign' => 'to_user');

        $fields[] = array('name'=>'Status Pengerjaan', 'slug'=>'status_pengerjaan', 'type'=>'choice', 'required' => true, 'unique' => false, 'instructions' => '', 'extra'=>array("choice_data"=>"sudah : Sudah dikerjakan\nbelum : Belum dikerjakan\nexpired : Expired", "choice_type"=>"dropdown", "default_value"=>"", "min_choices"=>"", "max_choices"=>""));
        $fields[] = array('name'=>'nilai', 'slug'=>'nilai', 'type'=>'decimal', 'required' => false, 'unique' => false, 'instructions' => '', 'extra'=>array("decimal_places"=>"", "default_value"=>"", "min_value"=>"", "max_value"=>""));
        $fields[] = array('name'=>'Jam Mulai', 'slug'=>'jam_mulai', 'type'=>'datetime', 'required' => false, 'unique' => false, 'instructions' => '', 'extra'=>array("use_time"=>"yes", "start_date"=>"", "end_date"=>"", "storage"=>"datetime", "input_type"=>"datepicker"));
        $fields[] = array('name'=>'Jam Selesai', 'slug'=>'jam_selesai', 'type'=>'datetime', 'required' => false, 'unique' => false, 'instructions' => '', 'extra'=>array("use_time"=>"yes", "start_date"=>"", "end_date"=>"", "storage"=>"datetime", "input_type"=>"datepicker"));
        $fields[] = array('name'=>'Status Ujian', 'slug'=>'status_ujian', 'type'=>'choice', 'required' => false, 'unique' => false, 'instructions' => 'Status kelulusan tes', 'extra'=>array("choice_data"=>"belum => Belum\nlulus => Lulus\nmati => Nilai Mati", "choice_type"=>"dropdown", "default_value"=>"belum", "min_choices"=>"", "max_choices"=>""));

        // Combine
        foreach ($fields AS &$field) { $field = array_merge($template, $field); }

        // Add fields to stream
        $this->streams->fields->add_fields($fields);

        // assign available fields
        $this->streams->fields->assign_field($namespace, 'to_user', 'user_id', array('required' => true, 'unique' => false));
        $this->streams->fields->assign_field($namespace, 'to_user', 'paket_id', array('required' => true, 'unique' => false));


        if(is_dir($this->upload_path.'soal') OR @mkdir($this->upload_path.'soal',0777,TRUE))
        {
            return TRUE;
        }
    }

    /**
     * Uninstall
     *
     * Uninstall our module - this should tear down
     * all information associated with it.
     */
    public function uninstall()
    {
        $this->load->driver('Streams');

        // PAKET STREAM
        $namespace = 'streams';
        $this->streams->streams->delete_stream('paket', $namespace);

        $this->streams->fields->delete_field('judul_paket', $namespace);
        $this->streams->fields->delete_field('deskripsi_paket', $namespace);
        $this->streams->fields->delete_field('tanggal_buka', $namespace);
        $this->streams->fields->delete_field('tanggal_tutup', $namespace);
        $this->streams->fields->delete_field('status_paket', $namespace);
        $this->streams->fields->delete_field('produk_id', $namespace);
        $this->streams->fields->delete_field('alokasi_waktu', $namespace);

        // CATEGORIES STREAM
        $namespace = 'streams';
        $this->streams->streams->delete_stream('categories', $namespace);
        $this->streams->fields->delete_field('category', $namespace);

        // GROUP SOAL STREAM
        $namespace = 'streams';
        $this->streams->streams->delete_stream('group_soal', $namespace);

        $this->streams->fields->delete_field('paket_id', $namespace);
        $this->streams->fields->delete_field('category_id', $namespace);
        $this->streams->fields->delete_field('instruksi', $namespace);
        $this->streams->fields->delete_field('judul_grup', $namespace);

        // SOAL STREAM
        $namespace = 'streams';
        $this->streams->streams->delete_stream('soal', $namespace);

        $this->streams->fields->delete_field('group_id', $namespace);
        $this->streams->fields->delete_field('pertanyaan', $namespace);
        $this->streams->fields->delete_field('gambar_soal', $namespace);
        $this->streams->fields->delete_field('pilihan_a', $namespace);
        $this->streams->fields->delete_field('pilihan_b', $namespace);
        $this->streams->fields->delete_field('pilihan_c', $namespace);
        $this->streams->fields->delete_field('pilihan_d', $namespace);
        $this->streams->fields->delete_field('pilihan_e', $namespace);
        $this->streams->fields->delete_field('jawaban', $namespace);
        $this->streams->fields->delete_field('pembahasan', $namespace);

        // JAWABAN STREAM
        $namespace = 'streams';
        $this->streams->streams->delete_stream('jawaban', $namespace);

        $this->streams->fields->delete_field('soal_id', $namespace);

        // TO USER STREAM
        $namespace = 'streams';
        $this->streams->streams->delete_stream('to_user', $namespace);

        $this->streams->fields->delete_field('status_pengerjaan', $namespace);
        $this->streams->fields->delete_field('nilai', $namespace);
        $this->streams->fields->delete_field('jam_mulai', $namespace);
        $this->streams->fields->delete_field('jam_selesai', $namespace);
        $this->streams->fields->delete_field('status_ujian', $namespace);

        return true;
    }

    public function upgrade($old_version)
    {
        return true;
    }

    public function help()
    {
        // Return a string containing help info
        // You could include a file and return it here.
        return "No documentation has been added for this module.<br />Contact the module developer for assistance.";
    }

}