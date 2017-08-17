@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <form class="form-horizontal" method="POST" action="/quote/save-quote">
                    {{ csrf_field() }}
                    <input type="hidden" name="division_id" value="{{ $divisionID }}">
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
                                                <input type="number" class="form-control input-sm item-quantity" name="quantity[{{ $product->id }}]"
                                                       value="1" data-price="{{ ($product->productPrices && $product->productPrices->first())
                                                       ? $product->productPrices->first()->price : (($product->price) ? $product->price : '') }}"
                                                       onchange="subtotal()">
                                            </td>
                                            <td style="vertical-align: middle; text-align: right;">
                                                {{ ($product->productPrices && $product->productPrices->first())
                                                ? 'R ' . number_format($product->productPrices->first()->price, 2) : (($product->price)
                                                ? 'R ' . number_format($product->price, 2) : '') }}
                                            </td>
                                        </tr>
                                        <input type="hidden" name="price[{{ $product->id }}]"
                                               value="{{ ($product->productPrices && $product->productPrices->first())
                                                   ? $product->productPrices->first()->price : (($product->price) ? $product->price : '') }}">
                                        <?php $prevCategory = $product->category_id; ?>
                                    @endforeach
                                </table>

                                <!-- Total cost section -->
                                    <div class="col-sm-6 col-sm-offset-6 no-padding">
                                        <table class="table">
                                            <tr>
                                                <td></td>
                                                <th style="text-align: left;">Subtotal:</th>
                                                <td style="text-align: right;" id="subtotal" nowrap></td>
                                            </tr>
                                            <tr>
                                                <td style="width: 250px; vertical-align: middle;">
                                                    <div class="form-group no-margin{{ $errors->has('discount_percent') ? ' has-error' : '' }}">
                                                        <label for="{{ 'discount_percent' }}" class="col-sm-4 control-label">Discount</label>

                                                        <div class="col-sm-8">
                                                            <div class="input-group">
                                                                <div class="input-group-addon"><i class="fa fa-percent"></i></div>
                                                                <input type="number" class="form-control input-sm" id="discount_percent"
                                                                       name="discount_percent" placeholder="Discount"
                                                                       value="{{ old('discount_percent') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <th style="text-align: left; vertical-align: middle;">Discount:</th>
                                                <td style="text-align: right; vertical-align: middle;" id="discount-amount" nowrap></td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align: middle;">
                                                    <div class="form-group no-margin">
                                                        <label for="{{ 'discount_percent' }}" class="col-sm-4 control-label"></label>
                                                        <div class="col-sm-8">
                                                            <label class="radio-inline pull-right no-padding" style="padding-left: 0px;">Add VAT <input class="rdo-iCheck" type="checkbox" id="rdo_add_vat" name="add_vat" value="1"></label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <th style="text-align: left; vertical-align: middle;">VAT:</th>
                                                <td style="text-align: right; vertical-align: middle;" id="" nowrap></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <th style="text-align: left; vertical-align: middle;">Total:</th>
                                                <td style="text-align: right; vertical-align: middle;" id="" nowrap></td>
                                            </tr>
                                        </table>
                                    </div>
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
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>

    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();

            //Initialize iCheck/iRadio Elements
            $('.rdo-iCheck').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });

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

            //call the function to calculate the subtotal
            subtotal();
        });

        //function to calculate the subtotal
        function subtotal() {
            var subtotal = 0;
            //var discount = 0;
            $( ".item-quantity" ).each(function( index ) {
                //console.log( index + ": " + $( this ).data('price') );
                var qty = $( this ).val();
                var price = $( this ).data('price');
                subtotal += (qty * price);
                $( "#subtotal" ).html('R ' + subtotal.formatMoney(2));
            });
            //var discountPercent = $('#')
        }

        Number.prototype.formatMoney = function(c, d, t){
            var n = this,
                c = isNaN(c = Math.abs(c)) ? 2 : c,
                d = d == undefined ? "." : d,
                t = t == undefined ? "," : t,
                s = n < 0 ? "-" : "",
                i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
                j = (j = i.length) > 3 ? j % 3 : 0;
            return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
        };
    </script>
@endsection