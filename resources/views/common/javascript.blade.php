<div class="bootstrap-iso">
    <div class="modal fade" role="dialog" id="status_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">Action Status<a data-dismiss="modal" class="pull-right btn-danger btn">&times;</a></div>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" role="dialog" id="delete_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title" style="left:40%">
                        <button class="btn btn-danger pull-right" data-dismiss="modal">&times;</button>
                        <h4>Are you sure?</h4>
                    </div>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal ajax-post" id="delete_form" action="" method="post">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <input type="hidden" name="delete_id">
                        <input type="hidden" name="delete_model">
                        <div class="form-group">
                            <label class="control-label col-md-5">&nbsp;</label>
                            <div class="col-md-5">
                                <button data-dismiss="modal" class="btn btn-danger">NO</button>
                                <button type="submit" class="btn btn-success">YES</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" role="dialog" id="run_action_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title" style="left:40%">
                        <button class="btn btn-danger btn-xs btn-raised pull-right" data-dismiss="modal">&times;</button>
                        <h4>Are you sure?</h4>
                    </div>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal ajax-post" id="run_action_form" action="" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="action_element_id">
                        <div class="form-group">
                            <label class="control-label col-md-5">&nbsp;</label>
                            <div class="col-md-5">
                                <button data-dismiss="modal" class="btn btn-danger btn-raised">NO</button>
                                <button type="submit" class="btn btn-success btn-raised">YES</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .autocomplete-suggestions{
        background-color: beige;
    }
    .centered {
        width: 100px;
        height: 100px;
    }

    .centered-container{
        display: -webkit-box;  /* OLD - iOS 6-, Safari 3.1-6, BB7 */
        display: -ms-flexbox;  /* TWEENER - IE 10 */
        display: -webkit-flex; /* NEW - Safari 6.1+. iOS 7.1+, BB10 */
        display: flex;         /* NEW, Spec - Firefox, Chrome, Opera */

        justify-content: center;
        align-items: center;
    }

    .titlecolumn th {
        background: whitesmoke;
        white-space: nowrap;
        text-align: right;
        font-weight: bold;
        /*width: 5%;*/
    }
</style>
<script type="text/javascript">
    var current_url = window.location.href;
    (function(window,undefined){
        if(typeof History != undefined){
            History.Adapter.bind(window,'statechange',function(){ // Note: We are using statechange instead of popstate
                var State = History.getState(); // Note: We are using History.getState() instead of event.state
                if(State.url != current_url){
                    ajaxLoad(State.url);
                }
            });
        }


    })(window);

    var form = null;
    jQuery(document).on('click','.is-invalid',function(){
        $(this).removeClass("is-invalid");
        $(this).closest(".invalid-feedback").remove();
    });
    jQuery(document).on('change','.is-invalid',function(){
        $(this).removeClass("is-invalid");
        $(this).closest(".invalid-feedback").remove();
    });
    jQuery(document).on('click','.form-group',function(){
        $(this).find('.help-block').remove();
        $(this).closest(".form-group").removeClass('is-invalid');
    });
    jQuery(document).on('click','.form-control',function(){
        $(this).find('.help-block').remove();
        $(this).closest(".form-group").removeClass('is-invalid');
    });
    jQuery(document).on('click','.clear-form',function(){
        resetForm('model_form_id');
    });

    jQuery(document).on('click','.load-page',function(){
        closeSidebar();
        $(".system-container").html('<img style="height:120px !important;" class="centered" src="{{ url("img/Ripple.gif") }}"/>');
        jQuery(".active").removeClass("active");
        jQuery(".loading-img").show();
        jQuery(".sb-site-container").trigger('click');
        jQuery(".profile-info").slideUp();
        var url = $(this).attr('href');
        $(this).closest('li').addClass("active");
        var status = 0;
        var material_active = $('input[name="material_page_loaded"]').val();
        if(!material_active){
            window.location.href = url;
        }
        $.get( url,null )
            .done(function( response ) {
                prepareAjaxUpload();
                jQuery(".loading-img").hide();
                current_url = url;
                if(response.redirect){
                    if(material_active == 1){
                        setTimeout(function(){
                            ajaxLoad(response.redirect);
                        },1300);
                    }else{
                        window.location.href = response.redirect;
                    }

                }
                $(".system-container").html(response);
                var title = $(".system-title").html();
               var res = History.pushState({state:1}, title, url);
                $(".mobile_title").html(title);
                prepareAjaxUpload();
                return false;
            })
            .fail(function(response){
                window.location.href = url;
            });
        return false;

    });
    jQuery(document).on('submit','.ajax-post',function(){
        var form = $(this);
        var btn = form.find(".submit-btn");
        btn.prepend('<img class="processing-submit-image" style="height: 50px;margin:-10px !important;" src="{{ url("img/Ripple.gif") }}">');
        btn.attr('disabled',true);
//            showLoading();
        this.form = form;
        $(".fg-line").removeClass('has-error');
        var url = $(this).attr('action');
        var data = $(this).serialize();
        var material_active = $('input[name="material_page_loaded"]').val();
        $.post(url,data).done(function(response,status){
            var btn = form.find(".submit-btn");
            btn.find('img').remove();
            btn.attr('disabled',false);
            endLoading(response);
            removeError();
            if(response.call_back){
                window[response.call_back](response);
                return false;
            }

            if(response.redirect){
                if(material_active == 1){
                    setTimeout(function(){
                        var s_class = undefined;
                        var params = getQueryParams(response.redirect);
                        if(params.ta_optimized){
                            s_class = 'bootstrap_table';
                        }else if(params.t_optimized){
                            s_class = 'ajax_tab_content';
                        }
                        return ajaxLoad(response.redirect,s_class);
                    },1300);
                }else{
                    window.location.href = response.redirect;
                }

            }else if(response.force_redirect){
                setTimeout(function(){
                    window.location.href= response.force_redirect;
                },1300);
            }
            else{
                return runAfterSubmit(response);
            }
        })
            .fail(function(xhr,status,error){
                var btn = form.find(".submit-btn");
                btn.find('img').remove();
                btn.attr('disabled',false);
                if(xhr.status == 422){
                    form.find('.alert_status').remove();
                    var response = JSON.parse(xhr.responseText).errors;
                    for(field in response){
                        $("input[name='"+field+"']").addClass('is-invalid');
                        $("input[name='"+field+"']").closest(".form-group").find('.help-block').remove();
                        $("input[name='"+field+"']").closest(".form-group").append('<small class="help-block invalid-feedback">'+response[field]+'</small>');

                        $("select[name='"+field+"']").addClass('is-invalid');
                        $("select[name='"+field+"']").closest(".form-group").find('.help-block').remove();
                        $("select[name='"+field+"']").closest(".form-group").append('<small class="help-block invalid-feedback">'+response[field]+'</small>');

                        $("textarea[name='"+field+"']").addClass('is-invalid');
                        $("textarea[name='"+field+"']").closest(".form-group").find('.help-block').remove();
                        $("textarea[name='"+field+"']").closest(".form-group").append('<small class="help-block invalid-feedback">'+response[field]+'</small>');
                    }

                    jQuery(".invalid-feedback").css('display','block');
                }else if(xhr.status == 406){
                    form.find('#form-exception').remove();
                    form.find('.alert_status').remove();
                    form.prepend('<div id="form-exception" class="alert alert-warning"><strong>'+xhr.status+'</strong> '+error+'<br/>'+xhr.responseText+'</div>');
                    // removeError();
                }else{
                    form.find('#form-exception').remove();
                    form.find('.alert_status').remove();
                    form.prepend('<div id="form-exception" class="alert alert-danger"><strong>'+xhr.status+'</strong> '+error+'<br/>('+url+')</div>');
                    // removeError();
                }

            });
        return false;
    });
    function getQueryParams(qs) {
        qs = qs.split('+').join(' ');

        var params = {},
            tokens,
            re = /[?&]?([^=]+)=([^&]*)/g;

        while (tokens = re.exec(qs)) {
            params[decodeURIComponent(tokens[1])] = decodeURIComponent(tokens[2]);
        }

        return params;
    }
    function gotoBottom(id){
        var element = document.getElementById(id);
        element.scrollTop = element.scrollHeight - element.clientHeight;
    }
    function ajaxLoad(url,s_class,active_tab){
        if(s_class){
            $("."+s_class).removeClass('centered-container');
            $("."+s_class).addClass('centered-container');
            $("."+s_class).html('<img style="height:120px !important;" class="centered" src="{{ url("img/Ripple.gif") }}"/>');
        }
        jQuery(".loading-img").show();
        var material_active = $('input[name="material_page_loaded"]').val();
        console.log(material_active);
        if(!material_active){
            window.location.href = url;
        }
        if(active_tab){
            setActiveTab(active_tab);
        }
        $.get( url,null )
            .done(function( response ) {
                jQuery(".loading-img").hide();
                if(s_class){
                    $("."+s_class).html(response);
                    $("."+s_class).removeClass('centered-container');
                }else{
                    $(".system-container").html(response);
                }
                var title = $(".system-title").html();
                url = url.replace('optimized','tab_optmized');
                if(!s_class){
                    History.pushState({state:1}, title, url);
                }else{
                    $("."+s_class).removeClass('centered-container');
                    return false;
                }
            })
            .fail(function(response){
                window.location.href = url;
            });
        prepareAjaxUpload();
        autoFillAllSelects();
        return false;
    }

    function closeSidebar(){

        $("body").removeClass("sidebar-toggled"), $(".ma-backdrop").remove(), $(".sidebar, .ma-trigger").removeClass("toggled");

    }
    function setActiveTab(tab){
        // alert(tab);
        jQuery(".auto-tab").removeClass('active');
        jQuery("."+tab).addClass('active');
    }
    function softError(element,reponse){

    }
    function removeError(){
        setTimeout(function(){
            $("#form-exception").fadeOut();
            $("#form-success").fadeOut();
            $(".alert_status").fadeOut();
        },1200);

    }

    function resetField(field,placeholder){
        setTimeout(function(){
            $("input[name='"+field+"']").attr('placeholder',placeholder);
            // $("input[name='"+field+"']").closest('fg-line').removeClass('has-error');
        },1300);
    }

    function hardError(element,response){

    }

    function validationErrors(form,errors){
        for(field in errors){
            alert(errors[field]);
        }
    }

    function showLoading(){
        $(".alert_status").remove();
        $('.ajax-post').not(".persistent-modal .modal-body").prepend('<div id="" class="alert alert-success alert_status"><img style="" class="loading_img" src="{{ URL::to("img/ajax-loader.gif") }}"></div>');
    }
    function endLoading(data){
        $(".alert_status").html('Success!');
        setTimeout(function(){


            if(data.id){

            }else{
                $(".modal").not(".persistent-modal").modal('hide');
            }
            $(".alert_status").slideUp();
//            $("#principal_file_modal").modal('show');
        },800);
    }


    function autofillForm(data){
        for(key in data){
            var in_type = $('input[name="'+key+'"]').attr('type');
            if(in_type != 'file'){
                $('input[name="'+key+'"]').val(data[key]);
                $('input[name="'+key+'"]').click();
                $('textarea[name="'+key+'"]').val(data[key]);
                $('textarea[name="'+key+'"]').click();
                $('select[name="'+key+'"]').val(data[key]);
                $('select[name="'+key+'"]').click();
            }
        }
        jQuery("input[name='id']").val(data['id']);
    }
    jQuery(document).on('click','.delete-btn',function(){
        var url = $(this).attr('href');
        deleteItem(url,null);
        return false;
    });
    function deleteItem(url,id,model){
//        $("#delete_modal").modal('show');
        $("input[name='delete_id']").val(id);
        $("input[name='delete_model']").val(model);
        $("#delete_form").attr('action',url);
        if(id)
            $("#delete_form").attr('action',url+'/'+id);

        swal({
            title: "Are you sure?",
            text: "A deleted Item can never be recovered!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel",
            closeOnConfirm: false,
            closeOnCancel: false
        }, function(isConfirm){
            if (isConfirm) {
                var url = $("#delete_form").attr('action');
                var data = $("#delete_form").serialize();
                $.post(url,data)
                    .done(function(response){
                        swal("Deleted!", "Item Deleted Successfully", "success");
                        if(response.redirect){
                            setTimeout(function(){
                                ajaxLoad(response.redirect);
                            },1300);

                        }else{
                            runAfterSubmit(response,url);
                        }
                    })
                    .fail(function(xhr,status,response){
                        swal("Error!", response, "error");
                    });

            } else {
                swal("Cancelled", "Your Item is safe :)", "error");
            }
        });

    }    function runSilentAction(url){
        $("#run_action_form").attr('action',url);
        var url = $("#run_action_form").attr('action');
        var data = $("#run_action_form").serialize();
        $.post(url,data)
            .done(function(response){
                if(response.redirect){
                    setTimeout(function(){
                        ajaxLoad(response.redirect);
                    },0);
                    return false;

                }else{
                    runAfterSubmit(response);
                    return false;
                }
            })
            .fail(function(xhr,status,response){
                alert(response);
            });
        return false;
    }
    function runPlainRequest(url,id){
        if(id != undefined && id != 0){
            // url = url+'/'+id;
        }
        $("input[name='action_element_id']").val(id);
        $("#run_action_form").attr('action',url);

        $("#run_action_form").attr('action',url);
        if(id)
            $("#run_action_form").attr('action',url+'/'+id);

        swal({
            title: "Are you sure?",
            text: '',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Proceed!",
            cancelButtonText: "No, cancel",
            closeOnConfirm: false,
            closeOnCancel: false
        }, function(isConfirm){
            if (isConfirm) {
                var url = $("#run_action_form").attr('action');
                var data = $("#run_action_form").serialize();
                $.post(url,data)
                    .done(function(response){
                        if(response.redirect){
                            swal("Success!", "Action Completed Successfully", "success");
                            setTimeout(function(){
                                ajaxLoad(response.redirect);
                            },1300);

                        }else{
                            swal("Success!", response, "success");
//                            runAfterSubmit(response);
                        }
                    })
                    .fail(function(xhr,status,response){
                        if(response == 'Not Acceptable'){
                            swal('Error!',xhr.responseText,'error');
                        }else{
                            swal("Error!", response, "error");
                        }

                    });

            } else {
                swal("Cancelled", "Action Cancelled by user", "error");
            }
        });
    }
    function reloadCsrf(){
    }

    function getEditItem(url,id,modal){
        var url = url+'/'+id;
        $.get(url,null,function(response){
            autofillForm(response);
            $("#"+modal).modal('show');
        });
    }

    function resetForm(id){
        $("."+id).find("input[type=text],textarea,select").val("");
        $("input[name='id']").val('');
//        runAfterReset();
    }

    function autoFillSelect(name,url,function_call){
//        $("select[name='"+name+"']").html('<option value="">Select</option>');
        $("select[name='"+name+"']").removeClass("is-invalid");
        $("select[name='"+name+"']").html('<option value="">Please Select</option>');
        $.get(url,null,function(response){
            if(response.length == 0){
                $("select[name='"+name+"']").attr('disabled',true);
            }else{
                $("select[name='"+name+"']").attr('disabled',false);
            }

            for(var i =0;i<response.length;i++){
                if(response[i].name){
                    $("select[name='"+name+"']").append('<option value="'+response[i].id+'">'+response[i].name+'</option>');
                }
                if(response[i].bank_name){
                    $("select[name='"+name+"']").append('<option value="'+response[i].id+'">'+response[i].bank_name+'</option>');
                }
                if(response[i].first_name){
                    $("select[name='"+name+"']").append('<option value="'+response[i].id+'">'+response[i].first_name+'</option>');
                }
            }
            if(function_call){
                window[function_call]();
            }
            if(response.length>20){
                jQuery('select[name="'+name+']').attr('class','form-group chosen-select');
                setTimeout(function(){
                    $(".chosen-select").chosen({disable_search_threshold: 10});
                    $(".chosen-select").trigger("chosen:updated");
                    $(".chosen-container").width('100%');
                },1000)
            }
        });
    }
    function setSelectData(name,data,first_null){
//        $("select[name='"+name+"']").html('');
        if(first_null){
            $("select[name='"+name+"']").html('<option value=""></option>');

        }

        for(var i =0;i<data.length;i++){
            if(data[i].name){
                $("select[name='"+name+"']").append('<option value="'+data[i].id+'">'+data[i].name+'</option>');
            }

        }
        setTimeout(function(){
            $(".chosen-select").chosen({disable_search_threshold: 10});
            $(".chosen-select").trigger("chosen:updated");
            $(".chosen-container").width('100%');
        },20)

    }

    function prepareEdit(element,modal){
        var data = $(element).data('model');
        autofillForm(data);
        $("#"+modal).modal('show');
    }

    function setAutoComplete(name,url){
        var formatted = [];
        $.get(url,null,function(response){
            for( var i=0;i<response.length;i++){
                var single = {value:response[i].name,data:response[i].name};
                formatted.push(single);
            }
            $("input[name='"+name+"']").autocomplete({
                lookup: formatted
            });
            console.log(formatted);
        });
    }
    $(document).ready(function() {
        prepareAjaxUpload();
        $('input[name="date_from"]').datetimepicker();
        $('input[name="deadline"]').datetimepicker();
        $('input[name="date_to"]').datetimepicker();
        $('input[name="date_of_birth"]').datetimepicker();

    });
    function prepareAjaxUpload(){

        $('input[name="date_from"]').datetimepicker();
        $('input[name="deadline"]').datetimepicker();
        $('input[name="date_to"]').datetimepicker();
        $('input[name="date_of_birt"]').datetimepicker();
        $('.datepicker').datetimepicker({
            timepicker:false
        });
        var form_url = $(".file-form").attr('action');
        var options = {
            target:        '#output1',   // target element(s) to be updated with server response
            beforeSubmit:  showRequest,  // pre-submit callback
            success:       fileUploadFinish,  // post-submit callback
            dataType:  'json',
            error:endWithError

        };
        autoFillAllSelects();
        $('.file-form').ajaxForm(options);
    }
    // pre-submit callback
    function showRequest(formData, jqForm, options) {
        var btn = jqForm.find(".submit-btn");
        btn.prepend('<img class="processing-submit-image" style="height: 50px;margin:-10px !important;" src="{{ url("img/Ripple.gif") }}">');
        btn.attr('disabled',true);
        $(".alert_status").remove();
        $('.file-form').prepend('<div id="" class="alert alert-success alert_status"><img style="" class="loading_img" src="{{ URL::to("img/ajax-loader.gif") }}"></div>');
    }

    function fileUploadFinish(response,status,xhr,jqForm){
        var btn = jqForm.find(".submit-btn");
        btn.find('img').remove();
        btn.attr('disabled',false);
        if(response.call_back){
            endLoading();
            window[response.call_back](response);
            return false;
        }
        if(response.id || response.image){
            $(".alert_status").remove();
            endLoading();
            runAfterSubmit(response);
        }else if(response.redirect){
            endLoading(response);
            setTimeout(function(){
                ajaxLoad(response.redirect);
            },1300);

        }else{
            endWithMinorErrors(response);
        }
    }
    function endWithError(xhr,tetet,error,jqForm){
        var btn = jqForm.find(".submit-btn");
        btn.find('img').remove();
        btn.attr('disabled',false);
        var error = xhr.statusText;
        response = xhr.responseText;
        response = JSON.parse(response).errors;
        if(xhr.status == 422){
            $('.alert_status').remove();
            for(field in response){
                $("input[name='"+field+"']").addClass('is-invalid');
                $("input[name='"+field+"']").closest(".form-group").find('.help-block').remove();
                $("input[name='"+field+"']").closest(".form-group").append('<small class="help-block invalid-feedback">'+response[field]+'</small>');

                $("select[name='"+field+"']").addClass('is-invalid');
                $("select[name='"+field+"']").closest(".form-group").find('.help-block').remove();
                $("select[name='"+field+"']").closest(".form-group").append('<small class="help-block invalid-feedback">'+response[field]+'</small>');

                $("textarea[name='"+field+"']").addClass('is-invalid');
                $("textarea[name='"+field+"']").closest(".form-group").find('.help-block').remove();
                $("textarea[name='"+field+"']").closest(".form-group").append('<small class="help-block invalid-feedback">'+response[field]+'</small>');

            }
        }else{
            $(".alert_status").remove();
            $('.file-form').prepend('<div id="" class="alert alert-danger alert_status"><strong>'+xhr.status+'</strong> '+error+'</div>');
            removeError();
        }
    }
    jQuery(document).ready(function(){
        $("input[name='start_time']").attr('data-mask','00:00:00');
        $("input[name='start_time']").addClass('input-mask');
        setInterval(function(){
//            getNotifications();
        },20000);
//        getNotifications();
    });
    function getNotifications(){

        var url = '{{ url('notifications') }}';
        jQuery.get(url,null,function(notifications){
            $(".notice_body").html('');
            $(".notice_count").html(notifications.length);
            for(var i=0;i<notifications.length;i++){
                var notification = notifications[i];
                $(".notice_body").append('<a class="load-page list-group-item media" href="'+notification.data.action+'">'+
                    '<div class="media-body">'+
                    '<small class="lgi-text">'+notification.data.message+'</small>'+
                    '</div></a>');
            }
        });
    }
    function readNotications(){
        var url = '{{ url("notifications/read-all") }}';
        jQuery.get(url);
        $(".notice_count").html(0);
    }

    function autoFillAllSelects(){
        var url = '{{ url(@Auth::user()->role.'/'.'autofill/data') }}';
        var data = [];
        $(".auto-fetch-select").each(function(){
            data.push($(this).attr('name')+':'+$(this).data("model"));
        });
        if(data.length > 0){
            $.get(url,{models:data},function(response){
                for(key in response){
                    setSelectData(key,response[key]);
                }
            });
        }
    }

    function deleteModel(id,model){
        var url = '{{ url(@Auth::user()->role.'/'.'delete/model') }}';
        return deleteItem(url,id,model);

    }
    @if(Auth::user())
    function loadTemplate(fn,data,id){
        var url = '{{ url(Auth::user()->role=='member' ? 'institution':Auth::user()->role) }}/templates/'+fn;
        $.get(url,data,function(response){
            $("#"+id).html(response);
        });
    }
    @endif
    function loadGeneralTemplate(fn,data,id){
        var url = '{{ url("general/templates") }}/'+fn;
        $.get(url,data,function(response){
            $("#"+id).html(response);
        });
    }
    $(document).ready(function(){
        var url = window.location.href;
        prepareAjaxUpload();

        // ajaxLoad(url);
    });

    function validateRemote(form_class,url){
        var data = $("."+form_class).serialize();
        $.post(url,data).done(function(response,status){
            return true;
        })
            .fail(function(xhr,status,error){
                if(xhr.status == 422){
                    var response = JSON.parse(xhr.responseText).errors;
                    for(field in response){
                        console.log(response);
                        $("input[name='"+field+"']").addClass('is-invalid');
                        $("input[name='"+field+"']").closest(".form-group").find('.help-block').remove();
                        $("input[name='"+field+"']").closest(".form-group").append('<small class="help-block invalid-feedback">'+response[field]+'</small>');

                        $("select[name='"+field+"']").addClass('is-invalid');
                        $("select[name='"+field+"']").closest(".form-group").find('.help-block').remove();
                        $("select[name='"+field+"']").closest(".form-group").append('<small class="help-block invalid-feedback">'+response[field]+'</small>');

                        $("textarea[name='"+field+"']").addClass('is-invalid');
                        $("textarea[name='"+field+"']").closest(".form-group").find('.help-block').remove();
                        $("textarea[name='"+field+"']").closest(".form-group").append('<small class="help-block invalid-feedback">'+response[field]+'</small>');
                    }
                }
            });
        return false;
    }

    function getTabCounts(url,data){
        $.get(url,data,function(response){
            for(key in response){
                $(".tab_badge_"+key).html(' <sup class="badge badge-info">'+response[key]+'</sup>')
            }
        });
    }
    window.intervals = [];
    function clearIntervals(){
        for(var i = 0; i<window.intervals.length;i++){
            clearInterval(window.intervals[i]);
        }

    }

    $(document).ready(function(){
        @if($notice = request()->session()->get('notice'))
        @if($notice['type'] == 'warning')
        toastr.warning('{{ $notice['message'] }}');
        @elseif($notice['type'] == 'info')
        toastr.info('{{ $notice['message'] }}');
        @elseif($notice['type'] == 'error')
        toastr.error('{{ $notice['message'] }}');
        @elseif($notice['type'] == 'success')
        toastr.success('{{ $notice['message'] }}');
        @endif
        @endif
        @if($alert = request()->session()->get('alert'))
        swal('{{ $alert['type'] }}','{{ $alert['message'] }}','{{ $alert['type'] }}');
        @endif
    });


</script>
<style type="text/css">
    .delete{
        color:red;
    }
</style>
