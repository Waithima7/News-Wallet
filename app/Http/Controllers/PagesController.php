<?php

namespace App\Http\Controllers;

use App\Models\Core\Category;
use App\Models\Core\Link;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index(){
        $categories = Category::paginate(10);
        $links = Link::join('categories','categories.id','=','links.category_id')
                        ->where('links.id','>',0)
                        ->get();
        return view('welcome',
            [
                'categories'=>$categories,
                'links'=>$links
            ]);
    }

    public function viewcategory(){
        $categories = Category::paginate(10);
        $links = Link::join('categories','categories.id','=','links.category_id')
            ->where('links.category_id','=',\request('id'))
            ->get();
        return view('welcome',
            [
                'categories'=>$categories,
                'links'=>$links
            ]);
    }
}
