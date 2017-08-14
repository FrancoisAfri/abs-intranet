@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <form class="form-horizontal" method="POST" action="">
                    {{ csrf_field() }}
                    <div class="box-header with-border">
                        <h3 class="box-title">New Quote</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="form-group{{ $errors->has('company_id') ? ' has-error' : '' }}">
                            <label for="{{ 'company_id' }}" class="col-sm-2 control-label">Client Company</label>

                            <div class="col-sm-10">
                                <select id="company_id" name="company_id" class="form-control select2" style="width: 100%;">
                                    <option value="">*** Please Select a Company ***</option>
                                    <option value="">*** Please Select a Company ***</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ ($company->id == old('company_id')) ? 'selected' : '' }}>{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('contact_person_id') ? ' has-error' : '' }}">
                            <label for="{{ 'contact_person_id' }}" class="col-sm-2 control-label">Contact Person</label>

                            <div class="col-sm-10">
                                <select id="contact_person_id" name="contact_person_id" class="form-control select2" style="width: 100%;">
                                    <option value="">*** Please Select a Person ***</option>
                                    @foreach($contactPeople as $contactPerson)
                                        <option value="{{ $contactPerson->id }}" {{ ($contactPerson->id == old('contact_person_id')) ? 'selected' : '' }}>{{ $contactPerson->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr class="hr-text" data-content="SELECT PRODUCTS">

                        <div class="form-group{{ $errors->has('product_id') ? ' has-error' : '' }}">
                            <label for="{{ 'product_id' }}" class="col-sm-2 control-label">Products</label>

                            <div class="col-sm-10">
                                <select id="product_id" name="product_id[]" class="form-control select2" style="width: 100%;" multiple>
                                    <option value="">*** Please Select Some Products ***</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ ($product->id == old('product_id[]')) ? 'selected' : '' }}>{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr class="hr-text" data-content="OR SELECT PACKAGES">

                        <div class="form-group{{ $errors->has('package_id') ? ' has-error' : '' }}">
                            <label for="{{ 'package_id' }}" class="col-sm-2 control-label">Package</label>

                            <div class="col-sm-10">
                                <select id="package_id" name="package_id[]" class="form-control select2" style="width: 100%;" multiple>
                                    <option value="">*** Please Select a Package ***</option>
                                    @foreach($packages as $package)
                                        <option value="{{ $package->id }}" {{ ($package->id == old('package_id[]')) ? 'selected' : '' }}>{{ $package->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right">Create Quote</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
        </div>

        <!-- Include modal -->
        @if(Session('changes_saved'))
            @include('contacts.partials.success_action', ['modal_title' => "Users Access Updated!", 'modal_content' => session('changes_saved')])
        @endif
    </div>
@endsection

@section('page_script')
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>

    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();

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
            @if(Session('changes_saved'))
                $('#success-action-modal').modal('show');
            @endif
        });
    </script>
@endsection