<?php


namespace ABR\Api;


use function Sodium\add;

class abrestan_sync_products
{
    public function Sync_products()
    {
        $arg =
            [
                'status' => array('publish'),
                'type' => array('external', 'simple', 'variable'),
                'limit' => -1,
                'orderby' => 'id',
                'order' => 'ASC',
            ];
        $products = wc_get_products($arg);

        $all_products = [];
        foreach ($products as $product) {
            if ($product->has_child()) {
                $children_ids = $product->get_children();
                foreach ($children_ids as $child_id) {
                    $items = wc_get_product($child_id);
                    $name=$items->get_data()['name'];
                    $item =
                        [
                            'name' => $name,
                            'w_code' => $items->get_id(),
                            'type' => $this->virual($product),
                            'sale_price'=>$items->get_price()
                        ];
                    array_push($all_products, $item);
                }
            }
            else{
                $item =
                    [
                        'name' => $product->get_name(),
                        'w_code' => $product->get_id(),
                        'type' => $this->virual($product),
                        'sale_price'=>$product->get_price()
                    ];
                array_push($all_products, $item);
            }

        }
        return $all_products;
    }

    public function virual($product)
    {
        if ($product->is_virtual() || $product->is_downloadable()) {
            return 2;
        } else {
            return 1;
        }

    }

    public function Send_products($products)
    {
        $company = get_option("abrestan_company");
        $Data =
            [
                'company_id' => $company,
                'data' => json_encode($products),
                'type' => '2',
            ];

        $abrestanApi = new abrestan_api();
        $wp_remote_post = $abrestanApi->SyncProduct($Data);

        if ($wp_remote_post->errors) {
            abrestan_log("Login", $wp_remote_post->get_error_messages(), "ERROR");
            $data_feedback = [
                'message' => 'ارتباط با سرور ابرستان برقرار نیست!',
                'code' => 400,
                'class' => 'uk-alert uk-alert-danger'
            ];
            return $data_feedback;
        } else {

            $result = json_decode(wp_remote_retrieve_body($wp_remote_post), true);
            $response_code = $wp_remote_post['response']['code'];

            switch ($response_code) {
                case 200:
                    $data_feedback = [
                        'message' => 'محصولات با موفقیت همگام سازی شدند.',
                        'code' => $response_code,
                        'class' => 'uk-alert uk-alert-success'
                    ];
                    foreach ($products as $product) {
                        abrestan_log("Sync Product", "Product number " . $product['w_code'] . " was successfully added to abrestan.", "success");
                    }
                    return $data_feedback;
                case 400:
                    switch ($result['code']) {
                        case 138:
                            abrestan_log("Sync Product", "Product not found for syncing!", "ERROR");
                            $data_feedback = [
                                'message' => ' محصولی برای همگام سازی وجود ندارد!',
                                'code' => $response_code,
                                'class' => 'uk-alert uk-alert-danger'
                            ];
                            return $data_feedback;
                        case 139:
                            abrestan_log("Sync Product", "The request does not specify the type of information, whether the product or the user!", "ERROR");
                            $data_feedback = [
                                'message' => 'در درخواست نوع اطلاعات اعم از کالا یا کاربر مشخص نشده است.',
                                'code' => $response_code,
                                'class' => 'uk-alert uk-alert-danger'
                            ];
                            return $data_feedback;
                    }
                    return false;
                case 500:
                    abrestan_log("Sync Product", "Internal Server Error", "ERROR");
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