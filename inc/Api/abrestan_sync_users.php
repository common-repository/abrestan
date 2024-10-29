<?php


namespace ABR\Api;


use function Sodium\add;

class abrestan_sync_users
{
    public function Sync_users()
    {
        $users = get_users();

        $all_user = [];
        foreach ($users as $user) {
            $item =
                [
                    'firstname' => "کاربر",
                    'lastname' => "فروشگاه",
                    'displayname' => $user->data->display_name,
                    'email' => $user->data->user_email,
                    'w_code' => $user->data->ID,
                    "type" => 2
                ];
            array_push($all_user, $item);
        }
        return $all_user;
    }


    public function Send_users($users)
    {
        $company = get_option("abrestan_company");
        $Data =
            [
                'company_id' => $company,
                'data' => json_encode($users),
                'type' => '1',
            ];

        $abrestanApi = new abrestan_api();
        $wp_remote_post = $abrestanApi->SyncUser($Data);
        if ($wp_remote_post->errors) {
            abrestan_log("Login", $wp_remote_post->get_error_messages(), "ERROR");
            $data_feedback = [
                'message' => 'ارتباط با سرور ابرستان برقرار نیست!',
                'code' => 400,
                'class' => 'uk-alert uk-alert-danger'
            ];
            return $data_feedback;
        }
        else
            {
            $result = json_decode(wp_remote_retrieve_body($wp_remote_post), true);
            $response_code = $wp_remote_post['response']['code'];
            switch ($response_code) {
                case 200:
                    $data_feedback = [
                        'message' => 'کاربران با موفقیت همگام سازی شدند.',
                        'code' => $response_code,
                        'class' => 'uk-alert uk-alert-success'
                    ];
                    foreach ($users as $user) {
                        abrestan_log("Sync User", "User NO. " . $user['w_code'] . " was successfully added to abrestan.", "success");
                    }
                    return $data_feedback;
                case 400:
                    switch ($result['code']) {
                        case 138:
                            abrestan_log("Sync User", "user not found for syncing!", "ERROR");
                            $data_feedback = [
                                'message' => ' کاربری برای همگام سازی وجود ندارد!',
                                'code' => $response_code,
                                'class' => 'uk-alert uk-alert-danger'
                            ];
                            return $data_feedback;
                        case 139:
                            abrestan_log("Sync User", "The request does not specify the type of information, whether the product or the user!", "ERROR");
                            $data_feedback = [
                                'message' => 'در درخواست نوع اطلاعات اعم از کالا یا کاربر مشخص نشده است.',
                                'code' => $response_code,
                                'class' => 'uk-alert uk-alert-danger'
                            ];
                            return $data_feedback;

                    }
                    return false;
                case 500:
                    abrestan_log("Sync User", "Internal Server Error", "ERROR");
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