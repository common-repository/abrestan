<?php
/**
 *    Plugin Name: abrestan
 *    Plugin URI: abrestan.com
 *    Description: Using this web service, you can connect your store to abrestan cloud accounting software.
 *    Version: 2.2.6
 *    Author: KTE
 *    WC requires at least: 4.1.0
 *    WC tested up to: 4.1.0
 *    Text Domain: abrestan
 *    Domain Path: /languages/
 *    Team: yaghootweb
 **/
defined('ABSPATH') or die();

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}
load_plugin_textdomain('abrestan', false, dirname(plugin_basename(__FILE__)) . '/languages');


use ABR\Api\abrestan_api;
use ABR\Api\abrestan_CreateFactor;
use ABR\Api\abrestan_login;
use ABR\Base\ABR_jdf;
use ABR\Init;

add_action('woocommerce_init', 'woocommerce_loaded');
function woocommerce_loaded()
{


    if (class_exists('ABR\\Init')) {
        Init::register_services();
    }

}


//register_activation_hook(__FILE__, 'install_Database_abrestan');
global $abrestan_db_version;
$abrestan_db_version = "1.0.1";

function booking_install()
{
    global $wpdb;
    global $abrestan_db_version;
    global $tableprefix;
    $installed_version = get_option('abrestan_db_option');

    $tableprefix = $wpdb->prefix . 'abrestan_';

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $charset_collate = $wpdb->get_charset_collate();

    if ($installed_version !== $abrestan_db_version) {

        $eventtable = $tableprefix . 'event';
        $sql = "CREATE TABLE " . $eventtable . " (
  id integer NOT NULL AUTO_INCREMENT,
  time TEXT NOT NULL,
  status TEXT NOT NULL,
  action TEXT NOT NULL,
  message TEXT NOT NULL,
  PRIMARY KEY  (id)
        ) " . $charset_collate . ";";
        dbDelta($sql);

        $ordertable = $tableprefix . 'order';
        $sql = "CREATE TABLE " . $ordertable . " (
  id integer NOT NULL AUTO_INCREMENT,
  company_id integer NULL ,
  order_id integer NULL ,
  factor_id TEXT NULL ,
  factor_no TEXT NULL ,
  time timestamp NOT NULL,
  PRIMARY KEY  (id)
        ) " . $charset_collate . ";";
        dbDelta($sql);

        update_option('abrestan_db_option', $abrestan_db_version);
    }
}

register_activation_hook(__FILE__, 'booking_install');



function abrestan_log($action, $message, $Status)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'abrestan_event';
    $jdf = new ABR_jdf();
    $date_miladi = date('Y-m-d H:i:s');
    $array = explode(' ', $date_miladi);
    list($year, $month, $day) = explode('-', $array[0]);
    list($hour, $minute, $second) = explode(':', $array[1]);
    $timestamp = mktime($hour, $minute, $second, $month, $day, $year);
    $jalali_date = $jdf->jdate("Y-m-d H:i:s", $timestamp, "", "", true);
    $wpdb->insert(
        $table_name,
        array(
            'time' => $jalali_date,
            'status' => $Status,
            'action' => $action,
            'message' => $message,
        )
    );
}

function abrestanOrderInfo($order_id, $invoiceID, $invoiceNO)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'abrestan_order';
    $company = get_option("abrestan_company")['companyCode'];

    $wpdb->insert(
        $table_name,
        array(
            'company_id' => $company,
            'order_id' => $order_id,
            'factor_id' => $invoiceID,
            'factor_no' => $invoiceNO,
        )
    );
}

function abrestanGetOrderByOrderId($order_id)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'abrestan_order';
    $q = $wpdb->prepare("SELECT * FROM $table_name where order_id=%d", $order_id);

    $results = $wpdb->get_results($q);
    return $results;
}
function abrestanGetOrder($order_id,$company_id)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'abrestan_order';
    $q = $wpdb->prepare("SELECT * FROM $table_name where company_id=%d AND order_id=%d ", $company_id,$order_id );
    $results = $wpdb->get_results($q);
    return $results;
}
function abrestanGetOrderByOrderIds($order_id)
{

    global $wpdb;
    $order_ids = implode( ', ', array_fill( 0, count( $order_id ), '%s' ));
    $table_name = $wpdb->prefix . 'abrestan_order';
    $sql = "SELECT * FROM $table_name where order_id IN ($order_ids)";
    $query = call_user_func_array(array($wpdb, 'prepare'), array_merge(array($sql), $order_id));
    $results = $wpdb->get_results($query);
    return $results;
}
function abrestanDeleteOrderByOrderId($order_id, $company_id)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'abrestan_order';

    $results = $wpdb->delete($table_name, ['order_id' => $order_id, 'company_id' => $company_id]);
    return $results;
}

function abrestan_string_to_bool($string)
{
    return is_bool($string) ? $string : ('yes' === strtolower($string) || 1 === $string || 'true' === strtolower($string) || '1' === $string);
}

function get_log()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "abrestan_event";
    $results = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC ");

    return $results;
}

function GenerateLog()
{
    $logs = get_log();
    if (file_exists(__DIR__ . "/abrestan_logs.log")){
        unlink(__DIR__ . "/abrestan_logs.log");
    }

    $file = fopen(__DIR__ . "/abrestan_logs.log", "a");
    foreach ($logs as $log) {
        $id = str_pad($log->id, 10, " ", STR_PAD_BOTH);
        $time = str_pad($log->time, 30, " ", STR_PAD_BOTH);
        $Status = str_pad($log->status, 10, " ", STR_PAD_BOTH);
        $action = str_pad($log->action, 20, " ", STR_PAD_BOTH);
        fwrite($file, "\n" . $id . "|" . $time . "|" . $Status . "|" . $action . "|    " . $log->message);
    }
    fclose($file);
}

add_action('wp_ajax_abrestan_login_action', 'abrestan_login_action');
add_action('wp_ajax_nopriv_abrestan_login_action', 'abrestan_login_action');
function abrestan_login_action()
{

    $login = new abrestan_login();
    echo json_encode($login->Login(sanitize_text_field($_REQUEST["abrestan_username"]), sanitize_text_field($_REQUEST["abrestan_password"])));
    wp_die();
}

add_action('wp_ajax_abrestan_logout_action', 'abrestan_logout_action');
add_action('wp_ajax_nopriv_abrestan_logout_action', 'abrestan_logout_action');
function abrestan_logout_action()
{
    $logout = new \ABR\Api\abrestan_logout();
    echo json_encode($logout->Logout());
    wp_die();
}

add_action('wp_ajax_abrestan_company_action', 'abrestan_company_action');
add_action('wp_ajax_nopriv_abrestan_company_action', 'abrestan_company_action');
function abrestan_company_action()
{
    $company = new \ABR\Api\abrestan_company();
    echo json_encode($company->Company(sanitize_text_field($_REQUEST["company_code"]), sanitize_text_field($_REQUEST["company_name"])));
    wp_die();
}

add_action('wp_ajax_abrestan_get_orders_action', 'abrestan_get_orders_action');
add_action('wp_ajax_nopriv_abrestan_get_orders_action', 'abrestan_get_orders_action');
function abrestan_get_orders_action()
{
    $sync_product = new \ABR\Api\abrestan_sync_orders();
    echo json_encode($sync_product->Sync_orders(sanitize_text_field($_REQUEST['order_from_date']), sanitize_text_field($_REQUEST['order_to_date'])));
    wp_die();
}


add_action('wp_ajax_abrestan_send_orders_action', 'abrestan_send_orders_action');
add_action('wp_ajax_nopriv_abrestan_send_orders_action', 'abrestan_send_orders_action');
function abrestan_send_orders_action()
{
    $orders = isset($_REQUEST['orders']) ?
        wp_unslash($_REQUEST['orders']) :
        array();

    $order = array();

    if (is_array($orders)) {
        foreach ($orders as $orderKey => $orderVAL) {
            $order[$orderKey] = sanitize_text_field($orderVAL);
        }
    }


    $sync_order = new \ABR\Api\abrestan_sync_orders();
    echo json_encode($sync_order->Send_orders($order));
    wp_die();

}
add_action('wp_ajax_abrestan_send_again_orders_action', 'abrestan_send_again_orders_action');
add_action('wp_ajax_nopriv_abrestan_send_again_orders_action', 'abrestan_send_again_orders_action');
function abrestan_send_again_orders_action()
{

    $orders = isset($_REQUEST['orders']) ?
        wp_unslash($_REQUEST['orders']) :
        array();

    $order = array();

    if (is_array($orders)) {
        foreach ($orders as $orderKey => $orderVAL) {
            $order[$orderKey] = sanitize_text_field($orderVAL);
        }
    }
    $Data =
        [
            'company_id' => get_option("abrestan_company")['companyCode'],
        ];
    $abrestanApi = new abrestan_api();
    $wp_remote_post = $abrestanApi->getOrders($Data);

    $abrestanFactors = json_decode(wp_remote_retrieve_body($wp_remote_post), true)['factors'];
    function searchForId($id, $array) {
        foreach ($array as $key => $val) {
            if ($val['generation_id'] === $id) {
                return true;
            }
        }
        return null;
    }
    var_dump($order);
    var_dump($abrestanFactors);
    die();

    $sync_order = new \ABR\Api\abrestan_sync_orders();
    echo json_encode($sync_order->Send_orders($order));
    wp_die();

}
add_action('wp_ajax_abrestan_save_setting_action', 'abrestan_save_setting_action');
add_action('wp_ajax_nopriv_abrestan_save_setting_action', 'abrestan_save_setting_action');
function abrestan_save_setting_action()
{
    $save_data = new \ABR\Controller\SaveSetting();
    echo json_encode($save_data->Save_data($_REQUEST));
    wp_die();
}

function abrestan_woocommerce_order_status_completed($orderid)
{

    $sync_order = new \ABR\Api\abrestan_sync_orders();
    $sync_order->Send_orders([$orderid]);


}

function delete_factor($orderid)
{

    $company = get_option("abrestan_company")['companyCode'];
    $abrestanOrders = abrestanGetOrderByOrderId($orderid);

    foreach ($abrestanOrders as $abrestanOrder) {
        if ($abrestanOrder->company_id == $company && !empty($abrestanOrder->factor_id)) {
            $abrestanApi = new abrestan_api();
            $Data =
                [
                    'factor_id' => $abrestanOrder->factor_id,
                    'company_id' => $company
                ];

            $wp_remote_post = $abrestanApi->DeleteOrder($Data);

            if ($wp_remote_post->errors) {
                abrestan_log("Delete Order", $wp_remote_post->get_error_messages(), "ERROR");
            } else {
                $result = json_decode(wp_remote_retrieve_body($wp_remote_post), true);
                $response_code = $wp_remote_post['response']['code'];
                switch ($response_code) {
                    case 200:
                        abrestanDeleteOrderByOrderId($orderid, $company);
                        delete_post_meta($orderid, "abrestan_error");
                        abrestan_log("Delete Order", "Order #" . $orderid . " was successfully deleted.", "SUCCESS");
                        $order = wc_get_order($orderid);
                        $order->add_order_note("فاکتور شماره " . $abrestanOrder->factor_no . " با موفقیت از ابرستان حذف گردید.", 0, 0);
                        break;
                    case 400:
                        switch ($result['code']) {
                            case 141:
                                abrestan_log("Delete Order", "Order #" . $orderid . " could not be deleted from Abrestan.", "ERROR");
                                break;
                        }
                        return false;
                    case 500:
                        abrestan_log("Delete Order", "Internal Server Error #" . $orderid, "ERROR");
                        $data_feedback = [
                            'message' => 'خطای سرور داخلی',
                            'code' => $response_code,
                            'class' => 'uk-alert uk-alert-danger'
                        ];
                        return $data_feedback;

                }
            }

        }
    }
}

function edit_factor($orderid)
{

    if ($_REQUEST['post_type'] == 'shop_order') {
        if (isset(get_post_meta($orderid, "order_diff")[0])) {
            $factors = new abrestan_CreateFactor();
            $factors = $factors->create_factor($orderid);
            $aaa = get_post_meta($orderid, "order_diff")[0];

            $match = true;
            foreach ($aaa as $key => $value) {

                if (is_array($value)) {
                    foreach ($value as $subkey => $subvalue) {
                        if ($aaa[$key][$subkey] != $factors[$key][$subkey]) {
                            $match = false;
                        }
                    }
                } else {
                    if ($aaa[$key] != $factors[$key]) {
                        $match = false;
                    }
                }

            }

            if ($match) {
                return false;
            }
            $company = get_option("abrestan_company")['companyCode'];
            $abrestanOrders = abrestanGetOrderByOrderId($orderid);
            if (isset($_REQUEST['action'])) {
                if ($_REQUEST['action'] == 'trash') {
                    delete_factor($orderid);
                } elseif ($_REQUEST['action'] == 'editpost') {
                    foreach ($abrestanOrders as $abrestanOrder) {
                        if ($abrestanOrder->company_id == $company && !empty($abrestanOrder->factor_id)) {
                            delete_factor($orderid);
                            $test = ($abrestanOrder->factor_id) ? true : false;
                            if ($test) {
                                $sync_order = new \ABR\Api\abrestan_sync_orders();
                                $sync_order->Send_orders([$orderid]);
                            }
                        }
                    }
                }

            }
        }

    }

}

add_action('wp_trash_post', 'delete_factor', 10, 1);
add_action('woocommerce_update_order', 'edit_factor', 10);
add_action('woocommerce_order_status_completed', 'abrestan_woocommerce_order_status_completed', 10, 1);
