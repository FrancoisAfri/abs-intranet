@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <!-- Search User Form -->
        <div class="col-md-8 col-md-offset-2">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-search pull-right"></i>
                    <h3 class="box-title">Search User</h3>
                    <p>Enter user details:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/users/search">
                    {{ csrf_field() }}

                    <div class="box-body">
                        <div class="form-group">
                            <label for="person_name" class="col-sm-3 control-label">Name</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" id="person_name" name="person_name" value="{{ old('person_name') }}" placeholder="Search by name...">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="id_number" class="col-sm-3 control-label">ID Number</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="number" class="form-control" id="id_number" name="id_number" value="{{ old('id_number') }}" placeholder="Search by ID number...">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="passport_number" class="col-sm-3 control-label">Passport Number</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="passport_number" name="passport_number" value="{{ old('passport_number') }}" placeholder="Search by passport number...">
                                </div>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="email" class="col-sm-3 control-label">Email</label>

                            <div class="col-sm-9">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-book"></i>
                                    </div>
                                    <input type="text" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Search by Email...">
                                </div>
                            </div>
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
		@if(Session('success_delete'))
            @include('contacts.partials.success_action', ['modal_title' => "User Deleted!", 'modal_content' => session('success_delete')])
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