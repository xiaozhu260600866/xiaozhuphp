<?php

function searchDate($searchType){
    if($searchType == "today"){
        $start_date = date('Y-m-d')." 00:00:00";
        $end_date = date('Y-m-d'). " 23:59:59";
    }elseif($searchType == "yesterday"){
        $start_date = date('Y-m-d',strtotime("-1 day"))." 00:00:00";
        $end_date = date('Y-m-d',strtotime("-1 day")). " 23:59:59";;
    }elseif($searchType == "thisyear"){
        $start_date = date('Y-01-01')." 00:00:00";
        $end_date = date('Y-12-31'). " 23:59:59";
    }elseif($searchType == "threeyear"){
        $start_date = date('Y-m-d',strtotime("-9001 day"))." 00:00:00";
        $end_date = date("Y-m-d H:i:s");
    }elseif($searchType == "thismonth"){
        $start_date = date('Y-m-01')." 00:00:00";
        $end_date = date('Y-m-d',strtotime("$start_date +1 month -1 day")). " 23:59:59";;
    }
    return array("start_date"=>$start_date,"end_date"=>$end_date);
}
function text($res){
    return $res;
}





