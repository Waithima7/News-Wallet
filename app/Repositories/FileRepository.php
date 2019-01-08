<?php
/**
 * Created by PhpStorm.
 * User: iankibet
 * Date: 7/25/18
 * Time: 8:13 AM
 */

namespace App\Repositories;
use Storage;
use Carbon\Carbon;
class FileRepository
{
    public static function move($file,$public=false){
        try{
            $originan_name = $file->getClientOriginalName();
            $file_type = $file->getClientMimeType();
            $file_size = $file->getSize();
            $arr = explode('.',$originan_name);
            $ext = $arr[count($arr)-1];
            $file_name = str_slug(str_replace($ext,'',$originan_name)).'.'.$ext;
            if($public){
                $pre = 'public';
            }else{
                $pre = 'app';
            }
            $path = '/uploads/'.Carbon::now()->format('Y/m/d/H/i/s/');
            $new_path = $pre.$path;
            $new_name = str_random(3).'_'.time().'_'.$file_name;
            Storage::disk('local')->putFileAs($new_path,$file,$new_name);
            if($public)
                $pre = 'storage';
            return [
                'file_name'=>$originan_name,
                'file_size'=>$file_size,
                'path'=>$pre.$path.$new_name,
                'file_type'=>$file_type,
                'uploaded'=>true
            ];
        }catch(\Exception $e){
            return [
                'uploaded'=>false,
                'error'=>$e->getMessage()
            ];
        }
    }
}