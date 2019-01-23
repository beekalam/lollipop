<?php
//date_default_timezone_set('Asia/Tehran');

$timestamp = time()+date("Z");
$current_time= gmdate("H",$timestamp).gmdate("i",$timestamp).gmdate("s",$timestamp);
$current_time_s= gmdate("H",$timestamp).':'.gmdate("i",$timestamp).':'.gmdate("s",$timestamp);

$g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
$j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
$j_month_name = array("", "Farvardin", "Ordibehesht", "Khordad", "Tir",
    "Mordad", "Shahrivar", "Mehr", "Aban", "Azar",
    "Dey", "Bahman", "Esfand");

function div($a, $b){

    return (int) ($a / $b);
}

function jalaliDay($year,$month,$day){


    list($Gyear, $Gmonth, $Gday) = JalaliToGregorian($year,$month,$day);

    $date = $Gyear .'/'. $Gmonth .'/'. $Gday;


    //echo $Gyear .'-'. $Gmonth .'-'. $Gday;
    //$date = '2007/10/31';
    // echo $date;
    $weekday = date('l', strtotime($date)); // note: first arg to date() is lower-case L
    if ($weekday == 'Tuesday') {$Jweekday = 'سه شنبه';}
    if ($weekday == 'Wednesday') {$Jweekday = 'چهارشنبه';}
    if ($weekday == 'Thursday') {$Jweekday = 'پنج شنبه';}
    if ($weekday == 'Friday') {$Jweekday = 'جمعه';}
    if ($weekday == 'Saturday') {$Jweekday = 'شنبه';}
    if ($weekday == 'Sunday') {$Jweekday = '&#1740;کشنبه';}
    if ($weekday == 'Monday') {$Jweekday = 'دوشنبه';}

    return $Jweekday; // SHOULD display Wednesday
}

function get_compare_dates(){ // this function uses jdf.php
    if (jdate('m') > 6)
        $days_in_month = 30;
    elseif (jdate('m') < 6)
        $days_in_month = 31;

    $compare_dates = array();
    $compare_dates["first_day_in_last_month"] = date("Y-m-d H:i:s", jmktime( 0, 0, 0, jdate('m') , 1, jdate('Y') )-(86400*$days_in_month) );
    $compare_dates["first_day_in_this_month"] = date("Y-m-d H:i:s", jmktime( 0, 0, 0, jdate('m') , 1, jdate('Y') ) );
    $compare_dates["current_day_in_this_month"] = date("Y-m-d H:i:s", jmktime( 0, 0, 0, jdate('m') , jdate('d'), jdate('Y') ) );
    $compare_dates["current_day_in_last_month"] = date("Y-m-d H:i:s", jmktime( 0, 0, 0, jdate('m') , jdate('d'), jdate('Y') )-(86400*$days_in_month) );
    return $compare_dates;
}

function convert_gregorian_iso_to_jalali_iso($g_iso){
    $year=substr($g_iso,0,4);
    $month=substr($g_iso,5,2);
    $day=substr($g_iso,8,2);
    list($jyear, $jmonth, $jday) = gregorian_to_jalali($year,$month,$day);
    return $jyear."-".$jmonth.'-'.$jday;
}

function convert_jalali_to_gregorian($date,$seperator='/'){
    list($year,$month,$day) = explode($seperator,$date);
    list($year,$month,$day)=JalaliToGregorian($year,$month,$day);
    if(strlen($day) == 1)
        $day = '0'.$day;
    if(strlen($month) == 1)
        $month = '0'.$month;
    return "{$year}-{$month}-{$day}";
}

function JalaliToGregorian($year,$month,$day){

    $gDaysInMonth =array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    $jDaysInMonth = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
    $jy=$year-979;
    $jm=$month-1;
    $jd=$day-1;
    $jDayNo=365*$jy + div($jy,33)*8 + div((($jy%33)+3),4);
    for ($i=0; $i < $jm; ++$i)
        $jDayNo += $jDaysInMonth[$i];
    $jDayNo +=$jd;
    $gDayNo=$jDayNo + 79;
    //146097=365*400 +400/4 - 400/100 +400/400
    $gy=1600+400*div($gDayNo,146097);
    $gDayNo = $gDayNo%146097;
    $leap=1;
    if($gDayNo >= 36525)
    {
        $gDayNo =$gDayNo-1;
        //36524 = 365*100 + 100/4 - 100/100
        $gy +=100* div($gDayNo,36524);
        $gDayNo=$gDayNo % 36524;

        if($gDayNo>=365)
            $gDayNo = $gDayNo+1;
        else
            $leap=0;
    }
    //1461 = 365*4 + 4/4
    $gy += 4*div($gDayNo,1461);
    $gDayNo %=1461;
    if($gDayNo>=366)
    {
        $leap=0;
        $gDayNo=$gDayNo-1;
        $gy += div($gDayNo,365);
        $gDayNo=$gDayNo %365;
    }
    $i=0;
    $tmp=0;
    while ($gDayNo>= ($gDaysInMonth[$i]+$tmp))
    {
        if($i==1 && $leap==1)
            $tmp=1;
        else
            $tmp=0;

        $gDayNo -= $gDaysInMonth[$i]+$tmp;
        $i=$i+1;
    }
    $gm=$i+1;
    $gd=$gDayNo+1;
    return array($gy, $gm, $gd);
}


function unix_timestamp_to_jalali($in)
{
    $date_gregorian = date("Y-m-d H:i:s", $in); 
    return convert_gregorian_iso_to_jalali_iso($date_gregorian) . substr($date_gregorian, 10,11);
}

function jalali_beginning_of_last_month($in=null)
{

    if(is_null($in))
        $in = time();

    list($date,$time)       = explode(" ",unix_timestamp_to_jalali($in));
    list($year,$month,$day) = explode("-",$date);
    $search = array();
    $replace= array();
    foreach(range(1,9) as $k=>$v){
        $replace[]  = strval($v);
        $search[] = '0'. $v;
    }

    $month = str_replace($search,$replace,$month);
    $day = 1;
    if(intval($month)==1){
        $year = intval($year)-1;
        $month = 12;
    }else{
        $month=intVal($month)-1;
    }

    return "{$year}-{$month}-{$day}";
}
function jalali_beginning_of_this_month()
{
    list($date,$time)       = explode(" ",unix_timestamp_to_jalali(time()));
    list($year,$month,$day) = explode("-",$date);
    $day="01";
    return "{$year}-{$month}-{$day}";
}

function jalali_end_of_this_month()
{
    list($date,$time) = explode(" ",unix_timestamp_to_jalali(time()));
    list($year,$month,$day) = explode("-",$date);
    $search = array();
    $replace= array();
    foreach(range(1,9) as $k=>$v){
        $replace[]  = strval($v);
        $search[] = '0'. $v;
    }

    $month = str_replace($search,$replace,$month);
    $month = intVal($month);

    if($month >=1 && $month <=6)
    {
        $day = 31;
    }else if($month >6 && $month <12){
        $day = 30;
    }

    //check leap year
    if($month == 12){
        foreach(array(28,29,30) as $v){
            if(jcheckdate($month,$v,$year)){
                $day = $v;
            }
        }
    }

    return "{$year}-{$month}-{$day}";    
}

function jalali_end_of_last_month($in=null)
{
    if(is_null($in))
        $in = time();
    list($date,$time) = explode(" ",unix_timestamp_to_jalali($in));
    list($year,$month,$day) = explode("-",$date);
    $search = array();
    $replace= array();
    foreach(range(1,9) as $k=>$v){
        $replace[]  = strval($v);
        $search[] = '0'. $v;
    }

    $month = str_replace($search,$replace,$month);
    $month = intVal($month);
    if(intval($month)==1){
        $year = intval($year)-1;
        $month = 12;
    }else{
        $month=intVal($month)-1;
    }

    if($month >=1 && $month <=6)
    {
        $day = 31;
    }else if($month >6 && $month <12){
        $day = 30;
    }

    //check leap year
    if($month == 12){
        foreach(array(28,29,30) as $v){
            if(jcheckdate($month,$v,$year)){
                $day = $v;
            }
        }
    }

    return "{$year}-{$month}-{$day}";
}


$year=gmdate("Y");
$month=gmdate("m");
$day=gmdate("d");

list($jyear, $jmonth, $jday) = gregorian_to_jalali($year,$month,$day);

$current_y=$jyear;
$current_m=$jmonth;
$current_d=$jday;
if (strlen($current_m) < 2) {$current_m = '0'.$current_m;}
if (strlen($current_d) < 2) {$current_d = '0'.$current_d;}

if ($current_d == 0) $current_d = 1;

//if ($current_m != 12) { $current_m=$current_m; } else { $current_m=1; $current_y=$current_y+1; }
if (Strlen($current_m) < 2) { $current_m='0'.$current_m; }
if (Strlen($current_d) < 2) { $current_d='0'.$current_d; }

$current_date=$current_y.$current_m.$current_d;
$current_date_s=$current_y.'/'.$current_m.'/'.$current_d;
global $current_date;

?>