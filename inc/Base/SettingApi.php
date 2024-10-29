<?php

namespace ABR\Base;
class SettingApi
{
    public $admin_pages = [];
    public $admin_subpages = [];

    public function register()
    {

        if (!empty($this->admin_pages)) {
            add_action('admin_menu', array($this, 'addAdminMenu'));
        }
    }

    public function addPages(array $pages)
    {
        $this->admin_pages = $pages;
        return $this;

    }

    public function addSubPages(array $pages)
    {
        $this->admin_subpages = $pages;
        return $this;
    }




    public function addAdminMenu()
    {
        foreach ($this->admin_pages as $page) {
            add_menu_page($page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'], $page['icon_url'], $page['position']);
        }
        foreach ($this->admin_subpages as $subpage)
        {
            add_submenu_page($subpage['parent_slug'],$subpage['page_title'],$subpage['menu_title'],$subpage['capability'],$subpage['menu_slug'],$subpage['callback']);
        }

    }
}
