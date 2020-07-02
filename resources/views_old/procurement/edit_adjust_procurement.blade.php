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
                <form class="form-horizontal" method="POST" action="/procuremnt/update/{{ $procurement->id }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="item_type" id="item_type" value="{{ $itemType }}">
                    <input type="hidden" name="title_name" id="title_name" value="{{ $title_name }}">
                    <input type="hidden" name="employee_id" id="employee_id" value="{{ $employee_id }}">
                    <input type="hidden" name="on_behalf" value="{{ $on_behalf }}">
                    <input type="hidden" name="on_behalf_employee_id" value="{{ $on_behalf_employee_id }}">
                    <input type="hidden" name="special_instructions" value="{{ $special_instructions }}">
                    <input type="hidden" name="justification_of_expenditure" value="{{ $justification_of_expenditure }}">
                    <input type="hidden" name="detail_of_expenditure" value="{{ $detail_of_expenditure }}">
                    <input type="hidden" name="delivery_type" value="{{ $delivery_type }}">
                    <div class="box-header with-border">
                        <h3 class="box-title">Modify Request</h3>
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
                            @if($itemType == 1)
                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th style="text-align: center;">Unit Price</th>
                                        <th style="text-align: right;">Total</th>
                                    </tr>
                                    @foreach ($products as $product)
                                        @if($loop->first || (isset($prevCategory) && $prevCategory != $product->category_id))
                                            <?php $prevCategory = 0; ?>
                                            <tr>
                                                <th class="success" colspan="5" style="text-align: center;">
                                                    <i>{{ $product->ProductPackages->name }}</i>
                                                </th>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td style="vertical-align: middle;">{{ $loop->iteration }}</td>
                                            <td style="vertical-align: middle; width: 250px;">
                                                <input type="text" class="form-control" name="item_names[{{ $product->id }}]"
                                                       value="{{ $product->name }}">
                                            </td>
                                            <td style="vertical-align: middle; width: 80px;">
                                                <input type="number" class="form-control input-sm items-quantity" name="quantity[{{ $product->id }}]"
                                                       value="1" onchange="subtotal()">
                                            </td>
											<td style="vertical-align: middle; width: 100px;">
												<input type="text" name="price[{{ $product->id }}]" class="form-control items-price" value="{{ ($product->current_price) ? $product->current_price : '' }}" onchange="subtotal()">
                                            </td>
											<td style="text-align: right;" id="total_price_{{ $loop->iteration - 1 }}">{{ ($product->total_price) ? $product->total_price : '' }}</td>										
                                        </tr>
                                        <?php $prevCategory = $product->category_id; ?>
                                    @endforeach
                                </table>
                            @elseif($itemType == 2)
                                @if($procurement->procurementItems)
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px" nowrap>#</th>
                                                <th>Description</th>
                                                <th style="width: 20px" nowrap class="text-center">Unit Price</th>
                                                <th nowrap>Quantity</th>
                                                <th style="width: 20px" nowrap class="text-center">Total</th>
                                                <th style="width: 20px" nowrap class="text-center"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="input-fields-wrap">
                                            @foreach($procurement->procurementItems as $items)
                                                <tr>
													<td style="width: 10px; vertical-align: middle;" nowrap>{{ $loop->iteration }}</td>
													<td>
														<textarea name="description[]" rows="2" class="form-control">{{ $items->item_name }}</textarea>
													</td>
													<td style="width: 100px; vertical-align: middle;" nowrap class="text-center">
														<input type="text" name="no_price[]" class="form-control item-price" value="{{ $items->item_price }}" onchange="subtotal()">
													</td>
													<td style="width: 100px; vertical-align: middle;" nowrap class="text-center">
														<input type="number" name="no_quantity[]" class="form-control item-quantity" onchange="subtotal()" value="{{ $items->quantity }}">
													</td>
													<td style="width: 10px; vertical-align: middle;" nowrap class="text-center" id="total_price_{{$loop->iteration - 1}}">{{ $items->item_price * $items->quantity }}</td>
													@if(! $loop->first)
                                                        <td style="width: 10px; vertical-align: middle;" nowrap class="text-center">
                                                            <button type="button" class="btn btn-link btn-xs remove_row" title="Remove"><i class="fa fa-times"></i></button>
                                                        </td>
                                                    @else
                                                        <td style="width: 10px; vertical-align: middle;" nowrap class="text-center"></td>
                                                    @endif
												</tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="6">
                                                    <button id="add_row" type="button" class="btn btn-primary btn-flat btn-block"><i class="fa fa-files-o"></i> Add New Row</button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                @else
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h4><i class="icon fa fa-database"></i> Services Settings Not Found!</h4>
                                        Please go to Products > Setup and enter the Services Settings.
                                    </div>
                                @endif
                            @endif
                            <!-- Total cost section -->
                            <div class="col-sm-6 col-sm-offset-6 no-padding">
                                <table class="table">
                                    <tr>
                                        <td></td>
                                        <th style="text-align: left;">Subtotal:</th>
                                        <td style="text-align: right;" id="subtotal" nowrap></td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: middle;">
                                            <div class="form-group no-margin">
                                                <label for="" class="col-sm-4 control-label"></label>
                                                <div class="col-sm-8">
                                                    <label class="radio-inline pull-right no-padding" style="padding-left: 0px;">Add VAT <input class="rdo-iCheck" type="checkbox" id="rdo_add_vat" name="add_vat" value="1" {{ ($procurement->add_vat == 1) ? ' checked' : '' }}></label>
                                                </div>
                                            </div>
                                        </td>
                                        <th style="text-align: left; vertical-align: middle;">VAT:</th>
                                        <td style="text-align: right; vertical-align: middle;" id="vat-amount" nowrap></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <th style="text-align: left; vertical-align: middle;">Total:</th>
                                        <td style="text-align: right; vertical-align: middle;" id="total-amount" nowrap></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
					<button type="button" class="btn btn-default pull-left" id="back_button"><i class="fa fa-arrow-left"></i> Back</button>
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-floppy-o"></i> Save Changes</button>
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
			//Cancel button click event
            $('#back_button').click(function () {
                location.href = '/procuremnt/modify_request/{{$procurement->id}}';
            });
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

            //Add more modules
            var max_fields      = 15; //maximum input boxes allowed
            var wrapper         = $(".input-fields-wrap"); //Fields wrapper
            var add_button      = $("#add_row"); //Add button ID
            var x = {{ ($procurement->procurementItems && $itemType == 2) ? count($procurement->procurementItems) : 1 }}; //initial text box count
            var xp = {{ ($procurement->procurementItems && $itemType == 2) ? count($procurement->procurementItems) - 1 : 1 }}; //initial text box count
            console.log(xp);
			$(add_button).click(function(e){ //on add input button click
                e.preventDefault();
                if(x < max_fields){ //max input box allowed
                    x++; //text box increment
					xp++; //text box increment
                    $(wrapper).append(`
                        <tr>
                            <td style="width: 10px; vertical-align: middle;" nowrap>${x}</td>
                            <td>
                                <textarea name="description[]" rows="2" class="form-control" required></textarea>
                            </td>
                            <td style="width: 10px; vertical-align: middle;" nowrap class="text-center">
								<input type="text" name="no_price[]" class="form-control item-price" onchange="subtotal()" required></td>
                            <td style="width: 100px; vertical-align: middle;" nowrap class="text-center">
                                <input type="number" name="no_quantity[]" class="form-control item-quantity" onchange="subtotal()" required>
                            </td>
							<td style="width: 10px; vertical-align: middle;" nowrap class="text-center" id="total_price_${xp}"></td>
                            <td style="width: 10px; vertical-align: middle;" nowrap class="text-center">
                                <button type="button" class="btn btn-link btn-xs remove_row" title="Remove"><i class="fa fa-times"></i></button>
                            </td>
                        </tr>
                    `); //add input box
                }
            });

            $(wrapper).on("click",".remove_row", function(e){ //user click on remove text
                e.preventDefault(); $(this).parent('td').parent('tr').remove(); x--; subtotal();
            });
            //call the function to calculate the subtotal
            subtotal();
            //VAT checkbox event handler
            var addVATCheckbox = $('#rdo_add_vat');
            addVATCheckbox.on('ifChanged', function(event){
                subtotal();
            });
        });
        //function to calculate the subtotal
        function subtotal() {
            var itemType = parseInt($('#item_type').val());
            var subtotal = 0;
            if (itemType == 1) { //Stock items
				var totalPrices = new Array();
				var quantitys = new Array();
                $( ".items-quantity" ).each(function( index ) {
                    var qty = parseInt($( this ).val()) || 0;
					quantitys[index]= qty;
                });
				$( ".items-price" ).each(function( index ) {
                    var price = parseInt($( this ).val()) || 0;
					
                    totalPrices[index] = price * quantitys[index];
					$("#total_price_" + index ).html('R ' + totalPrices[index].formatMoney(2));
                });
				const sums = totalPrices.reduce(add);
				function add(accumulator, a) {
					return accumulator + a;
				} 
				subtotal = sums;
				$( "#subtotal" ).html('R ' + subtotal.formatMoney(2));
            }
			else if (itemType == 2) { //Non Stock Items
                var totalPrice = new Array();
				var quantities = new Array();
                $( ".item-quantity" ).each(function( index ) {
                    var qty = parseInt($( this ).val()) || 0;
					quantities[index]= qty;
                });
				$( ".item-price" ).each(function( index ) {
					//console.log( index + ": " + $( this ).val() );
                    var price = parseInt($( this ).val()) || 0;
					totalPrice[index] = price * quantities[index];
					//console.log(index);
					$("#total_price_" + index ).html('R ' + totalPrice[index].formatMoney(2));
                });
				const sum = totalPrice.reduce(add);
				function add(accumulator, a) {
					return accumulator + a;
				} 
				subtotal = sum;
				$( "#subtotal" ).html('R ' + subtotal.formatMoney(2));
            }
            var total = subtotal;
            var formattedVAT = '&mdash;';
            var vatCheckValue = $('#rdo_add_vat').iCheck('update')[0].checked;
            if (vatCheckValue) {
                var vatAmount = (total * 0.15);
                formattedVAT = 'R ' + vatAmount.formatMoney(2);
                total += vatAmount;
            }
            $( "#vat-amount" ).html(formattedVAT);

            $( "#total-amount" ).html('R ' + total.formatMoney(2));
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
