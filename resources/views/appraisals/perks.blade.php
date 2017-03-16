@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Available Perks</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body"><!-- style="max-height: 200px; overflow-y: scroll;" -->
                    @if(count($perks) > 0)
                        <ul class="products-list product-list-in-box">
                            @foreach($perks as $perk)
                                <li class="item">
                                    <div class="product-img">
                                        <img src="{{ (!empty($perk->img)) ? Storage::disk('local')->url("perks/$perk->img") : 'http://placehold.it/50x50' }}" alt="Product Image">
                                    </div>
                                    <div class="product-info">
                                        <a href="#" class="product-title" data-toggle="modal" data-target="#edit-perk-modal" data-id="{{ $perk->id }}" data-name="{{ $perk->name }}" data-description="{{ $perk->description }}" data-req_percent="{{ $perk->req_percent }}" data-img="{{ (!empty($perk->img)) ? Storage::disk('local')->url("perks/$perk->img") : 'http://placehold.it/235x235' }}">{{ $perk->name }}</a>
                                        <span class="pull-right" style="margin-left: 15px;">
                                            <button type="button" id="view_ribbons" class="btn {{ (!empty($perk->status) && $perk->status == 1) ? "btn-danger" : "btn-success" }} btn-xs btn-flat" onclick="activatePerk({{ $perk->id }});"><i class="fa {{ (!empty($perk->status) && $perk->status == 1) ? "fa-times" : "fa-check" }}"></i> {{(!empty($perk->status) && $perk->status == 1) ? "De-Activate" : "Activate"}}</button>
                                        </span>
                                        <span class="label label-success pull-right"><i class="fa fa-star"></i> {{ $perk->req_percent }}%</span>
                                        <span class="product-description">
                                            {{ $perk->description }}
                                        </span>
                                    </div>
                                </li>
                                <!-- /.item -->
                            @endforeach
                        </ul>
                    @else
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-warning"></i> No Records Found!</h4>
                            There is no perks to show. Please add some perks by clicking on the 'Add New Perk' button below.
                        </div>
                    @endif
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="button" id="add-new-perk" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-new-perk-modal"><i class="fa fa-plus-circle"></i> Add New Perk</button>
                </div>
            </div>
        </div>

        <!-- Include add new prime rate modal -->
        @include('appraisals.partials.add_perk')
        @include('appraisals.partials.edit_perk')
    </div>
@endsection

@section('page_script')
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
    <!-- optionally if you need translation for your language then include locale file as mentioned below
    <script src="/bower_components/bootstrap_fileinput/js/locales/<lang>.js"></script>-->
    <!-- End Bootstrap File input -->

    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>

    <script>
        function activatePerk(perkID) {
            location.href= "/appraisal/perks/" + perkID + "/activate";
        }
        $(function () {
            //Tooltip
            //$('[data-toggle="tooltip"]').tooltip();

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

            //Post perk form to server using ajax (add)
            $('#add-perk').on('click', function() {
                var strUrl = '/appraisal/perks/new';
                var formName = 'add-new-perk-form';
                var modalID = 'add-new-perk-modal';
                //var modal = $('#'+modalID);
                var submitBtnID = 'add-perk';
                var redirectUrl = '/appraisal/perks';
                var successMsgTitle = 'Perk Added!';
                var successMsg = 'The new perk has been added successfully!';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            //Post perk form to server using ajax (edit)
            $('#update-perk').on('click', function() {
                var strUrl = '/appraisal/perks/' + perkID;
                var formName = 'edit-perk-form';
                var modalID = 'edit-perk-modal';
                var submitBtnID = 'update-perk';
                var redirectUrl = '/appraisal/perks';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The perk details have been updated successfully!';
                modalFormDataSubmit(strUrl, formName, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            //pass perk data to the edit perk modal
            var perkID;
            $('#edit-perk-modal').on('show.bs.modal', function (e) {
                var btnEdit = $(e.relatedTarget);
                perkID = btnEdit.data('id');
                var name = btnEdit.data('name');
                var desc = btnEdit.data('description');
                var percent = btnEdit.data('req_percent');
                var perkImg = btnEdit.data('img');
                var modal = $(this);
                modal.find('#name').val(name);
                modal.find('#description').val(desc);
                modal.find('#req_percent').val(percent);
                //show perk image if any
                var imgDiv = modal.find('#perk-img');
                imgDiv.empty();
                var htmlImg = $("<img>").attr('src', perkImg).attr('class', 'img-responsive img-thumbnail').attr('style', 'max-height: 235px;');
                imgDiv.html(htmlImg);
            });
        });
    </script>
@endsection
