<?php


namespace ABR\Api;



class abrestan_company
{
    public function Company($companyCode,$companyName)
    {
        if ($companyCode==0){
            abrestan_log("Select Business","Business not selected!","ERROR");
            $data_feedback = [
                'message' => 'کسب و کار انتخاب نشده است!',
                'code' => 400,
                'class' => 'uk-alert uk-alert-danger'
            ];
            delete_option('abrestan_company');
            return  $data_feedback;
        }

        $Data =
            [
                'company_id'=>$companyCode,
            ];
        $abrestanApi = new abrestan_api();
        $wp_remote_post = $abrestanApi->SelectCompany($Data);

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
                    abrestan_log("Select Business","Business No. $companyCode with the name $companyName was successfully selected.","SUCCESS");
                    $company=[
                        'companyCode'=>$companyCode,
                        'companyName'=>$companyName
                    ];
                    update_option('abrestan_company',$company);
                    $data_feedback = [
                        'message' => 'کسب و کار با موفقیت انتخاب شد.',
                        'code' => $response_code,
                        'class' => 'uk-alert uk-alert-success'
                    ];
                    return $data_feedback;
                case 400:
                    switch ($result['code']) {
                        case 106:
                            abrestan_log("Select Business","Business No. $companyCode with the name $companyName was not selected successfully!","ERROR");
                            $data_feedback = [
                                'message' => 'انتخاب کسب و کار با خطا روبرو شد!',
                                'code' => $response_code,
                                'class' => 'uk-alert uk-alert-danger'
                            ];
                            return  $data_feedback;
                    }
                    return false;
            }
        }

    }
}