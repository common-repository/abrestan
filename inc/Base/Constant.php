<?php

namespace ABR\Base;

class Constant
{
    public function register()
    {
        if (!defined('ABR_PATH')) {
            define('ABR_PATH', trailingslashit(plugin_dir_path(dirname(__FILE__,2))));
        }
        if (!defined('ABR_URL')) {
            define('ABR_URL', trailingslashit(plugin_dir_url(dirname(__FILE__,2))));
        }
        if (!defined('ABR_CSS')) {
            define('ABR_CSS', trailingslashit(ABR_URL) . 'assets/css/');
        }
        if (!defined('ABR_JS')) {
            define('ABR_JS', trailingslashit(ABR_URL) . 'assets/js/');
        }
        if (!defined('ABR_IMG')) {
            define('ABR_IMG', trailingslashit(ABR_URL) . 'assets/images/');
        }

        if (!defined('ABR_ADMIN_CSS')) {
            define('ABR_ADMIN_CSS', trailingslashit(ABR_URL) . 'assets/css/');
        }

        if (!defined('ABR_ADMIN_JS')) {
            define('ABR_ADMIN_JS', trailingslashit(ABR_URL) . 'assets/js/');
        }

        if (!defined('ABR_ADMIN_IMG')) {
            define('ABR_ADMIN_IMG', trailingslashit(ABR_URL) . 'assets/images/');
        }

        if (!defined('ABR_TPL')) {
            define('ABR_TPL', trailingslashit(ABR_PATH . 'templates'));
        }

        if (!defined('ABR_INC')) {
            define('ABR_INC', trailingslashit(ABR_PATH . 'inc'));
        }


    }
}