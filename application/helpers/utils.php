<?php

function formatDuration($seconds){
    if(!is_numeric($seconds))
        return $seconds;
    $seconds=(int)$seconds;
    $hours=(int)($seconds/3600);
    if($hours<10)
        $hours="0{$hours}";
    $rest=$seconds%3600;
    $mins=(int)($rest/60);
    if($mins<10)
        $mins="0{$mins}";
    $secs=$rest%60;
    if($secs<10)
        $secs="0{$secs}";

    return "{$hours}:{$mins}:{$secs}";
}


function persian_duration($duration_seconds){

    $difference = $duration_seconds * 1000;

    $daysDifference = floor($difference / 1000 / 60 / 60 / 24);
    $difference -= $daysDifference * 1000 * 60 * 60 * 24;

    $hoursDifference = floor($difference / 1000 / 60 / 60);
    $difference -= $hoursDifference * 1000 * 60 * 60;

    $minutesDifference = floor($difference / 1000 / 60);
    $difference -= $minutesDifference * 1000 * 60;

    $secondsDifference = floor($difference / 1000);

    //EN EXPORT
    //var out_str = 'difference = ' + daysDifference + ' day/s ' + hoursDifference + ' hour/s ' + minutesDifference + ' minute/s ' + secondsDifference + ' second/s ';

    //FA EXPORT
    $out_str = '';
    if ($daysDifference != 0)
    {
        $out_str .= $daysDifference . ' روز ';
    }
    if ($hoursDifference != 0)
    {
        $out_str .= $hoursDifference . ' ساعت ' ;
    }
    if ($minutesDifference != 0)
    {
        $out_str .= $minutesDifference . ' دقیقه ' ;
    }
    if (($hoursDifference == 0) && ($daysDifference == 0))
    {
        if ($secondsDifference != 0)
        {
            $out_str .= $secondsDifference . ' ثانیه ' ;
        }
    }
    if ($out_str == "")
    {
        $out_str = "0";
    }
    return $out_str;
}


function en_num($input)
{ 
    $en_num = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9",".",".","."); //".",".","." dirty code
    $fa_num = array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹",",","٫",".");
    return str_replace($fa_num,$en_num,$input);     
}

function fa_num($input)
{
    $en_num = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
    $fa_num = array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹");
    return str_replace($en_num,$fa_num,$input);     
}

function pr($in){
    echo "<pre style='direction:ltr'>";
    echo print_r($in,true);
    echo "</pre>";
}
function pre($in){
    pr($in);
    exit;
}

function xlog($in){
    if(!function_exists("log_message"))
    {
        return;
    }
    if(is_array($in) || is_object($in))
    {
       log_message("debug",print_r($in,true));
    }else{
        log_message("debug",$in);
    }
}

function visset($in,$default=""){
    return isset($in) ? $in : $default;
}

function eisset($in,$default=""){
    echo isset($in) ? $in : $default;
}

function ejson($in){
    echo json_encode($in);
}

function startsWith($string,$search,$case=true){
    if ($case)
        return strpos($string, $search, 0) === 0;
    return stripos($string, $search, 0) === 0;
}

function mobile_number_is_valid($mob){
    if (!is_numeric($mob))
        return false;

    if (strlen($mob) != 11)
        return false;

    if (!startsWith($mob,"09"))
        return false;

    return true;
}

