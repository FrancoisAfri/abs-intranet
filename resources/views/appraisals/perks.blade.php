@extends('layouts.main_layout')

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
                <div class="box-body">
                    <ul class="products-list product-list-in-box">
                        <li class="item">
                            <div class="product-img">
                                <img src="http://placehold.it/50x50" alt="Product Image">
                            </div>
                            <div class="product-info">
                                <a href="javascript:void(0)" class="product-title">Samsung TV
                                    <span class="label label-warning pull-right">75%</span></a>
                                <span class="product-description">
                                  Samsung 32" 1080p 60Hz LED Smart HDTV.
                                </span>
                            </div>
                        </li>
                        <!-- /.item -->
                        <li class="item">
                            <div class="product-img">
                                <img src="http://placehold.it/50x50" alt="Product Image">
                            </div>
                            <div class="product-info">
                                <a href="javascript:void(0)" class="product-title">Play Station 4
                                    <span class="label label-warning pull-right">81%</span></a>
                                <span class="product-description">
                                  Play Station 4 Slim Console plus 2 DualShock 4 Controllers.
                                </span>
                            </div>
                        </li>
                        <!-- /.item -->
                    </ul>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="button" id="add-new-template" class="btn btn-primary pull-right" data-toggle="modal" data-target="#add-new-template-modal">Add New Perk</button>
                </div>
            </div>
        </div>

        <!-- Include add new prime rate modal -->
    </div>
@endsection

@section('page_script')
    <script>

        $(function () {
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

            //pass category data to the edit category modal
            $('#edit-template-modal').on('show.bs.modal', function (e) {
                var btnEdit = $(e.relatedTarget);
                templateId = btnEdit.data('id');
                var templateName = btnEdit.data('template');
                var jobTitleId = btnEdit.data('job_title_id');
                var modal = $(this);
                modal.find('#template').val(templateName);
                modal.find('#job_title_id').val(jobTitleId);
                $('select#job_title_id').val(jobTitleId);

            });

            //function to post category form to server using ajax
            function postModuleForm(formMethod, postUrl, formName) {

            }

        });
    </script>
@endsection