<?php


namespace ABR\Controller;


class SaveSetting
{
    public function Save_data($data)
    {
        delete_option('abrestan_setting');
        update_option('abrestan_setting', $data);
        abrestan_log("save setting", "Settings updated.", "SUCCESS");
        $data_feedback = [
            'message' => 'تنظیمات بروزرسانی شدند.',
            'code' => 200,
            'class' => 'uk-alert uk-alert-success'
        ];

        return $data_feedback;

    }

}