<?php


namespace ABR\Api;




class abrestan_api
{
    public function apiRequest($uri,$method, $data = array())
    {
        if ($uri == null) {
            return false;
        }

        $endpoint = 'https://kasb.abrestan.com/webservice/v1/' . $uri;

        $body = array_merge($data,            [
            'token' => (get_option('abrestan_login')['token']) ? get_option('abrestan_login')['token'] : ''
        ]);


        $options = array(
            'body' => $body,
            'timeout' => 60,
            'redirection' => 5,
            'blocking' => true,
            'httpversion' => '1.0',
            'sslverify' => false,
            'data_format' => 'body',
            'method'     => $method
        );

            $wp_remote_post = wp_remote_request($endpoint, $options);

            return $wp_remote_post;
    }
    public function Login($Data)
    {
        $uri = 'login';
        $method='POST';

        return $this->apiRequest($uri,$method, $Data);
    }
    public function SyncProduct($Data)
    {
        $uri = 'import/commodities/or/persons';
        $method='POST';

        return $this->apiRequest($uri,$method, $Data);
    }
    public function SelectCompany($Data)
    {
        $uri = 'set/domain';
        $method='POST';
        return $this->apiRequest($uri,$method, $Data);
    }
    public function SyncUser($Data)
    {
        $uri = 'import/commodities/or/persons';
        $method='POST';

        return $this->apiRequest($uri,$method, $Data);
    }
    public function SyncOrder($Data)
    {
        $uri = 'insert/sale/factor';
        $method='POST';
        return $this->apiRequest($uri,$method, $Data);
    }
    public function getOrders($Data)
    {
        $uri = 'sale/factor/all';
        $method='GET';
        return $this->apiRequest($uri,$method,$Data);
    }
    public function DeleteOrder($Data)
    {
        $uri = 'delete/sale/factor';
        $method='POST';
        return $this->apiRequest($uri,$method, $Data);
    }
}