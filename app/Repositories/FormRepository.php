<?php
/**
 * Created by PhpStorm.
 * User: ngugiwaithima
 * Date: 1/8/17
 * Time: 9:42 AM
 */

namespace App\Repositories;
use App\Models\Core\Settings\DynamicAttribute;
use URL;
use Auth;
class FormRepository
{
    public function autoGenerate($elements,$action=null,$classes = [],$model=null){
        $spoofed_method = '';
        $info = '';
        if(isset($model['id']) && @$model['id'] != 0)
            $info = '<div class="alert alert-info">Update Details</div>';

        if($model){
            if(!is_array($model)){
                $model = $model->toArray();
            }

            $action = $action.'/'.$model['id'];
            $spoofed_method = method_field('put');
        }
        $classes[] = 'ajax-post';
        $classes[] = 'model_form_id';


        $textareas = ['description','cancel_reason','reason','answer','html','instructions','comment',"testimonial",'about','address','postal_address','message','invoice_footer','security_credential'];
        $selects = [];
        $selects_val = [];
        $selects_val['inc_type'] = [
            'percentage'=>'percentage',
            'amount'=>'amount',
        ];
        $selects_val['category'] = [];
        $selects_val['parent_category'] = [];
        $selects_val['file_type'] = ['Order File','Additional Material','Sources List','Revision Material','other'];
        $selects_val['attribute_type'] = [
            'text'=>'Text',
            'textarea'=>'TextArea',
            'select'=>'Select',
            'multiselect'=>'MultiSelect',
        ];

        $selects_val['bid_enabled'] = [
            '0'=>'No',
            '1'=>'Yes',
        ];

        $passwords = ['password','password_confirmation'];
//            $selects[''] =
        $class = 'ajax-post';
        $enctype = '';
        $files = ['registration_file','image','file','icon','profile_pic','default_image','video_file'];
        foreach($files as $file){
            if(in_array($file,$elements)){
                $enctype = 'multipart/form-data';
                $classes = [];
                $enctype = 'multipart/form-data';
                $classes[] = 'model_form_id';
                $classes[] = "file-form";
                break;
            }
        }
        if(count($elements)>6){
            $classes[] = 'row';
        }
        $classes = implode(' ',$classes);
        $checkboxes = [];
        $checkboxes['days'] = ['Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday'];
        $checkboxes['downloadable'] = ['yes','no'];
        $checkboxes['amendment_type'] = ['Change ID details','Update Child Records','Update Spouse Records'];
//            $checkboxes['employer_type'] = ['Employed','Organized Groups','sponsored'];
        $form_string = '';
        $id='model_form_id';
//        if(isset($elements['form_model'])){
//            $action = @Auth::user()->role.'/core/model';
//        }
        $form_string.=$info.'<form id="" enctype="'.$enctype.'" class="'.$classes.'" method="post" action="'.URL::to($action).'">
           <input type="hidden" name="id" value="'.@$model['id'].'">
           <input type="hidden" name="entity_name">
           <input type="hidden" name="cur_search_value">
           <input type="hidden" name="cur_table_url">';
        if(isset($elements['form_model'])){
            $form_string.='<input type="hidden" name="form_model" value="'.$elements['form_model'].'">';
            unset($elements['form_model']);
        }
        $form_string.=$spoofed_method;
        $halve = round(count($elements)/2,0);
        if(count($elements)>6){
            $form_string.='<div class="col-md-5">';
        }
        $form_string.=csrf_field();
        $input_masks = [];
        $input_masks['start_time'] = '00:00:00';
        $no = 0;

        foreach($elements as $element_data=>$element){
            if(strpos($element_data,'hidden_') === false && strpos($element,'hidden_') === false){

            }else{
                if(strpos($element,'hidden_') === false){
                    $form_string.='<input type="hidden" name="'.str_replace('hidden_','',$element_data).'" value="'.$element.'">';
                    unset($elements[$element_data]);
                }else{
                    $form_string.='<input type="hidden" name="'.str_replace('hidden_','',$element).'" value="">';

                }
                continue;

            }
            $no++;
            $array = explode('_',$element);
            $form_string.='<div class="form-group '.$element.'">';
            $form_string.='<div class="fg-line">';
            $label_strings = str_replace('ipoooid','',$element);
            if($element == 'terms'){
                // $form_string.='<label class="fg-label control-label">'.ucwords(str_replace('_',' ','Accept temrs and conditions?')).'</label>';
            }else{
                $form_string.='<label class="fg-label control-label label_'.$element.'">'.ucwords(str_replace('_',' ',$label_strings)).'</label>';
            }


            if(in_array($element,$textareas)){
                $form_string.='<textarea name="'.$element.'" class="form-control">'.@$model[$element].'</textarea>';
            }elseif($element == 'terms'){
                $form_string.='<input name="'.$element.'" value="yes" type="checkbox" checked="true">';
                $form_string.='<label class="fg-label control-label">'.ucwords(str_replace('_',' ','I Accept terms and conditions')).'</label>';
            }
            elseif($array[count($array)-1]=='id' && isset($selects[$element]) == false && isset($selects_val[$element]) == false){
                $form_string.='<div class="select">';
                $data_model = '';
                $add_class = '';
                if(!is_integer($element_data)){
                    $data_model = ' data-model="'.$element_data.'" ';
                    $add_class = "auto-fetch-select";
                }

                $form_string.='<select '.$data_model.' name="'.$element.'" class="form-control '.$add_class.'">'.@$model[$element].'<option value="">Select...</a></select>';
                $form_string.='</div>';
            }elseif($array[count($array)-1]=='file'){
                $form_string.='<input type="file" name="'.$element.'" class="form-control">';
            }elseif(in_array($element,$files)){
                $form_string.='<input type="file" name="'.$element.'" class="form-control">';
            }elseif(in_array($element,$passwords)){
                $form_string.='<input type="password" name="'.$element.'" class="form-control">';
            }elseif(isset($selects[$element])){
                $form_string.='<div class="select">';
                $form_string.='<select name="'.$element.'" class="form-control">';
                $form_string.='<option selected value="">Select..</option>';
                foreach($selects[$element] as $option){
                    if(@$model[$element] == $option){
                        $form_string.='<option selected value="'.$option.'">'.ucwords(str_replace('_',' ',$option)).'</option>';
                    }else{
                        $form_string.='<option value="'.$option.'">'.ucwords(str_replace('_',' ',$option)).'</option>';
                    }
                }
                $form_string.='</select>';
                $form_string.='</div>';
            }elseif(isset($selects_val[$element])){
                $form_string.='<div class="select">';
                $form_string.='<select name="'.$element.'" class="form-control">';
                $form_string.='<option selected value="">Select..</option>';
                foreach($selects_val[$element] as $key=>$value){
                    if(@$model[$element] == $key){
                        $form_string.='<option selected value="'.$key.'">'.ucwords($value).'</option>';
                    }else{
                        $form_string.='<option value="'.$key.'">'.ucwords($value).'</option>';
                    }
                }
                $form_string.='</select>';
                $form_string.='</div>';
            }elseif(isset($checkboxes[$element])){
                $form_string.='<div class="checkboxes">';
                foreach($checkboxes[$element] as $checkbox){
                    if(@$model[$element] == $checkbox){
                        $form_string.='<input checked class="" type="checkbox" name="'.$element.'[]" value="'.strtolower(str_replace(' ','_',$checkbox)).'">'.ucwords($checkbox).'<br/>';
                    }else{
                        $form_string.='<input class="" type="checkbox" name="'.$element.'[]" value="'.strtolower(str_replace(' ','_',$checkbox)).'">'.ucwords($checkbox).'<br/>';
                    }
                }
                $form_string.='</div>';
            }
            else{
                if(isset($input_masks[$element])){
                    $form_string.='<input value="'.@$model[$element].'" type="text" data-mask="'.$input_masks[$element].'" name="'.$element.'" class="form-control input-mask">';

                }else{
                    $form_string.='<input value="'.@$model[$element].'" type="text" name="'.$element.'" class="form-control">';

                }
            }
            $form_string.='</div>';
            $form_string.='</div>';
            if(count($elements)>6){
                if($no == $halve || $no == $halve*2){
                    $form_string.='</div>';
                    $form_string.='<div class="col-md-5">';
                }
            }


        }
        if(count($elements)>6){
            $form_string.='</div>';
        }
        $form_string.='<div class="form-group">
<label class="control-label col-md-5">&nbsp;&nbsp;</label>
<div class="col-md-6">
<button type="submit" class="btn btn-primary btn-raised submit-btn"><i class="zmdi zmdi-save"></i> Submit</button>
</div>
</div>';
        $form_string.='</form>';
        return $form_string;
    }
}
