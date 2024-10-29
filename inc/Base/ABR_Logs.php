<?php


namespace ABR\Base;


class ABR_Logs
{
    public function register()
    {
            $this->GenerateLog();
    }
    public  function  GenerateLog(){
        $logs=get_log();
        $file = fopen(dirname(__DIR__,2) . "/abrestan_logs.log", "a");
        foreach ($logs as $log){
            $id = str_pad($log->id, 10, " ", STR_PAD_BOTH);
            $time = str_pad($log->time, 30, " ", STR_PAD_BOTH);
            $Status = str_pad($log->status, 10, " ", STR_PAD_BOTH);
            $action = str_pad($log->action, 20, " ", STR_PAD_BOTH);
            fwrite($file, "\n" . $id . "|". $time . "|" . $Status . "|" . $action . "|    " . $log->message);
        }
        fclose($file);
        return "a";
    }
}