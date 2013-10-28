<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Ujian extends Module
{
    public $version = '1.0.0';

    public function info()
    {
        $info = array(
            'name' => array(
                'en' => 'Ujian'
            ),
            'description' => array(
                'en' => 'Modul ujian berisi soal dan jawaban TO'
            ),
            'frontend' => true,
            'backend' => true,
            'menu' => 'content',
            'sections' => array(
                'paket' => array(
                    'name' => 'ujian:paket',
                    'uri' => 'admin/ujian',
                    'shortcuts' => array(
                        'create' => array(
                            'name' => 'ujian:new_paket',
                            'uri' => 'admin/ujian/tambah_paket',
                            'class' => 'add'
                        )
                    )
                ),
                'to_user' => array(
                    'name' => 'Peserta Tryout',
                    'uri' => 'admin/ujian/to_user',
                ),
            )
        );

        if($this->uri->segment(3) == 'group') {
            $info['sections']['paket']['shortcuts'] = array(
                'create' => array(
                            'name' => 'ujian:new_group',
                            'uri' => 'admin/ujian/tambah_group/'.$this->uri->segment(4),
                            'class' => 'add'
                        ),
                'import' => array(
                            'name' => 'ujian:import',
                            'uri' => 'admin/ujian/import/'.$this->uri->segment(4),
                            'class' => 'add'
                        )
            );
        }
        if($this->uri->segment(3) == 'soal') {
            $info['sections']['paket']['shortcuts'] = array(
                'create' => array(
                            'name' => 'ujian:new_soal',
                            'uri' => 'admin/ujian/tambah_soal/'.$this->uri->segment(4).'/'.$this->uri->segment(5),
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
        $menu['Tryout Online']['Order'] = 'admin/so';
        $menu['Tryout Online']['Tryout']= 'admin/ujian';
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
       

        /* PAKET STREAM */
        $namespace = 'paket';
        // Create stream
        $extra = array('title_column' => 'judul', 'view_options' => array("id","judul","tanggal_buka","status_paket"), 'sorting' => 'title', 'menu_path' => '', 'is_hidden' => 'no');
        if( !$this->streams->streams->add_stream('Paket', 'paket', $namespace, 'to_', 'Berisi tentang paket - paket soal TO', $extra) ) return FALSE; 

        // Get stream data
        $paket = $this->streams->streams->get_stream('paket', $namespace);

        // Add fields
        $fields   = array();
        $template = array('namespace' => $namespace, 'assign' => 'paket');

        $fields[] = array('name'=>'Judul', 'slug'=>'judul', 'type'=>'text', 'required' => 'yes', 'unique' => 'no', 'instructions' => 'Judul Paket Try Out', 'extra'=>array("max_length"=>"50", "default_value"=>""));
        $fields[] = array('name'=>'Deskripsi', 'slug'=>'deskripsi', 'type'=>'textarea', 'required' => 'yes', 'unique' => 'no', 'instructions' => 'Deskripsi dari Paket Try Out', 'extra'=>array("default_text"=>"", "allow_tags"=>"y", "content_type"=>"text"));
        $fields[] = array('name'=>'Tanggal buka', 'slug'=>'tanggal_buka', 'type'=>'datetime', 'required' => 'yes', 'unique' => 'no', 'instructions' => '', 'extra'=>array("use_time"=>"yes", "start_date"=>"", "end_date"=>"", "storage"=>"datetime", "input_type"=>"datepicker"));
        $fields[] = array('name'=>'Tanggal Tutup', 'slug'=>'tanggal_tutup', 'type'=>'datetime', 'required' => 'yes', 'unique' => 'no', 'instructions' => 'Untuk toleransi batas waktu pengerjaan Tryout', 'extra'=>array("use_time"=>"yes", "start_date"=>"", "end_date"=>"", "storage"=>"datetime", "input_type"=>"datepicker"));
        $fields[] = array('name'=>'Status Paket', 'slug'=>'status_paket', 'type'=>'choice', 'required' => 'yes', 'unique' => 'no', 'instructions' => 'Pilih off jika belum cukup untuk divalidasi, pilih On jika sudah divalidasi', 'extra'=>array("choice_data"=>"on => On\r\noff => Off", "choice_type"=>"radio", "default_value"=>"off", "min_choices"=>"", "max_choices"=>""));
        $fields[] = array('name'=>'Produk Id', 'slug'=>'produk_id', 'type'=>'relationship', 'required' => 'yes', 'unique' => 'no', 'instructions' => 'Berisi produk Id dari tabel Order', 'extra'=>array("choose_stream"=>"31", "link_uri"=>null));
        $fields[] = array('name'=>'Alokasi Waktu', 'slug'=>'alokasi_waktu', 'type'=>'integer', 'required' => 'no', 'unique' => 'no', 'instructions' => 'Untuk mengalokasikan waktu pengerjaan Tryout', 'extra'=>array("max_length"=>"", "default_value"=>"90"));

        // Combine
        foreach ($fields AS &$field) { $field = array_merge($template, $field); }

        // Add fields to stream
        $this->streams->fields->add_fields($fields);


        // GROUP SOAL STREAM
        $namespace = 'group_soal';
        // Create stream
        $extra = array('title_column' => 'paket_id', 'view_options' => array("id","created"), 'sorting' => 'title', 'menu_path' => '', 'is_hidden' => 'no');
        if( !$this->streams->streams->add_stream('Group Soal', 'group_soal', $namespace, 'to_', 'Mengkelompokan soal sesuai paket soal', $extra) ) return FALSE; 

        // Get stream data
        $group_soal = $this->streams->streams->get_stream('group_soal', $namespace);

        // Add fields
        $fields   = array();
        $template = array('namespace' => $namespace, 'assign' => 'group_soal');

        $fields[] = array('name'=>'Paket id', 'slug'=>'paket_id', 'type'=>'relationship', 'required' => 'yes', 'unique' => 'no', 'instructions' => 'diambil dari tabel paket', 'extra'=>array("choose_stream"=>"27", "link_uri"=>null));
        $fields[] = array('name'=>'Instruksi', 'slug'=>'instruksi', 'type'=>'textarea', 'required' => 'yes', 'unique' => 'no', 'instructions' => 'Instruksi dari grup soal', 'extra'=>array("default_text"=>"", "allow_tags"=>"y", "content_type"=>"text"));
        $fields[] = array('name'=>'Judul', 'slug'=>'judul', 'type'=>'text', 'required' => 'no', 'unique' => 'no', 'instructions' => 'Judul dari Soal', 'extra'=>array("max_length"=>"50", "default_value"=>""));

        // Combine
        foreach ($fields AS &$field) { $field = array_merge($template, $field); }

        // Add fields to stream
        $this->streams->fields->add_fields($fields);


        // SOAL STREAM
        $namespace = 'soal';
        // Create stream
        $extra = array('title_column' => 'pertanyaan', 'view_options' => array("id","created"), 'sorting' => 'title', 'menu_path' => '', 'is_hidden' => 'no');
        if( !$this->streams->streams->add_stream('Soal', 'soal', $namespace, 'to_', 'Berisi tentang isi soal dari TO', $extra) ) return FALSE; 

        // Get stream data
        $soal = $this->streams->streams->get_stream('soal', $namespace);

        // Add fields
        $fields   = array();
        $template = array('namespace' => $namespace, 'assign' => 'soal');

        $fields[] = array('name'=>'Group Id', 'slug'=>'group_id', 'type'=>'relationship', 'required' => 'yes', 'unique' => 'no', 'instructions' => 'Diambil dari tabel group soal', 'extra'=>array("choose_stream"=>"8", "link_uri"=>null));
        $fields[] = array('name'=>'Pertanyaan', 'slug'=>'pertanyaan', 'type'=>'textarea', 'required' => 'yes', 'unique' => 'no', 'instructions' => 'Inputkan Pertanyaan', 'extra'=>array("default_text"=>"", "allow_tags"=>"y", "content_type"=>"text"));
        $fields[] = array('name'=>'Pilihan A', 'slug'=>'pilihan_a', 'type'=>'text', 'required' => 'yes', 'unique' => 'no', 'instructions' => 'Inputkan pilihan A', 'extra'=>array("max_length"=>"50", "default_value"=>""));
        $fields[] = array('name'=>'Pilihan B', 'slug'=>'pilihan_b', 'type'=>'text', 'required' => 'yes', 'unique' => 'no', 'instructions' => 'Inputkan pilihan B', 'extra'=>array("max_length"=>"50", "default_value"=>""));
        $fields[] = array('name'=>'Pilihan C', 'slug'=>'pilihan_c', 'type'=>'text', 'required' => 'yes', 'unique' => 'no', 'instructions' => 'Inputkan pilihan C', 'extra'=>array("max_length"=>"50", "default_value"=>""));
        $fields[] = array('name'=>'Pilihan D', 'slug'=>'pilihan_d', 'type'=>'text', 'required' => 'yes', 'unique' => 'no', 'instructions' => 'Inputkan pilihan D', 'extra'=>array("max_length"=>"50", "default_value"=>""));
        $fields[] = array('name'=>'Jawaban', 'slug'=>'jawaban', 'type'=>'choice', 'required' => 'yes', 'unique' => 'no', 'instructions' => 'Inputkan jawaban benar dari pertanyaan', 'extra'=>array("choice_data"=>"A => A\r\nB => B\r\nC => C\r\nD => D", "choice_type"=>"radio", "default_value"=>"", "min_choices"=>"", "max_choices"=>""));
        $fields[] = array('name'=>'Paket id', 'slug'=>'paket_id', 'type'=>'relationship', 'required' => 'yes', 'unique' => 'no', 'instructions' => '', 'extra'=>array("choose_stream"=>"27", "link_uri"=>null));

        // Combine
        foreach ($fields AS &$field) { $field = array_merge($template, $field); }

        // Add fields to stream
        $this->streams->fields->add_fields($fields);


        // JAWABAN STREAM
        $namespace = 'jawaban';
        // Create stream
        $extra = array('title_column' => '', 'view_options' => array("id","created"), 'sorting' => 'title', 'menu_path' => '', 'is_hidden' => 'no');
        if( !$this->streams->streams->add_stream('Jawaban', 'jawaban', $namespace, 'to_', 'Berisi tentang jawaban dari TO', $extra) ) return FALSE; 

        // Get stream data
        $jawaban = $this->streams->streams->get_stream('jawaban', $namespace);

        // Add fields
        $fields   = array();
        $template = array('namespace' => $namespace, 'assign' => 'jawaban');

        $fields[] = array('name'=>'user', 'slug'=>'user_id', 'type'=>'relationship', 'required' => 'yes', 'unique' => 'no', 'instructions' => 'Untuk mengetahui siapa yang mengakses tryout online', 'extra'=>array("choose_stream"=>"3", "link_uri"=>null));
        $fields[] = array('name'=>'Paket id', 'slug'=>'paket_id', 'type'=>'relationship', 'required' => 'yes', 'unique' => 'no', 'instructions' => '', 'extra'=>array("choose_stream"=>"27", "link_uri"=>null));
        $fields[] = array('name'=>'Soal Id', 'slug'=>'soal_id', 'type'=>'relationship', 'required' => 'yes', 'unique' => 'no', 'instructions' => 'Isi Soal ID agar sesuai dengan stream soal', 'extra'=>array("choose_stream"=>"9", "link_uri"=>null));
        $fields[] = array('name'=>'Jawaban', 'slug'=>'jawaban', 'type'=>'choice', 'required' => 'no', 'unique' => 'no', 'instructions' => 'Mengisi jawaban yang diinputkan oleh user', 'extra'=>array("choice_data"=>"A => A\r\nB => B\r\nC => C\r\nD => D", "choice_type"=>"radio", "default_value"=>"", "min_choices"=>"", "max_choices"=>""));

        // Combine
        foreach ($fields AS &$field) { $field = array_merge($template, $field); }

        // Add fields to stream
        $this->streams->fields->add_fields($fields);


        // TO USER STREAM
        $namespace = 'to_user';
        // Create stream
        $extra = array('title_column' => '', 'view_options' => array("id","user_id","status_pengerjaan","nilai","paket_id"), 'sorting' => 'title', 'menu_path' => '', 'is_hidden' => 'no');
        if( !$this->streams->streams->add_stream('Try Out User', 'to_user', $namespace, 'so_', 'Tryout User', $extra) ) return FALSE; 

        // Get stream data
        $to_user = $this->streams->streams->get_stream('to_user', $namespace);

        // Add fields
        $fields   = array();
        $template = array('namespace' => $namespace, 'assign' => 'to_user');

        $fields[] = array('name'=>'user', 'slug'=>'user_id', 'type'=>'relationship', 'required' => 'yes', 'unique' => 'no', 'instructions' => '', 'extra'=>array("choose_stream"=>"3", "link_uri"=>null));
        $fields[] = array('name'=>'Status Pengerjaan', 'slug'=>'status_pengerjaan', 'type'=>'choice', 'required' => 'yes', 'unique' => 'no', 'instructions' => '', 'extra'=>array("choice_data"=>"sudah => Sudah dikerjakan\r\nbelum => Belum dikerjakan\r\nexpired => Expired", "choice_type"=>"dropdown", "default_value"=>"", "min_choices"=>"", "max_choices"=>""));
        $fields[] = array('name'=>'nilai', 'slug'=>'nilai', 'type'=>'decimal', 'required' => 'no', 'unique' => 'no', 'instructions' => '', 'extra'=>array("decimal_places"=>"", "default_value"=>"", "min_value"=>"", "max_value"=>""));
        $fields[] = array('name'=>'Paket id', 'slug'=>'paket_id', 'type'=>'relationship', 'required' => 'yes', 'unique' => 'no', 'instructions' => '', 'extra'=>array("choose_stream"=>"27", "link_uri"=>null));
        $fields[] = array('name'=>'Jam Mulai', 'slug'=>'jam_mulai', 'type'=>'datetime', 'required' => 'no', 'unique' => 'no', 'instructions' => '', 'extra'=>array("use_time"=>"yes", "start_date"=>"", "end_date"=>"", "storage"=>"datetime", "input_type"=>"datepicker"));
        $fields[] = array('name'=>'Jam Selesai', 'slug'=>'jam_selesai', 'type'=>'datetime', 'required' => 'no', 'unique' => 'no', 'instructions' => '', 'extra'=>array("use_time"=>"yes", "start_date"=>"", "end_date"=>"", "storage"=>"datetime", "input_type"=>"datepicker"));

        // Combine
        foreach ($fields AS &$field) { $field = array_merge($template, $field); }

        // Add fields to stream
        $this->streams->fields->add_fields($fields);


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
        $namespace = 'paket';
        $this->streams->streams->delete_stream('paket', $namespace);

        $this->streams->fields->delete_field('judul', $namespace);
        $this->streams->fields->delete_field('deskripsi', $namespace);
        $this->streams->fields->delete_field('tanggal_buka', $namespace);
        $this->streams->fields->delete_field('tanggal_tutup', $namespace);
        $this->streams->fields->delete_field('status_paket', $namespace);
        $this->streams->fields->delete_field('produk_id', $namespace);
        $this->streams->fields->delete_field('alokasi_waktu', $namespace);

        // GROUP SOAL STREAM
        $namespace = 'group_soal';
        $this->streams->streams->delete_stream('group_soal', $namespace);

        $this->streams->fields->delete_field('paket_id', $namespace);
        $this->streams->fields->delete_field('instruksi', $namespace);
        $this->streams->fields->delete_field('judul', $namespace);

        // SOAL STREAM
        $namespace = 'soal';
        $this->streams->streams->delete_stream('soal', $namespace);

        $this->streams->fields->delete_field('group_id', $namespace);
        $this->streams->fields->delete_field('pertanyaan', $namespace);
        $this->streams->fields->delete_field('pilihan_a', $namespace);
        $this->streams->fields->delete_field('pilihan_b', $namespace);
        $this->streams->fields->delete_field('pilihan_c', $namespace);
        $this->streams->fields->delete_field('pilihan_d', $namespace);
        $this->streams->fields->delete_field('jawaban', $namespace);
        $this->streams->fields->delete_field('paket_id', $namespace);

        // JAWABAN STREAM
        $namespace = 'jawaban';
        $this->streams->streams->delete_stream('jawaban', $namespace);

        $this->streams->fields->delete_field('user_id', $namespace);
        $this->streams->fields->delete_field('paket_id', $namespace);
        $this->streams->fields->delete_field('soal_id', $namespace);
        $this->streams->fields->delete_field('jawaban', $namespace);

        // TO USER STREAM
        $namespace = 'to_user';
        $this->streams->streams->delete_stream('to_user', $namespace);

        $this->streams->fields->delete_field('user_id', $namespace);
        $this->streams->fields->delete_field('status_pengerjaan', $namespace);
        $this->streams->fields->delete_field('nilai', $namespace);
        $this->streams->fields->delete_field('paket_id', $namespace);
        $this->streams->fields->delete_field('jam_mulai', $namespace);
        $this->streams->fields->delete_field('jam_selesai', $namespace);

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