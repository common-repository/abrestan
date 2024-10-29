<?php


namespace ABR\Controller;

use ABR\Base\ABR_jdf;
class ConvertDate
{
    public $date;
    public function __construct($date_en)
    {
        $jdf=new ABR_jdf();
        $date_miladi=$date_en;
        date_default_timezone_set(get_option('timezone_string'));
        $array = explode(' ', $date_miladi);
        list($year, $month, $day) = explode('-', $array[0]);
        list($hour, $minute, $second) = explode(':', $array[1]);
        $timestamp = mktime($hour, $minute, $second, $month, $day, $year);
        $jalali_date = $jdf->jdate("Y-m-d", $timestamp,"","",true);
        $this->date= $jalali_date;
    }
    public function convert_date()
    {
        return $this->date;
    }

}