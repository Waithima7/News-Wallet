<?php

namespace App\Http\Controllers\Users\Categories;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Core\Category;
use App\Repositories\SearchRepo;
use Illuminate\Support\Facades\Schema;

class CategoriesController extends Controller
{
    
    /**
     * return category's index view
     */
    public function index(){
        return view($this->folder.'index',[

        ]);
    }


    /**
     * store category
     */
    public function storeCategory(){
        request()->validate($this->getValidationFields());
        $data = \request()->all();
        if(!isset($data['user_id'])) {
                    if (Schema::hasColumn('categories', 'user_id'))
                        $data['user_id'] = request()->user()->id;
                }
        $this->autoSaveModel($data);
        return redirect()->back();
    }

    /**
     * return category values
     */
    public function listCategories(){
        $categories = Category::where([
            ['id','>',0]
        ])->where('user_id','=',\request()->user()->id);
        if(\request('all'))
            return $categories->get();
        return SearchRepo::of($categories)
            ->addColumn('action',function($category){
                $str = '';
                $json = json_encode($category);
                $str.='<a href="#" data-model="'.htmlentities($json, ENT_QUOTES, 'UTF-8').'" onclick="prepareEdit(this,\'category_modal\');" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> Edit</a>';
                $str.='&nbsp;|&nbsp;<a href="#" onclick="deleteItem(\''.url(request()->user()->role.'/categories/delete').'\',\''.$category->id.'\');" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</a>';
                return $str;
            })->make();
    }


    /**
     * delete category
     */
    public function destroyCategory($category_id)
    {
        $category = Category::findOrFail($category_id);
        $category->delete();
        return redirect()->back()->with('notice',['type'=>'success','message'=>'Category deleted successfully']);
    }

}
