<?php
namespace XiaozhuPhp\Facades;
use Illuminate\Support\Facades\Facade;
class App2 extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'app2';
    }
}
