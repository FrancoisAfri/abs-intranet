@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <!-- Search User Form -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-search pull-right"></i>
                    <h3 class="box-title">Qualifications</h3>
                    <p>Enter employee qualifications:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/hr/emp_qualifications">
                    {{ csrf_field() }}

                    <div class="box-body">
                     <div class="box-body">
                        <table class="table table-bordered"> 
                            <tr>
                                <th style="width: 5px; text-align: center;"></th>
                                <th>Institution</th>
                                <th>Qualification</th>
                                <th>Year Obtained</th>
                                <th>Qualification Type</th>
                                <th>Certificate</th>
                                <th style="width: 5px; text-align: center;"></th>
                            </tr>
            
                            @foreach ($qualifications as $type)
                                <tr>
                                    <td style=" text-align: center;" nowrap>
                                        <button type="button" id="edit_compan" class="btn btn-primary  btn-xs" data-toggle="modal" data-target="#edit-company-modal" data-id="{{ $type->id }}" data-name="{{ $type->name }}" data-manager_id="{{$type->manager_id}}" ><i class="fa fa-pencil-square-o"></i> Edit</button>
                                    </td>
                                    <td>{{ $type->name }}</td>
                                    <td>{{ ($type->manager) ? $type->manager->first_name." ".$type->manager->surname : ''}}</td>
                                    <td>
                                        
                                          <!--   <button type="button" id="view_ribbons" class="btn 11111111111111111111111{{ (!empty($type->active) && $type->active == 1) ? "btn-danger" : "btn-success" }} btn-xs" onclick="postData({{$type->id}}) , 'dactive';"><i class="fa {{ (!empty($type->active) && $type->active == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($type->active) && $type->active == 1) ? "De-Activate" : "Activate"}}</button> -->
                                    <button type="button" id="view_ribbons" class="btn {{ (!empty($type->active) && $type->active == 1) ? " btn-danger " : "btn-success " }}
                                      btn-xs" onclick="postData({{$type->id}}, 'dactive');"><i class="fa {{ (!empty($type->active) && $type->active == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($type->active) && $type->active == 1) ? "De-Activate" : "Activate"}}</button>
                                      
                                    </td>
                                </tr>    
                            @endforeach
                        </table>
                    </div>
         
                        <!-- /.box-body -->
                    <div class="box-footer">
                     <button type="button" id="level_module" class="btn btn-primary pull-right" data-toggle="modal" data-target="#level-module-modal">Add </button>  
                    </div>
        </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Search</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!-- End new User Form-->
        <!-- Confirmation Modal -->
        @if(Session('success_add'))
            @include('contacts.partials.success_action', ['modal_title' => "New User Added!", 'modal_content' => session('success_add')])
        @endif
    </div>
@endsection
@section('page_script')
    <script type="text/javascript">
        $(function () {
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

            //Show success action modal
            $('#success-action-modal').modal('show');
        });
    </script>
@endsection