<?php namespace Xiaozhuphp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;
/*全局JSON*/
class XiaozhuJson extends Model
{
    /*追加属性去json开始*/
    
    public function getAttribute1Attribute(){
       return 1;
    }
    public function getAttribute2Attribute(){
       return 1;
    }
    public function find_($id){
        $isEx = Redis::hget(env("DB_DATABASE").'_'.$this->table,$id);
        if($isEx){
            return json_decode($isEx);
        }else{
           return $this->find($id);
        }
    }
    public function getAll($request,$page=5000,$field=[]){
        if(count($field) ==0){
             $lists = $this->modelWhere($request)->jsonWhere($request)->globalWhere($request)->modelJoin($request)->siteName($request)->where(function($query) use ($request){
                    })->take($page)->get();
        }else{
            $newField = array();
            foreach ($field as $key => $value) {
                $newField[] = $this->table.".".$value;
            }
             $lists = $this->modelWhere($request)->select($newField)->modelJoin($request)->globalWhere($request)->siteName($request)->where(function($query) use ($request){
              })->take($page)->get();
        }
        return $lists;

    }
    public function getSum($field="amount"){
          $res = $this->modelWhere($request)->jsonWhere($request)->globalWhere($request)->modelJoin($request)->siteName($request)->where(function($query) use ($request){
                    })->sum($field);
          return (float)$res;
    }
    public function getCount($request){
         $res = $this->modelWhere($request)->jsonWhere($request)->globalWhere($request)->modelJoin($request)->siteName($request)->where(function($query) use ($request){
                    })->count();
          return (int)$res;
    }
    public function getLists($request,$page=15,$field=[])
    {
        if(count($field) == 0){
            // $builder = $this->modelWhere($request)->globalWhere($request)->modelJoin($request)->siteName($request)->where(function($query) use ($request){
               
            // });
            // $bindings = $builder->getBindings();
            // $sql = str_replace('?', '%s', $builder->toSql());
            // $sql = sprintf($sql, ...$bindings);
            // $sql.= $request->has("page") ? "page:".$request->page : "page:1";
            // $sql = md5($sql);
            // $isEx = Redis::hget(env("DB_DATABASE").'_'.$this->table,$sql);
            // if($isEx){
            //     $lists = json_decode($isEx);
            //     \Log::info("cache");
            //     return $lists;
            // }else{
            //      $lists = $this->modelWhere($request)->globalWhere($request)->modelJoin($request)->siteName($request)->where(function($query) use ($request){
            //         })->paginate($page);
            //     Redis::hset(env("DB_DATABASE")."_".$this->table,$sql,json_encode($lists));

            // }
           $lists = $this->modelWhere($request)->jsonWhere($request)->globalWhere($request)->modelJoin($request)->siteName($request)->where(function($query) use ($request){
                    })->paginate($page);
          
        }else{

            $newField = array();
            foreach ($field as $key => $value) {
                $newField[] = $this->table.".".$value;
            }

            //  $builder = $this->modelWhere($request)->globalWhere($request)->select($newField)->modelJoin($request)->siteName($request)->where(function($query) use ($request){
               
            // });
            // $bindings = $builder->getBindings();
            // $sql = str_replace('?', '%s', $builder->toSql());
            // $sql = sprintf($sql, ...$bindings);
            // $sql.= $request->has("page") ? "page:".$request->page : "page:1";
            // $sql = md5($sql);
            // $isEx = Redis::hget(env("DB_DATABASE").'_'.$this->table,$sql);
            // if($isEx){
            //     $lists = json_decode($isEx);
            //     \Log::info("cache");
            //     return $lists;
            // }else{
            //      $lists = $this->modelWhere($request)->select($newField)->modelJoin($request)->globalWhere($request)->siteName($request)->where(function($query) use ($request){
              
            //     })->paginate($page);
            //     Redis::hset(env("DB_DATABASE")."_".$this->table,$sql,json_encode($lists));
            // }
            $request["field"] = $newField;
             $lists = $this->modelWhere($request)->modelJoin($request)->globalWhere($request)->siteName($request)->where(function($query) use ($request){
              })->paginate($page);
        }
        return json_decode($lists->toJson());
      
    }

     public function scopeSiteName($query,$request=null)
    {
        if($request && !$request->has("searchAll"))  return $query->where($this->table.'.site_name',getSiteName());
    }
       

    /*常用查询语句*/
    public function scopeGlobalWhere($query,$request)
    {
        if($request && $request->has('groupBy'))  $query->groupBy($request->groupBy);
        if($request->has('orderByRaw')){
             $query->orderByRaw($request->orderByRaw)->orderBy($this->table.".id", "desc");
        }else{
            if($request->has("orderBy") && $request->orderBy){
                 $query->orderBy($this->table.".".$request->orderBy, "desc");
            }
           
        }
      if ($request->has('created_at_start'))  $query->where($this->table.'.created_at', '>=', $request->created_at_start." 00:00:00");
      if ($request->has('created_at_end')) $query->where($this->table.'.created_at', '<=', $request->created_at_end." 23:59:59");
      if($request->has("searchType") && $request->searchType){
           $date = searchDate($request->searchType);
           $query->where($this->table.'.created_at', '>=', $date["start_date"])->where($this->table.'.created_at', '<=', $date["end_date"]);
      }

        return $query;
    }

     
  
}