<?php


namespace ABR\Api;


use ABR\Controller\GetToken;
use Illuminate\Support\Facades\Date;
use ABR\Api\abrestan_CreateFactor;
use ABR\Base\ABR_jdf;
use ABR\Base\jdf;
use ABR\Controller\ConvertDate;
use ABR\Controller\ItemsDetails;
use ABR\Controller\ProductDetails;


class abrestan_sync_orders
{
    public $factor = [];
    public $product;

    public function Sync_orders($from_date, $to_date)
    {
        $jdf = new ABR_jdf();
        $fdate_jalali = $from_date;
        date_default_timezone_set(get_option('timezone_string'));
        $array1 = explode(' ', $fdate_jalali);
        list($year, $month, $day) = explode('/', $array1[0]);
        $f_miladi_date = $jdf->jalali_to_gregorian($year, $month, $day, '-');
        $tdate_jalali = $to_date;
        $array2 = explode(' ', $tdate_jalali);
        list($year, $month, $day) = explode('/', $array2[0]);
        $t_miladi_date = $jdf->jalali_to_gregorian($year, $month, $day, '-');

        $arg =
            [
                'limit' => -1,
                'date_created' => $f_miladi_date . "..." . $t_miladi_date,
                'orderby' => 'date_created',
                'order' => 'ASC',
                'status' => array('wc-completed'),
            ];
        $orders = wc_get_orders($arg);
        $all_orders = [];

        $Data =
            [
                'company_id' => get_option("abrestan_company")['companyCode'],
            ];
        $abrestanApi = new abrestan_api();
        $wp_remote_post = $abrestanApi->getOrders($Data);

        $abrestanFactors = json_decode(wp_remote_retrieve_body($wp_remote_post), true)['factors'];

        function searchForId($id, $array)
        {
            foreach ($array as $key => $val) {
                if ($val['generation_id'] === $id) {
                    return true;
                }
            }
            return false;
        }


        foreach ($orders as $order) {
            array_push($all_orders, $order->get_id());
        }
        $oldSent = abrestanGetOrderByOrderIds($all_orders);
        $oldorders = [];

        foreach ($oldSent as $sent) {

            if (searchForId($sent->factor_id, $abrestanFactors)) {
                array_push($oldorders, $sent->order_id);
            }else{
                abrestanDeleteOrderByOrderId($sent->order_id, get_option("abrestan_company")['companyCode']);
            }
        }

        return array_values(array_diff($all_orders, $oldorders));
    }


    public function Send_orders($orders)
    {
        $factors = new abrestan_CreateFactor();
        $factors = $factors->create_factor($orders[0]);
        if ($factors == 0) {
            abrestan_log("Sync Order", "Order #$orders[0] information is not complete!", "ERROR");
            $data_feedback = [
                'message' => 'اطلاعات فاکتور کامل نیست!',
                'code' => 400,
                'class' => 'uk-alert uk-alert-danger'
            ];

            return $data_feedback;
        }

        $company = get_option("abrestan_company")['companyCode'];
        $abrestanOrders = abrestanGetOrderByOrderId($orders[0]);

        foreach ($abrestanOrders as $abrestanOrder) {
            if ($abrestanOrder->company_id == $company && !empty($abrestanOrder->factor_id)) {
                abrestan_log("Sync Order", "Invoice No. $orders[0] has already been registered in this business!", "ERROR");
                $data_feedback = [
                    'message' => "فاکتور شماره $orders[0] در این کسب و کار ثبت شده است.",
                    'code' => 400,
                    'class' => 'uk-alert uk-alert-danger'
                ];
                delete_post_meta($orders[0], "abrestan_error");
                return $data_feedback;
            }
        }

        $Data =
            [
                'factor_date' => $factors['factor_date'],
                'company_id' => $factors['company_id'],
                'customer' => json_encode($factors['customer']),
                'transition_cost' => $factors['transition_cost'],
                'factor_items' => json_encode($factors['factor_items']),
                'AddFactorItemsToInitialBalance' => abrestan_string_to_bool(get_option("abrestan_setting")['InitialBalance']),

            ];

        $abrestanApi = new abrestan_api();
        $wp_remote_post = $abrestanApi->SyncOrder($Data);
        $result = json_decode(wp_remote_retrieve_body($wp_remote_post), true);

        var_dump($result);

        if ($result['code'] == 105) {

            $getToken = new GetToken();
            $getToken->register();
        }

        if ($wp_remote_post->errors) {
            abrestan_log("Sync Order", $wp_remote_post->get_error_messages(), "ERROR");
            $data_feedback = [
                'message' => 'ارتباط با سرور ابرستان برقرار نیست!',
                'code' => 400,
                'class' => 'uk-alert uk-alert-danger'
            ];
            delete_post_meta($orders[0], "abrestan_error");
            return $data_feedback;
        } else {
            $result = json_decode(wp_remote_retrieve_body($wp_remote_post), true);
            $response_code = $wp_remote_post['response']['code'];

            switch ($response_code) {
                case 200:
                    $data_feedback = [
                        'message' => 'عملیات با موفقیت انجام شد.',
                        'code' => $response_code,
                        'class' => 'uk-alert uk-alert-success'
                    ];
                    update_post_meta($orders[0], "order_diff", $factors);
                    delete_post_meta($orders[0], "abrestan_error");
                    abrestan_log("Sync Order", "Order #" . $orders[0] . " was successfully synced.", "SUCCESS");
                    $order = wc_get_order($orders[0]);
                    $order->add_order_note("فاکتور شماره " . $result['factor_number'] . " با موفقیت در ابرستان ثبت گردید.", 0, 0);

                    abrestanOrderInfo($orders[0], $result['factor_id'], $result['factor_number']);
                    return $data_feedback;
                case 400:
                    switch ($result['code']) {
                        case 160:
                            update_post_meta($orders[0], "abrestan_error", $result['result']);
                            abrestan_log("Sync Order", "Order #" . $orders[0] . " could not be synced with Abrestan.", "WARNING");
                            $data_feedback = [
                                'message' => 'عملیات با موفقیت انجام شد. اما برخی از سفارشات به دلیل عدم موجودی کالا در ابرستان ثبت نشدند. برای بررسی بیشتر رخدادها را مشاهده بفرمایید.',
                                'code' => $response_code,
                                'class' => 'uk-alert uk-alert-warning'
                            ];
                            return $data_feedback;

                    }
                    return false;
                case 500:
                    abrestan_log("Sync Order", "Internal Server Error #" . $orders[0], "ERROR");
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