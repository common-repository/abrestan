<?php


namespace ABR\Api;



class abrestan_logout
{
    public function Logout()
    {
        if (get_option('abrestan_login')){
            delete_option('abrestan_login');
            abrestan_log("Logout","Login information deleted.","SUCCESS");
        }
        else{
            abrestan_log("Logout","Login information could not be deleted.","ERROR");
        }

        if (get_option('abrestan_company')){
            delete_option('abrestan_company');
            abrestan_log("Logout","Selected company removed.","SUCCESS");
        }
        else{
            abrestan_log("Logout","The selected company could not be removed!","ERROR");
        }
        if (get_option('abrestan_companies_list')){
            delete_option('abrestan_companies_list');
            abrestan_log("Logout","Company list deleted.","SUCCESS");
        }
        else{
            abrestan_log("Logout","Company list could not be deleted!","ERROR");
        }

        abrestan_log("Logout","User successfully logged out.","SUCCESS");
        $data_feedback = [
            'message' => 'با موفقیت خارج شدید!',
            'class' => 'uk-alert uk-alert-danger'
        ];
        return $data_feedback;

    }
}