<?php

namespace ABR\Api;


use ABR\Controller\ConvertDate;
use ABR\Controller\ItemsDetails;
use ABR\Controller\ProductDetails;
use WC_Product;

class abrestan_CreateFactor
{
    public $order;
    public $order_id;

    public function randomStr($n = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }
    public function tr_num($str, $mod = 'en', $mf = '٫') {
        $num_a = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.');
        $key_a = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', $mf);
        return ($mod == 'fa') ? str_replace($num_a, $key_a, $str) : str_replace($key_a, $num_a, $str);
    }

    public function create_factor($order_id)
    {
        $hidden_order_itemmeta = apply_filters(
            'woocommerce_hidden_order_itemmeta',
            array(
                '_qty',
                '_tax_class',
                '_product_id',
                '_variation_id',
                '_line_subtotal',
                '_line_subtotal_tax',
                '_line_total',
                '_line_tax',
                'method_id',
                'cost',
                '_reduced_stock',
                '_restock_refunded_items',
            )
        );




        $item_details = new ItemsDetails();
        $order = wc_get_order($order_id);
        $custmer_id = $order->get_customer_id();

        $order_currency = $order->get_currency();
        $order_items = $order->get_items();

        $order_billing_data = $order->get_data()['billing'];
        $custmerFirstName = $order_billing_data['first_name'];
        $custmerLastName = $order_billing_data['last_name'];

        $user = new \WP_User($custmer_id);

        if (!$user->exists()) {
var_dump($this->tr_num($order_billing_data['phone']));





            $custmerFirstName = ($order_billing_data['first_name'])?$order_billing_data['first_name']:(($order_billing_data['last_name'])?$order_billing_data['last_name']:"کاربر");
            $custmerLastName = ($order_billing_data['last_name'])?$order_billing_data['last_name']:(($order_billing_data['first_name'])?$order_billing_data['first_name']:"مهمان");;
            $custmerUserLogin = ($order_billing_data['phone'])?$this->tr_num($order_billing_data['phone']):$this->randomStr(12);
            $custmerUserEmail = ($order_billing_data['email'])?$order_billing_data['email']:"";

            var_dump($custmerFirstName);
            var_dump($custmerLastName);
            var_dump($custmerUserLogin);
            var_dump($custmerUserEmail);
            var_dump($custmer_id);

            if ($custmer_id == 0) {
                if (abrestan_string_to_bool(get_option('abrestan_setting')['AddCustomerToOrder'])) {
                    $lastUser = get_user_by('login', $order_billing_data['phone']);

                    if ($lastUser) {
                        $custmer_id = $lastUser->ID;
                        update_post_meta($order->get_id(), '_customer_user', $custmer_id);
                    } else {
                        $userdata = array(
                            'user_pass' => $this->randomStr(12),   //(string) The plain-text user password.
                            'user_login' => $custmerUserLogin,   //(string) The user's login username.
                            'user_email' => $custmerUserEmail,   //(string) The user email address.
                            'display_name' => $custmerFirstName . " " . $custmerLastName,   //(string) The user's display name. Default is the user's username.
                            'first_name' => $custmerFirstName,   //(string) The user's first name. For new users, will be used to build the first part of the user's display name if $display_name is not specified.
                            'last_name' => $custmerLastName,   //(string) The user's last name. For new users, will be used to build the second part of the user's display name if $display_name is not specified.
                            'role' => 'Customer',
                        );
                        $user_id = wp_insert_user($userdata);
                        update_post_meta($order->get_id(), '_customer_user', $user_id);
                        $custmer_id = $user_id;
                    }
                } else {
                    $custmer_id = "9999";
                    $custmerFirstName = "کاربر";
                    $custmerLastName = "مهمان";
                }

            } else {



                $lastUserbymobile = get_user_by('login', $order_billing_data['phone']);
                $lastUserbyemail = get_user_by('email', $order_billing_data['email']);

                if ($lastUserbymobile) {
                    $custmer_id = $lastUserbymobile->ID;
                    update_post_meta($order->get_id(), '_customer_user', $custmer_id);
                } elseif ($lastUserbyemail) {
                    $custmer_id = $lastUserbyemail->ID;
                    update_post_meta($order->get_id(), '_customer_user', $custmer_id);
                } else {
                    $userdata = array(
                        'user_pass' => $this->randomStr(12),   //(string) The plain-text user password.
                        'user_login' => $custmerUserLogin,   //(string) The user's login username.
                        'user_email' => $custmerUserEmail,   //(string) The user email address.
                        'display_name' => $custmerFirstName . " " . $custmerLastName,   //(string) The user's display name. Default is the user's username.
                        'first_name' => $custmerFirstName,   //(string) The user's first name. For new users, will be used to build the first part of the user's display name if $display_name is not specified.
                        'last_name' => $custmerLastName,   //(string) The user's last name. For new users, will be used to build the second part of the user's display name if $display_name is not specified.
                        'role' => 'Customer',
                    );
                    $user_id = wp_insert_user($userdata);
                    update_post_meta($order->get_id(), '_customer_user', $user_id);
                    $custmer_id = $user_id;
                }
            }


        }

        $products = array();
        foreach ($order_items as $order_item) {
            $product_details = new ProductDetails($order_item);
            $product_data = $order_item->get_data();
            $product_id = $product_details->get_product_id($product_data);



            if ($product_id != "error") {








                $product_name = $product_details->get_product_name($order_item);
                $product_quantity = $product_data['quantity'];
                $product_price = $item_details->covert_price($product_data['subtotal'] / $product_data['quantity'], $order_currency);
                $product_discount = $item_details->covert_price($product_data['subtotal'] - $product_data['total'], $order_currency);
                $product_tax = $item_details->covert_price($product_data['subtotal_tax'], $order_currency);
                $product_virtual = $product_details->virual($product_data["product_id"]);

                $product = array(
                    'name' => $product_name,
                    'w_code' => $product_id,
                    'amount' => $product_price,
                    'number' => $product_quantity,
                    'type' => $product_virtual,
                    'discount' => $product_discount,
                    'tax_complication' => $product_tax
                );
                array_push($products, $product);
            }
        }

        $jalali_date = new ConvertDate($order->get_date_created()->date("Y-m-d H:i:s"));


        $display_name = get_userdata($custmer_id);
        $transition = $item_details->covert_price($order->get_data()['shipping_total'], $order_currency);
        $company = get_option("abrestan_company")['companyCode'];

        $factor = array(
            "factor_date" => $jalali_date->convert_date(),
            "company_id" => $company,
            "customer" =>
                [
                    "firstname" => $custmerFirstName,
                    "lastname" => $custmerLastName,
                    "displayname" => $custmerFirstName . " " . $custmerLastName,
                    "w_code" => $custmer_id,
                    "type" => 1,
                    "tel" => $order_billing_data['phone'],
                    "email" => $order_billing_data['email'],
                    "city" => $order_billing_data['city'],
                    "address" => $order_billing_data['address_1'],
                    "postal_code" => $order_billing_data['postcode'],
                ],
            "factor_items" => $products,
            "transition_cost" => $transition,

        );


        if (get_post_meta($order->get_id(), "factor_id_abr")) {
            $factor1 = get_post_meta($order->get_id(), "factor_id_abr");
            $factor_id = array(
                "factor_id" => $factor1['0']
            );
            $factor = array_merge($factor, $factor_id);

        }


        return $factor;

    }

}