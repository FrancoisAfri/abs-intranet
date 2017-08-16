@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <form class="form-horizontal" method="POST" action="/quote/adjust">
                    {{ csrf_field() }}
                    <input type="hidden" name="company_id" value="{{ $companyID }}">
                    <input type="hidden" name="contact_person_id" value="{{ $contactPersonId }}">
                    <div class="box-header with-border">
                        <h3 class="box-title">New Quote</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-ban"></i> Invalid Input Data!</h4>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                            <div style="overflow-x:auto;">
                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th style="text-align: right;">Unit Price</th>
                                    </tr>
                                    @foreach ($products as $product)
                                        @if($loop->first || (isset($prevCategory) && $prevCategory != $product->category_id))
                                            <?php $prevCategory = 0; ?>
                                            <tr>
                                                <th class="success" colspan="4" style="text-align: center;">
                                                    <i>{{ $product->ProductPackages->name }}</i>
                                                </th>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td style="vertical-align: middle;">{{ $loop->iteration }}</td>
                                            <td style="vertical-align: middle;">{{ $product->name }}</td>
                                            <td style="vertical-align: middle; width: 80px;">
                                                <input type="number" class="form-control input-sm" name="quantity[{{ $product->id }}]" value="1">
                                            </td>
                                            <td style="vertical-align: middle; text-align: right;">{{ ($product->productPrices && $product->productPrices->first()) ? 'R ' . number_format($product->productPrices->first()->price, 2) : (($product->price) ? 'R ' . number_format($product->price, 2) : '') }}</td>
                                        </tr>
                                        <?php $prevCategory = $product->category_id; ?>
                                    @endforeach
                                </table>
                            </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right">Send Quote</button>
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

    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>

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