@extends('layouts.main_layout')
  <!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
     <!-- iCheck -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"> Product Type Promotions</h3>

                </div>
                 {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                <!-- /.box-header -->
                <div class="box-body">

                <table class="table table-bordered">
                     <tr><th style="width: 10px"></th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Start Date</th>
                                 <th>End Date</th>
                                 <th>Discount %</th>
                   
                     <th style="width: 40px"></th>
                     </tr>
                    @if (count($productsPromotions) > 0)
                        @foreach($productsPromotions as $type)
                         <tr id="jobtitles-list">
                           <td nowrap>
                       <button type="button" id="edit_compan" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-category-modal" data-id="{{ $type->id }}" data-name="{{ $type->name }}" data-description="{{$type->description}}" ><i class="fa fa-pencil-square-o"></i> Edit</button></td>
                                    <td>{{ $type->name }}</td>
                                    <td>{{ $type->description }}</td>
                                    <td>{{ !empty($type->start_date) ? date('d M Y ', $type->start_date) : '' }}</td>
                                    <td>{{ !empty($type->end_date) ? date(' d M Y', $type->end_date) : '' }}</td>
                                    <td>{{ $type->discount }} %</td>
                          
                          <td nowrap>
                           <button type="button" id="view_ribbons" class="btn {{ (!empty($type->active) && $type->active == 1) ? " btn-danger " : "btn-success " }}
                                      btn-xs" onclick="postData({{$type->id}}, 'dactive');"><i class="fa {{ (!empty($type->active) && $type->active == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($type->active) && $type->active == 1) ? "De-Activate" : "Activate"}}</button>
                                    </td>
                                </tr>
                                   @endforeach
                               @else
                               <tr id="categories-list">
                        <td colspan="5">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                             No Promotions to display, please start by adding a new Promotions..
                        </div>
                        </td>
                        </tr>
                           @endif
                            </table>
                        </div>
                                   <!-- /.box-body -->
                   <div class="box-footer">
                     <button type="button" id="cat_module" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-promotion-modal">Add new Promotions </button>
                    </div>
             </div>
        </div>
   @include('products.partials.add_promotion_modal')
   @include('hr.partials.edit_category_modal')

</div>


@endsection

@section('page_script')
 <!-- Ajax form submit -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
     <!-- InputMask -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>

    <script>
        function postData(id , data ){
            if (data == 'qual') location.href = "/hr/addqul/" + id;
            else if (data == 'doc') location.href = "/hr/adddoc/" + id;
            else if (data == 'dactive') location.href = "/Product/category/" + id;
            else if (data == 'activateGroupLevel') location.href = '/hr/grouplevel/activate/' + id;
        }
         $(function () {
              $(".select2").select2();
              $('.temp-field').hide();
            var moduleId;
            //Tooltip
            $('[data-toggle="tooltip"]').tooltip();
            //Vertically center modals on page
            function reposition() {
                var modal = $(this),
                        dialog = modal.find('.modal-dialog');
                modal.css('display', 'block');

                // Dividing by two centers the modal exactly, but dividing by three
                // or four works better for larger screens.
                dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 2));
            }
            // Reposition when a modal is shown
            $('.modal').on('show.bs.modal', reposition);
            // Reposition when the window is resized
            $(window).on('resize', function() {
                $('.modal:visible').each(reposition);
            });
    
            //
             $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true
            });
             
             //Initialize iCheck/iRadio Elements
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });

                  $('#rdo_package, #rdo_product').on('ifChecked', function(){      
                var allType = hideFields();
                if (allType == 1) $('#box-subtitle').html('Site Address');
                else if (allType == 2) $('#box-subtitle').html('Temo Site Address');
            });

                       function hideFields() {
            var allType = $("input[name='promotion_type']:checked").val();
            if (allType == 1) { //adjsut leave
                $('.temp-field').hide();
                $('.site-field').show(); 
            }
            else if (allType == 2) { //resert leave
//                
                $('.site-field').hide();
                $('.temp-field').show();
            }

//          
            return allType;
           
        }
            

            //Post module form to server using ajax (ADD)
            $('#add_promotion').on('click', function() {
                //console.log('strUrl');
                var strUrl = '/Product/promotions/add';
                var modalID = 'add-promotion-modal';
                var objData = {
                    name: $('#'+modalID).find('#name').val(),
                    description: $('#'+modalID).find('#description').val(),
                    start_date: $('#'+modalID).find('#start_date').val(),
                    end_date: $('#'+modalID).find('#end_date').val(),
                    discount: $('#'+modalID).find('#discount').val(),
                    package: $('#'+modalID).find('#package').val(),
                    product: $('#'+modalID).find('#product').val(),
                    price: $('#'+modalID).find('#price').val(),
                    promotion_type: $('#'+modalID).find('input:checked[name = promotion_type]').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'cat_module';
                var redirectUrl = '/product/Promotions';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The group has been updated successfully.';
                //var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

              var doc_typeID;
            $('#edit-category-modal').on('show.bs.modal', function (e) {
                    //console.log('kjhsjs');
                var btnEdit = $(e.relatedTarget);
                doc_typeID = btnEdit.data('id');
                var name = btnEdit.data('name');
                var description = btnEdit.data('description');
                //var employeeName = btnEdit.data('employeename');
                var modal = $(this);
                modal.find('#name').val(name);
                modal.find('#description').val(description);

             });
            $('#edit_category').on('click', function () {
                var strUrl = '/Product/category_edit/' + doc_typeID;
                // Product/category_edit/{Category}
                var modalID = 'edit-category-modal';
                var objData = {
                    name: $('#'+modalID).find('#name').val(),
                    description: $('#'+modalID).find('#description').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val()
                };
                var submitBtnID = 'save_category';
                var redirectUrl = '/product/Categories';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'Category modal has been updated successfully.';
                var Method = 'PATCH';
         modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg, Method);
            });

        });
    </script>
@endsection

