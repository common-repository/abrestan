<?php


namespace ABR\Controller;


use ABR\Api\abrestan_api;


class GetToken
{
    public function register()
    {

        if (get_option('abrestan_login')) {

            $time = get_option("abrestan_login")['time'];
            $timeToGet = get_option("abrestan_login")['ttl'];

            if (time() - $time >= $timeToGet) {
                $Encrypted_username=get_option('abrestan_login')['username'];
                $Encrypted_password=get_option('abrestan_login')['password'];
                $Data =
                    [
                        'URL' => "https://kasb.abrestan.com/webservice/v1/login",
                        'method' => "POST",
                        'username' => $Encrypted_username,
                        'password' => $Encrypted_password,

                    ];

                $abrestanApi = new abrestan_api();
                $wp_remote_post = $abrestanApi->Login($Data);
                $result = json_decode(wp_remote_retrieve_body($wp_remote_post), true);

                if ($wp_remote_post['response']['code'] == 200) {

                    switch ($result['code']){
                        case 104:
                            $data_option =
                                [
                                    'username' => $Encrypted_username,
                                    'password' => $Encrypted_password,
                                    'token' => $result['token'],
                                    'ttl' => $result['ttl'],
                                    'time' => time()
                                ];

                            update_option('abrestan_login', $data_option);
                            update_option('abrestan_companies_list', $result['companies_list']);
                            $company=new \ABR\Api\abrestan_company();
                            $company->Company(get_option("abrestan_company")['companyCode'],get_option('abrestan_company')['companyName']);
                    }

                }
            }

        }
    }

}