<?php namespace Xiaozhuphp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;
/*全局JSON*/
use Maatwebsite\Excel\Facades\Excel;
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
    public function getLabel($request,$label=["name"],$append=[]){
        $arr = array();
        $lists = $this->modelWhere($request)->jsonWhere($request)->globalWhere($request)->modelJoin($request)->siteName($request)->where(function($query) use ($request){
                    })->get();
        foreach ($lists as $key => $value) {
            $value = json_encode($value);
            $value = json_decode($value,true);
            $arr[$key]["id"] = $value["id"];
            $arr[$key]["label"] = $value[$label];
            foreach ($append as $key2 => $value2) {
               $arr[$key][$key2] = $value[$value2];  
            }

            $arr[$key]["value"] = $value["id"];
        }
        return $arr;
    }
    public function getSum($request,$field="amount"){
          $res = $this->modelWhere($request)->jsonWhere($request)->globalWhere($request)->modelJoin($request)->siteName($request)->where(function($query) use ($request){
                    })->sum($field);
          return (float)$res;
    }
    public function getCount($request){
         $res = $this->modelWhere($request)->jsonWhere($request)->globalWhere($request)->modelJoin($request)->siteName($request)->where(function($query) use ($request){
                    })->count();
          return (int)$res;
    }
    public function getFirst($request=null,$field="id",$value){
         $res = $this->modelWhere($request)->jsonWhere($request)->globalWhere($request)->modelJoin($request)->siteName($request)->where(function($query) use ($request){
                    })->where($field,$value)->first();
          return $res;

    }
    public function getLists($request,$page=15,$field=[])
    {
        if($request->has('excel') && $request->excel){
            @ini_set('memory_limit', '1512M');
            $fields = json_decode($request->field);
            $keys = array();
            foreach ($fields as $key => $value) {
                $keys[] = $value->label;
            }
            $lists = $this->getAll($request,$page,$field);
            if (!count($lists))  dd("没数据");
            $filename = '导出列表';
            $data  = array();
            foreach ($lists as $key => $value) {
                $date = (string) ($value->created_at);
                $value  = json_encode($value);
                $value = json_decode($value,true);
                foreach ($fields as $key2 => $field) {
                    $field_ = explode('.',$field->prop);
                    if($field_[0] =="order_no"){
                        $value_ = "~".$value["order_no"];
                    }else{
                        $value_ = count($field_) == 1 ? $value[$field_[0]] : $value[$field_[0]][$field_[1]];
                    }
                    
                    $data[$key][] = $value_;
                }
            }
            $this->toExcel($data, $keys, $filename);
        }else{
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
           if($request->has('onlyTrashed')){
                $lists = $this->modelWhere($request)->onlyTrashed()->jsonWhere($request)->globalWhere($request)->modelJoin($request)->siteName($request)->where(function($query) use ($request){
                    })->paginate($page);
           }else{
             $lists = $this->modelWhere($request)->jsonWhere($request)->globalWhere($request)->modelJoin($request)->siteName($request)->where(function($query) use ($request){
                    })->paginate($page);
           }
          
          
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
            if($request->has('onlyTrashed')){
                $lists = $this->onlyTrashed()->modelWhere($request)->jsonWhere($request)->modelJoin($request)->globalWhere($request)->siteName($request)->where(function($query) use ($request){
                })->paginate($page);
            }else{
                $lists = $this->modelWhere($request)->jsonWhere($request)->modelJoin($request)->globalWhere($request)->siteName($request)->where(function($query) use ($request){
                })->paginate($page);
            }
             
        }
        return json_decode($lists->toJson());

        }
       
      
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
    public function toExcel($data, $title, $filename) {
        $GLOBALS['title'] = $title;
        Excel::create($filename, function ($excel) use ($filename, $data) {
            $excel->sheet($filename, function ($sheet) use ($data) {
                $sheet->row(1, $GLOBALS["title"]);
                $sheet->rows($data);
            });
        })->export('xls');
    }

     
  
}