<?php


namespace ABR\Api;

use ABR\Controller\Encrypt;

class abrestan_login
{
    public function Login($abrestan_username, $abrestan_password)
    {
        $encrypt = new Encrypt();
        $Encrypted_username = $encrypt->encrypt($abrestan_username);
        $Encrypted_password = $encrypt->encrypt($abrestan_password);
        $Data =
            [
                'username' => $Encrypted_username,
                'password' => $Encrypted_password,
            ];
        $abrestanApi = new abrestan_api();
        $wp_remote_post = $abrestanApi->Login($Data);

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
                    $data_feedback = [
                        'message' => 'با موفقیت وارد شدید!',
                        'code' => $response_code,
                        'class' => 'uk-alert uk-alert-success'
                    ];
                    abrestan_log("Login", $abrestan_username . " has logged in successfully.", "SUCCESS");
                    return $data_feedback;
                case 400:
                    switch ($result['code']) {
                        case 101:
                            abrestan_log("Login", "Enter your mobile number or email!", "ERROR");
                            $data_feedback = [
                                'message' => 'شماره تلفن همراه یا ایمیل را وارد نمایید!',
                                'code' => $response_code,
                                'class' => 'uk-alert uk-alert-danger'
                            ];

                            return $data_feedback;
                        case 102:
                            abrestan_log("Login", "Enter the password!", "ERROR");
                            $data_feedback = [
                                'message' => 'رمز عبور را وارد نمایید!',
                                'code' => $response_code,
                                'class' => 'uk-alert uk-alert-danger'
                            ];
                            return $data_feedback;
                        case 103:
                            abrestan_log("Login", "User not found with " . $abrestan_username . " !", "ERROR");
                            $data_feedback = [
                                'message' => 'با نام کاربری ' . $abrestan_username . ' کاربری یافت نشد!',
                                'code' => $response_code,
                                'class' => 'uk-alert uk-alert-danger'
                            ];
                            return $data_feedback;
                    }
                    return false;
            }
        }

    }
}