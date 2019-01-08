<?php

namespace App\Http\Controllers;

use App\Repositories\ModelSaverRepository;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $folder = "";
    function __construct()
    {
        $class = get_class($this);
        $class = str_replace('App\\Http\\Controllers\\',"",$class);
        $arr = explode('\\',$class);
        unset($arr[count($arr)-1]);
        $folder = implode('.',$arr).'.';
        $this->folder = 'core.'.strtolower($folder);
    }
    function saveModel($data){
        $model_saver = New ModelSaverRepository();
        $model = $model_saver->saveModel($data);
        return $model;
    }
    function autoSaveModel($data){
        $model_saver = New ModelSaverRepository();
        $model = $model_saver->saveModel($data);
        return $model;
    }
    function getValidationFields($fillables = null){
        $data = request()->all();
        $model = new $data['form_model']();

        if ($fillables)
            $fillables = $fillables;
        else
            $fillables = $model->getFillable();
        $validation_array =  [];
        foreach($fillables as $field){
            $validation_array[$field] = 'required';
        }
        $validation_array['id']='';
        $validation_array['form_model']='';
        return $validation_array;
    }

}
