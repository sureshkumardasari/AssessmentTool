<?php namespace App\Modules;
use App\Services\ImageResolution;
class ServiceProvider extends  \Illuminate\Support\ServiceProvider
{

    public function boot()
    {
        $modules = config("module.modules");
        while (list(,$module) = each($modules)) {
            if(file_exists(__DIR__.'/'.$module.'/routes.php')) {
                include __DIR__.'/'.$module.'/routes.php';
            }
            if(is_dir(__DIR__.'/'.$module.'/Views')) {
                $this->loadViewsFrom(__DIR__.'/'.$module.'/Views', $module);
            }
        }
        \Validator::resolver(function($translator, $data, $rules, $messages){
            return new ImageResolution($translator, $data, $rules, $messages);
        });
    }

    public function register(){}

}