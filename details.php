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
                    'name' => 'Try Out Order',
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

    /**
     * Install
     *
     * This function will set up our
     * FAQ/Category streams.
     */
    public function install()
    {
        // We're using the streams API to
        // do data setup.
        /*$this->load->driver('Streams');

        $this->load->language('ujian/ujian');

        // Add faqs streams
        if ( ! $this->streams->streams->add_stream('Paket', 'paket', 'streams', 'to_', null)) return false;
        if ( ! $categories_stream_id = $this->streams->streams->add_stream('lang:faq:categories', 'categories', 'faq', 'faq_', null)) return false;

        if ( ! $this->streams->streams->add_stream('lang:ujian:ujian', 'faqs', 'faq', 'to_', null)) return false;
        if ( ! $this->streams->streams->add_stream('lang:ujian:ujian', 'faqs', 'faq', 'to_', null)) return false;
        
        
        //$faq_categories

        // Add some fields
        $fields = array(
            array(
                'name' => 'Test',
                'slug' => '',
                'namespace' => 'faq',
                'type' => 'text',
                'extra' => array('max_length' => 200),
                'assign' => 'faqs',
                'title_column' => true,
                'required' => true,
                'unique' => true
            ),
            array(
                'name' => 'Answer',
                'slug' => 'answer',
                'namespace' => 'faq',
                'type' => 'textarea',
                'assign' => 'faqs',
                'required' => true
            ),
            array(
                'name' => 'Title',
                'slug' => 'faq_category_title',
                'namespace' => 'faq',
                'type' => 'text',
                'assign' => 'categories',
                'title_column' => true,
                'required' => true,
                'unique' => true
            ),
            array(
                'name' => 'Category',
                'slug' => 'faq_category_select',
                'namespace' => 'faq',
                'type' => 'relationship',
                'assign' => 'faqs',
                'extra' => array('choose_stream' => $categories_stream_id)
            )
        );

        $this->streams->fields->add_fields($fields);

        $this->streams->streams->update_stream('faqs', 'faq', array(
            'view_options' => array(
                'id',
                'question',
                'answer',
                'faq_category_select'
            )
        ));

        $this->streams->streams->update_stream('categories', 'faq', array(
            'view_options' => array(
                'id',
                'faq_category_title'
            )
        ));*/

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
        /*$this->load->driver('Streams');

        // For this teardown we are using the simple remove_namespace
        // utility in the Streams API Utilties driver.
        $this->streams->utilities->remove_namespace('ujian');*/

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