<?php

namespace App\Http\Controllers\Users\Links;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Core\Link;
use App\Repositories\SearchRepo;
use Illuminate\Support\Facades\Schema;

class LinksController extends Controller
{
    
    /**
     * return link's index view
     */
    public function index(){
        return view($this->folder.'index',[

        ]);
    }


    /**
     * store link
     */
    public function storeLink(){
        request()->validate($this->getValidationFields());
        $data = \request()->all();
        if(!isset($data['user_id'])) {
                    if (Schema::hasColumn('links', 'user_id'))
                        $data['user_id'] = request()->user()->id;
                }
        $this->autoSaveModel($data);
        return redirect()->back();
    }

    /**
     * return link values
     */
    public function listLinks(){
        $links = Link::where([
            ['id','>',0]
        ]);
        if(\request('all'))
            return $links->get();
        return SearchRepo::of($links)
            ->addColumn('action',function($link){
                $str = '';
                $json = json_encode($link);
                $str.='<a href="#" data-model="'.htmlentities($json, ENT_QUOTES, 'UTF-8').'" onclick="prepareEdit(this,\'link_modal\');" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> Edit</a>';
                $str.='&nbsp;|&nbsp;<a href="#" onclick="deleteItem(\''.url(request()->user()->role.'/links/delete').'\',\''.$link->id.'\');" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</a>';
                return $str;
            })->make();
    }


    /**
     * delete link
     */
    public function destroyLink($link_id)
    {
        $link = Link::findOrFail($link_id);
        $link->delete();
        return redirect()->back()->with('notice',['type'=>'success','message'=>'Link deleted successfully']);
    }

}
