<?php
namespace ABR;

final class Init
{
    public $plugin;
    public static function get_services()
    {
        return [
            Base\Constant::class,
            Base\ABR_Enqueue::class,
            Pages\Admin::class,
            Base\order_metabox::class,
            Controller\GetToken::class,
            Api\AddColumn::class,
        ];
    }

    public static function register_services()
    {
        foreach (self::get_services() as $class){
            $service=self::instantiate($class);

            if (method_exists($service,'register')){
                $service->register();
            }
        }
    }

    private static function instantiate($class)
    {
        $service= new $class();
        return $service;
    }

}
