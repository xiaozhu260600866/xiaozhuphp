<?php

function searchDate($searchType) {
	if ($searchType == "today") {
		$start_date = date('Y-m-d') . " 00:00:00";
		$end_date = date('Y-m-d') . " 23:59:59";
	} elseif ($searchType == "yesterday") {
		$start_date = date('Y-m-d', strtotime("-1 day")) . " 00:00:00";
		$end_date = date('Y-m-d', strtotime("-1 day")) . " 23:59:59";
	} elseif ($searchType == "thisyear") {
		$start_date = date('Y-01-01') . " 00:00:00";
		$end_date = date('Y-12-31') . " 23:59:59";
	} elseif ($searchType == "threeyear") {
		$start_date = date('Y-m-d', strtotime("-9001 day")) . " 00:00:00";
		$end_date = date("Y-m-d H:i:s");
	} elseif ($searchType == "thismonth") {
		$start_date = date('Y-m-01') . " 00:00:00";
		$end_date = date('Y-m-d', strtotime("$start_date +1 month -1 day")) . " 23:59:59";
	}
	return array("start_date" => $start_date, "end_date" => $end_date);
}
function text($res) {
	return "xiaozhu";
}
function  del($tablename,$id){
	 if($tablename == "user_address"){
	 	 	$tablename = "\App\\UserAddress";
	 	}else{
		   if(strpos($tablename, "_") !==false){
	           $tablenameArr= explode("_",$tablename);
	           $tablename = '';
	           foreach ($tablenameArr as $key => $value) {
	               $tablename .= ucwords($value);
	           }
	        }else{
	            $tablename = ucwords($tablename);
	      }
	      $tablename = "\App\\".trim($tablename,"s");
	}
    
  	
    $res = $tablename::where("id", $id)->first();
   if($res){
        $res->delete();
        return true;
   }else{
      return false;
   }
}
function getModel($model,$id,$field=array(),$attribute=true){
    if($id){

       $model = "\App\\".$model;
       if(count($field)){
          $res = $model::where('id',$id)->select($field)->first();
       }else{
          $res = $model::where('id',$id)->first();
       }
     
       if(!$attribute && $res){
            $res = $res->toArray();
            $newRes = array();
            foreach ($res as $key => $value) {
                if(in_array($key,$field)){
                    $newRes[$key] = $value;
                }
            }
           $res = $newRes;
       }
        return $res;
    }
  

}

function getUserInfo($user_id) {
	$user = \App\User::leftJoin("user_infos", "users.id", "=", "user_infos.user_id")->select(['users.headimgurl', 'users.nickname', 'users.id', "user_infos.phone", "user_infos.name", "user_infos.address", "user_infos.company_name"])->where("users.id", $user_id)->first();
	if ($user) {
		$res = array(
			"id" => $user->id,
			"headimgurl" => $user->headimgurl,
			"headerPic" => $user->headerPic,
			"nickname" => $user->nickname,
			"userInfo" => array(
				"company_name" => $user->company_name,
				"phone" => $user->phone,
				"name" => $user->name,
				"address" => $user->address,
			),

		);

	} else {
		$res = array(
			"id" => '已被删除',
			"headimgurl" => '已被删除',
			"nickname" => '已被删除',
			"userInfo" => array(
				"company_name" => '已被删除',
				"phone" => '已被删除',
				"name" => '已被删除',
				"address" => '已被删除',
			),

		);

	}

	return $res;
}
