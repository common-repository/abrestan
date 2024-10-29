<?php


namespace ABR\Api;


class AddColumn
{
    public function register()
    {
        add_filter('manage_edit-shop_order_columns', [$this, 'wc_new_order_column']);
        add_action('manage_shop_order_posts_custom_column', [$this, 'cw_add_order_profit_column_content']);

        add_action('admin_print_styles', [$this, 'cw_add_order_profit_column_style']);
    }

    function wc_new_order_column($columns)
    {
        $columns['invoice'] = "فاکتور";
        return $columns;
    }

    function cw_add_order_profit_column_content($column)
    {
        global $post;
        if ('invoice' === $column) {
            if (get_post_meta($post->ID, "factor_id_abr") && get_post_meta($post->ID, "factor_no_abr")) {
                echo '<mark class="order-status status-processing tips"><span>'.esc_attr__(get_post_meta($post->ID, "factor_no_abr", true)).'</span></mark>';
            }
            else{
                $company = get_option("abrestan_company")['companyCode'];
                $abrestanOrders = abrestanGetOrderByOrderId($post->ID);
                foreach ($abrestanOrders as $abrestanOrder) {
                    if ($abrestanOrder->company_id == $company) {
                        if ($abrestanOrder->factor_id) {
                            echo '<mark class="order-status status-processing tips"><span>' . esc_attr__($abrestanOrder->factor_no) . '</span></mark>';
                        }
                    }
                }
            }


        }
    }

    function cw_add_order_profit_column_style()
    {
        $css = '.widefat .column-invoice { width: 50px !important;    padding-left: 1em !important;     text-align: center;}';
        wp_add_inline_style('woocommerce_admin_styles', $css);
    }
}