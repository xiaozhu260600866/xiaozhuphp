<?php 
namespace XiaozhuPhp\admin;
use Illuminate\Support\Facades\Redis;
use App\Http\Requests;


class app 
{
   public function __construct($config)
    {
        
        $this->config = $config;
    }
    public function updateRedisAll($table_name,$model=null,$type=null){
       $lists = Redis::hgetall(env("DB_DATABASE").'_'.$table_name);
        foreach ($lists as $key => $value) {
           Redis::hdel(env("DB_DATABASE").'_'.$table_name,$key);
        }
        if($model && !$type){
           Redis::hset(env("DB_DATABASE")."_".$table_name,$model->id,json_encode($model));
        }else{
           Redis::hdel(env("DB_DATABASE").'_'.$table_name,$model->id);
        }

    }


}

?>