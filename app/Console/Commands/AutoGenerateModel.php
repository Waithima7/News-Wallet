<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Artisan;
use Illuminate\Support\Facades\Storage;
use File;
class AutoGenerateModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autogenerate:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a model, migration, controller, route and view at the same time';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $real_model;
    protected $real_controller;
    protected $model_name;
    protected $fields;
    protected $plain_fields;
    protected $model_fields;
    protected $route_url;
    protected $controller_name;
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $model_name = $this->ask("What is the name of the model?");
        $this->model_name = $model_name;
        $model_namespace = $this->ask("What is the model namespace?");
        $real_model = $model_namespace."/".$model_name;
        $this->real_model = $real_model;
        $controller_name = $this->ask("What is the controller name?");
        $this->controller_name = $controller_name;
        $controller_namespace = $this->ask("What is the controller namespace?");
        $this->route_url = strtolower($controller_namespace);
        $this->real_controller = $controller_namespace.'/'.$controller_name;
        $plain_fields = [];
        $fields = [];
        $add_more = 1;
        while($add_more){
            $field_name = $this->ask("Add migration field, N to end");
            if(strtolower($field_name) == 'n'){
                $add_more = 0;
            }else{
                $field_type = $this->ask("What type of field is this (string,integer,char,date,dateTime,text,longText,json)?");
                $field_type = str_replace('datetime','dateTime',$field_type);
                $field_type = str_replace('longtext','longText',$field_type);
                $fields[] = [
                    'name'=>$field_name,
                    'type'=>$field_type
                ];
                $plain_fields[] = $field_name;
            }
        }
        $this->fields = $fields;
        $this->plain_fields = $plain_fields;
        $this->createModel();
        $this->updateMigrationFields();
        $this->createController();
        if(strtolower($this->ask('Do you want to create route Y or N ? ')) == 'y')
            $this->createRoute();
        if(strtolower($this->ask('Do you want to create view Y or N ? ')) == 'y')
           $this->createView();
        $this->info("Done!");
    }

    public function createModel(){
        Artisan::call("make:model",[
            'name'=>$this->real_model,
            '-m'=>true
        ]);
        $model_path = app_path($this->real_model.'.php');
        $model_content = file_get_contents($model_path);
        $model_array = explode('//',$model_content);
        $pre_model_content = $model_array[0];
        $post_model_content = $model_array[1];
        $this->model_fields = '"'.implode('","',$this->plain_fields).'"';
        $current_model_content = "\n\t".'protected $fillable = ['.$this->model_fields.'];'."\n";
        $new_model_contents = $pre_model_content.$current_model_content.$post_model_content;
        file_put_contents($model_path,$new_model_contents);
    }

    protected function updateMigrationFields(){
        $migration_dir = base_path('database/migrations');
        $migrations = scandir($migration_dir);
        $migration = $migration_dir.'/'.$migrations[count($migrations)-1];
        $migration_contents = file_get_contents($migration);
        $migration_arr = explode('$table->increments(\'id\');'."\n",$migration_contents);
        $pre_migration_content = $migration_arr[0];
        $after_migration_content = $migration_arr[1];
        $current_migration_content = '$table->increments(\'id\');'."\n";
        $fields = $this->fields;
        foreach($fields as $field){
            $current_migration_content.="\t\t\t".'$table->'.$field['type'].'(\''.$field['name'].'\');'."\n";
        }
        $new_migration_content = $pre_migration_content.$current_migration_content.$after_migration_content;
        file_put_contents($migration,$new_migration_content);
    }

    public function createController(){
        Artisan::call("make:controller",[
            'name'=>$this->real_controller
        ]);
        $path = app_path("Http/Controllers/".$this->real_controller.'.php');
        $content = file_get_contents($path);

        $new_content = file_get_contents(storage_path("app/templates/controller.txt"));
        $new_content = $this->replaceVars($new_content);
        $new_controller = str_replace('//',$new_content,$content);
        $use_content = 'use App\\'.str_replace('/','\\',$this->real_model).";\nuse App\Repositories\SearchRepo;\nuse Illuminate\Support\Facades\Schema;\n\nclass";
        $new_controller = $this->replace_first('class',$use_content,$new_controller);
        file_put_contents($path,$new_controller);
    }

    public function replaceVars($content){
        $model = strtolower($this->model_name);
        $umodel = $this->model_name;
        $models = str_plural($model);
        $umodels = str_plural($umodel);
        $new_content = str_replace('{model}',$model,$content);
        $new_content = str_replace('{cmodel}',strtoupper($model),$new_content);
        $new_content = str_replace('{models}',$models,$new_content);
        $new_content = str_replace('{umodel}',$umodel,$new_content);
        $new_content = str_replace('{umodels}',$umodels,$new_content);
        $new_content = str_replace('{route_url}',$this->route_url,$new_content);
        $new_content = str_replace('{controller}',$this->controller_name,$new_content);
        $new_content = str_replace('{model_fields}',$this->model_fields,$new_content);
        $new_content = str_replace('{model_namespace}',str_replace('/','\\',$this->real_model),$new_content);
        return $new_content;
    }

    public function createRoute(){
        $route_file = strtolower($this->route_url.'/'.str_plural($this->model_name)).'.route.php';
        $content = file_get_contents(storage_path("app/templates/route.txt"));
        $new_content = $this->replaceVars($content);
        $route_path = base_path("routes/".$route_file);
        $this->storeFile($route_path,$new_content);
    }

    public function createView(){
        $view_url = $this->ask("What is the view url? [$this->route_url]");
        if(!$view_url)
            $view_url = $this->route_url;
        $view_name = $this->ask("What is the view name? [index]?");
        if(!$view_name)
            $view_name = 'index';
        $view_file = strtolower($view_url.'/'.$view_name.'.blade.php');
        $content = file_get_contents(storage_path("app/templates/view.txt"));
        $new_content = $this->replaceVars($content);
        $view_path = base_path("resources/views/core/".$view_file);
        $this->storeFile($view_path,$new_content);
    }

    public function storeFile($original_path,$contents){
        $original_path = str_replace('\\','/',$original_path);
        $path_arr = explode("/",$original_path);
        unset($path_arr[count($path_arr)-1]);
        $dir = implode("/",$path_arr);
        exec("mkdir -p $dir");
        file_put_contents($original_path,$contents);
    }

    public function replace_first($find, $replace, $subject) {
        return implode($replace, explode($find, $subject, 2));
    }
}
