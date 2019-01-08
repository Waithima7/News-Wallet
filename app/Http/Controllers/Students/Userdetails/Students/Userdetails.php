<?php

namespace App\Http\Controllers\Students\Userdetails\Students;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Core\StudentDetails;
use App\Repositories\SearchRepo;

class Userdetails extends Controller
{
    
    /**
     * return studentdetails's index view
     */
    public function index(){
        return view($this->folder.'index',[

        ]);
    }


    /**
     * store studentdetails
     */
    public function storeStudentDetails(){
        request()->validate($this->getValidationFields());
        $data = \request()->all();
        if(!isset($data['user_id'])) {
                    if (Schema::hasColumn('studentdetails', 'user_id'))
                        $data['user_id'] = request()->user()->id;
                }
        $this->autoSaveModel($data);
        return redirect()->back();
    }

    /**
     * return studentdetails values
     */
    public function listStudentDetails(){
        $studentdetails = StudentDetails::where([
            ['id','>',0]
        ]);
        if(\request('all'))
            return $studentdetails->get();
        return SearchRepo::of($studentdetails)
            ->addColumn('action',function($studentdetails){
                $str = '';
                $json = json_encode($studentdetails);
                $str.='<a href="#" data-model="'.htmlentities($json, ENT_QUOTES, 'UTF-8').'" onclick="prepareEdit(this,\'studentdetails_modal\');" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> Edit</a>';
                $str.='&nbsp;|&nbsp;<a href="#" onclick="deleteItem(\''.url(request()->user()->role.'/studentdetails/delete').'\',\''.$studentdetails->id.'\');" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</a>';
                return $str;
            })->make();
    }


    /**
     * delete studentdetails
     */
    public function destroyStudentDetails($studentdetails_id)
    {
        $studentdetails = StudentDetails::findOrFail($studentdetails_id);
        $studentdetails->delete();
        return redirect()->back()->with('notice',['type'=>'success','message'=>'StudentDetails deleted successfully']);
    }

}
