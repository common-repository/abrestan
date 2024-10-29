<?php


namespace ABR\Base;


class order_metabox
{
    public function register()
    {
        add_action('add_meta_boxes', [$this, 'add_meta_boxesws']);
    }

    function add_meta_boxesws()
    {
        add_meta_box(
            'add_meta_boxes',
            __('ابرستان'),
            [$this, 'sun'],
            'shop_order'
        );
    }

    function sun()
    {
        if (isset( $_REQUEST['post'])) {
            $post_id = sanitize_text_field($_REQUEST['post']);
            ?>
            <div id="alert-success-sync_orders" uk-alert style="display: none" dir="rtl">
                <p></p>
            </div>
            <?php
            $factor_id = get_post_meta($post_id, 'factor_no_abr');
            if ($factor_id) {
                ?>
                <h1 class="uk-text-success">فاکتور مرتبط در ابرستان با شماره <?php echo esc_attr($factor_id[0]) ?> ثبت گردید.</h1>
                <?php
            }
            else{
                $abrestanOrders = abrestanGetOrderByOrderId($post_id);
                foreach ($abrestanOrders as $abrestanOrder) {
                    if ($abrestanOrder->factor_id) {
                        $company = get_option("abrestan_company")['companyCode'];
                        if ($abrestanOrder->company_id == $company) {
                            if ($abrestanOrder->factor_id) {
                                ?>
                                <h1 class="uk-text-success">فاکتور مرتبط در ابرستان با
                                    شماره <?php echo esc_attr($abrestanOrder->factor_no) ?> ثبت گردید.</h1>
                                <?php
                            }
                        }

                    }
                }
            }

            if (get_post_meta($post_id, 'abrestan_error')) {
                $error = get_post_meta($post_id, 'abrestan_error');
                ?>
                <h1 class="uk-text-danger">به دلیل عدم موجودی کالا های زیر در ابرستان فاکتور ثبت نشد!</h1>
                <table class="uk-table uk-table-striped">
                    <thead>
                    <tr>
                        <th class="uk-table-expand">نام کالا</th>
                        <th class="uk-width-small">تعداد</th>
                        <th class="uk-width-small">موجودی انبار</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($error[0] as $item) {
                        ?>
                        <tr>
                            <td><?php echo esc_attr($item['name']) ?></td>
                            <td><?php echo esc_attr($item['number']) ?></td>
                            <td><?php echo esc_attr($item['number'] - abs($item['remain'])) ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>

                <?php

            }
            ?>

            <input type="hidden" name="orders_id" value="<?php echo esc_attr($post_id) ?>">
            <div class="uk-margin">
                <div class="uk-flex uk-flex-around	">
                    <div class="uk-button uk-button-primary uk-button-large btn_loader"
                         id="abrestan_send_orders_btn"><?php _e('send invoice', 'abrestan') ?>
                        <div class="loader"></div>
                    </div>
                </div>
            </div>
            <?php

            /*else {
                ?>
                <h1 class="uk-text-warning">برای ثبت فاکتور باید وضعیت فاکتور در حالت تکمیل شده قرار بگیرد!</h1>
                <?php
            }*/

        }


    }

}