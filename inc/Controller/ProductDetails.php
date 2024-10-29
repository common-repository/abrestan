<?php


namespace ABR\Controller;


class ProductDetails
{
    public $product;
    public $products;



    public function __construct($product)
    {
        $this->products =$product;

    }

    public function get_product_id($item)
    {
        $variation_id =  $item["variation_id"];
        $product_id =  $item["product_id"];
        if($variation_id == "0" && $product_id=="0"){
            return "error";
        }
        elseif ($variation_id == "0") {
            $product_id =  $item["product_id"];
        } else {
            $product_id = $item["variation_id"];
        }

        return $product_id;
    }
    public function get_product_name($item)
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
        $product_name=$item->get_name();
        $meta_data = $item->get_all_formatted_meta_data( '' );
        if ( $meta_data ){
            if (count($meta_data)>1){
                foreach ( $meta_data as $meta_id => $meta ){
                    if ( in_array( $meta->key, $hidden_order_itemmeta, true ) ) {
                        continue;
                    }
                    $product_name=$product_name." - ".strip_tags(wp_kses_post( force_balance_tags( $meta->display_value )));

                }
            }

        }


        return $product_name;
    }
    public function get_product($product_id)
    {
        $product=wc_get_product($product_id);

        return $product;
    }

    public function get_product_price($product_id)
    {

        $product=$this->get_product($product_id);

        if ($product->is_on_sale()) {
            $price = (float)$product->get_regular_price();
        } else {
            $price = (float)$product->get_price();
        }
        return $price;
    }
    public function get_product_discount($product_id,$item)
    {
        $product=$this->get_product($product_id);

        if (!$product->is_on_sale()) {
            return 0;
        }
        $regular_price = (float)$product->get_regular_price();
        $sale_price = (float)$product->get_price();
        $price=$regular_price - $sale_price;
        $price=$price*$item['quantity'];

        if ($item->get_subtotal() !== $item->get_total()){
            $price=$price+($item->get_subtotal() - $item->get_total());
        }
        return $price;
    }
    public function virual($product_id)
    {
        $product=$this->get_product($product_id);


        if ($product->is_virtual()||$product->is_downloadable())
        {
            return 2;
        }
        else
        {
            return 1;
        }

    }

}