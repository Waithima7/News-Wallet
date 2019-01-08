<?php

namespace App\Providers;

use App\Models\Core\Order;
use App\User;
use Illuminate\Support\ServiceProvider;
use Form;
use App\Repositories\FormRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Form::macro('autoForm', function($elements,$action,$classes = [],$model=null)
        {
            $model_form = null;
            if(!is_array($elements)){
                $model_form = $elements;
                $elements = new $elements();
                $elements = $elements->getfillable();
                $elements['form_model'] = $model_form;
            }
            $formRepository = new FormRepository();
            return $formRepository->autoGenerate($elements,$action,$classes,$model);
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}