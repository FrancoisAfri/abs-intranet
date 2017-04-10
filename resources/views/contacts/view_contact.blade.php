@extends('layouts.main_layout')

@section('page_dependencies')
        <!-- bootstrap datepicker -->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">

<!-- bootstrap file input -->
<link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="row">
        <!-- User Form -->
        <div class="col-md-8 col-md-offset-2">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-user pull-right"></i>
                    <h3 class="box-title">Client</h3>
                    <p>Client details:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/contacts/{{ $contact->id }}">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}

                    <div class="box-body">
                        
                            <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                <label for="first_name" class="col-sm-3 control-label">First Name</label>

                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $contact->first_name }}" placeholder="First Name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('surname') ? ' has-error' : '' }}">
                                <label for="surname" class="col-sm-3 control-label">Surname</label>

                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <input type="text" class="form-control" id="surname" name="surname" value="{{ $contact->surname }}" placeholder="Surname" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('contact_type') ? ' has-error' : '' }}">
                                <label for="representative" class="col-sm-3 control-label">Type of Contact</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <select class="form-control" name="contact_type" id="contact_type" placeholder="Select Contact Type">
                                            <option value="">*** Select a Type of Contact ***</option>
                                            @foreach($contact_types as $index => $value)
                                                <option value="{{ $index }}"{{ ($index == $contact->contact_type) ? ' selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('organization_type') ? ' has-error' : '' }}">
                                <label for="type_attendees" class="col-sm-3 control-label">Organization Type</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-suitcase"></i>
                                        </div>
                                        <select class="form-control" name="organization_type" id="organization_type" placeholder="Select Attendees" required>
                                            @foreach($org_types as $index => $value)
                                                <option value="{{ $index }}"{{ ($index == $contact->organization_type) ? ' selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-sm-3 control-label">Email</label>

                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-envelope"></i>
                                        </div>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ $contact->email }}" placeholder="Email">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('office_number') ? ' has-error' : '' }}">
                                <label for="office_number" class="col-sm-3 control-label">Office number</label>

                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-phone"></i>
                                        </div>
                                        <input type="tel" class="form-control" id="office_number" name="office_number" value="{{ $contact->office_number }}" data-inputmask='"mask": "(999) 999-9999"' placeholder="Office number" data-mask>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('cell_number') ? ' has-error' : '' }}">
                                <label for="cell_number" class="col-sm-3 control-label">Cell Number</label>

                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-mobile"></i>
                                        </div>
                                        <input type="tel" class="form-control" id="cell_number" name="cell_number" value="{{ $contact->cell_number }}" data-inputmask='"mask": "(999) 999-9999"' placeholder="Cell Number" data-mask>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('str_position') ? ' has-error' : '' }}">
                                <label for="position" class="col-sm-3 control-label">Position</label>

                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-black-tie"></i>
                                        </div>
                                        <input type="text" class="form-control" id="str_position" name="str_position" value="{{ $contact->str_position }}" placeholder="Position">
                                    </div>
                                </div>
                            </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="button" id="cancel" class="btn btn-default"><i class="fa fa-arrow-left"></i> Cancel</button>
                        <button type="submit" name="command" id="update" class="btn btn-primary pull-right"><i class="fa fa-upload"></i> Update</button>
				   </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->
        </div>
        <!-- End new User Form-->
        <!-- Confirmation Modal -->
        @if(Session('success_edit'))
            @include('contacts.partials.success_action', ['modal_title' => 'Contact Details Updated!', 'modal_content' => session('success_edit')])
        @elseif(Session('success_add'))
            @include('contacts.partials.success_action', ['modal_title' => 'Contact Added!', 'modal_content' => session('success_add')])
        @endif
    </div>
@endsection

@section('page_script')
            <!-- bootstrap datepicker -->
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>

    <!-- InputMask -->
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>

    <!-- Start Bootstrap File input -->
    <!-- canvas-to-blob.min.js is only needed if you wish to resize images before upload. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/canvas-to-blob.min.js" type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
    <!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files. This must be loaded before fileinput.min.js -->
    <script src="/bower_components/bootstrap_fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
    <!-- optionally if you need a theme like font awesome theme you can include it as mentioned below -->
    <script src="/bower_components/bootstrap_fileinput/themes/fa/theme.js"></script>
    <!-- optionally if you need translation for your language then include locale file as mentioned below -->
    <script src="/bower_components/bootstrap_fileinput/js/locales/<lang>.js"></script>
    <!-- End Bootstrap File input -->

    <script>
        $(function () {
            //Cancel button click event
            document.getElementById("cancel").onclick = function () {
                location.href = "{{ $back }}";
            };

            //Date picker
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                endDate: '-1d',
                autoclose: true
            });

            //Phone mask
            $("[data-mask]").inputmask();

            // [bootstrap file input] initialize with defaults
            $("#input-1").fileinput();
            // with plugin options
            //$("#input-id").fileinput({'showUpload':false, 'previewFileType':'any'});

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

            //Show success action modal
            $('#success-action-modal').modal('show');
        });
		function postData(id, data)
		{
			if (data == 'access')
				location.href = "/users/module_access/" + id;
		}
    </script>
@endsection