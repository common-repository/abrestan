<?php

namespace ABR\Base;
class ABR_Enqueue
{
    public function register()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueue'));
    }

    function enqueue()
    {

        if (is_rtl()) {
            wp_enqueue_style('uikit-rtl', ABR_ADMIN_CSS . 'uikit-rtl.min.css');
            wp_enqueue_style('custom-rtl', ABR_ADMIN_CSS . 'custom-rtl.css');
        } else {
            wp_enqueue_style('uikit', ABR_ADMIN_CSS . 'uikit.min.css');
            wp_enqueue_style('custom', ABR_ADMIN_CSS . 'custom.css');
        }
        wp_enqueue_script('uikit-script', ABR_ADMIN_JS . 'uikit.min.js');
        wp_enqueue_script('jscolor', ABR_ADMIN_JS . 'jscolor.js');
        wp_enqueue_script('custom', ABR_ADMIN_JS . 'custom210.js',['jquery']);
        wp_localize_script( 'custom', 'plugin_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    }
}
