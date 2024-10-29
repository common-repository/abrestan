<?php

namespace ABR\Controller;
class ItemsDetails
{

    public function covert_price(float $product_price,$product_currency)
    {

        if ($product_price == 0) {
            $edited_price = "0";
        }
        else{
            if ($product_currency == "IRR") {
                $edited_price = (float)$product_price;

            } elseif ($product_currency == "IRT") {
                $edited_price = (float)$product_price . "0";
            }
        }
        return $edited_price;
    }


}
