<?php

namespace ABR\Pages;

use ABR\Base\SettingApi;


class Admin
{

    public $setting;
    public $pages = [];
    public $subpages = [];

    function __construct()
    {
        $this->setting = new SettingApi();
        $this->pages =
            [
                [
                    'page_title' => __('abrestan','abrestan'),
                    'menu_title' => __('abrestan','abrestan'),
                    'capability' => 'manage_options',
                    'menu_slug' => 'abrestan',
                    'callback' => array($this,'add_template') ,
                    'icon_url' => 'dashicons-media-code',
                    'position' => 1,
                ]
            ];
        $this->subpages = [];

    }
    public function add_template(){
        require_once ABR_TPL."admin/admin.php";
    }


    public function register()
    {
        $this->setting->addPages($this->pages)->addSubPages($this->subpages)->register();
    }


}
