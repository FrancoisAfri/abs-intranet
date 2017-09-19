@extends('layouts.main_layout')
@section('page_dependencies')
<!-- bootstrap file input -->
<link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
@endsection
@section('content')
<!--  -->

<!-- Ticket Widget -->

<!--  -->
<div class="row">
    <div class="col-md-12">
        <div>
            <div class="box box-warning same-height-widget">
                <div class="box-header with-border">
                    <i class="fa fa-product-hunt"></i>
                    <h3 class="box-title">View My Product(s)</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body" style="max-height: 274px; overflow-y: scroll;">
                    <div class="table-responsive">
                        <table class="table no-margin">
                            <thead>
                                <tr>
                                    <th><i class="fa fa-id-badge"></i> Account Number</th>
                                    <th><i class="fa fa-building-o"></i> Company</th>
                                    <th><i class="fa fa-user"></i> Contact Person</th>
                                    <th><i class="fa fa-calendar-o"></i> Date Created</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                @if (!empty($account))
                                @foreach($account as $accounts)
                                <tr>
                                    <td>{{ ($accounts->account_number) ? $accounts->account_number : '' }}</td>
                                    <td>{{ ($accounts->company) ? $accounts->company->name : '[individual]' }}</td>
                                    <td>{{ ($accounts->client) ? $accounts->client->full_name : '' }}</td>
                                    <td>{{ ($accounts->start_date) ? date('d/m/Y', $accounts->start_date) : '' }}</td>
                                </tr>

                            </tbody>
                        </table>

                    </div>
                    <!--  -->
                    <div class="table-responsive">
                        <table class="table no-margin">
                            <thead>

                                <tr>
                                    <td></td>
                                    <th>Quote # <i class="fa fa-first-order"></th>
                                    <th>Date Ordered <i class="fa fa-calendar-o"></i></th>
                                    <th>Payment Option <i class="fa fa-credit-card-alt"></i></th>
                                    <th>Status <i class="fa fa-info-circle"></i></th>
                                   <!--  <th class="text-right">Cost <i class="fa fa-money"></i></th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($accounts->quotations as $quotation)
                                <tr>
                                    <td width="5px"><i class="fa fa-caret-down"></i></td>
                                    <td><a href="/">{{ ($accounts->quote_number) ? $accounts->quote_number : $accounts->id }}</a></td>
                                    <td>{{ $quotation->created_at }}</td>
                                    <td>{{ $quotation->str_payment_option }}</td>
                                    <td><span class="label label-{{ $labelColors[$quotation->status] }}">{{ $purchaseStatus[$quotation->status] }}</span></td>
                                    <td class="text-right" nowrap>{{ ($quotation->cost) ? 'R ' . number_format($quotation->cost, 2) : '' }}</td>
                                    <!--  -->
                                    <td class="text-right"></td>
                                </tr>
                                <!--  -->
                                @if($quotation && (count($quotation->products) > 0 || count($quotation->packages) > 0))
                                <tr>
                                    <td></td>
                                    <td class="warning" colspan="7">
                                        <ul class="list-inline">
                                            @if(count($quotation->products) > 0)
                                            @foreach($quotation->products as $product)
                                            <li class="list-inline-item"><i class="fa fa-square-o"></i> {{ $product->name }}</li> |
                                            @endforeach
                                            @endif

                                            @if(count($quotation->packages) > 0)
                                            @foreach($quotation->packages as $package)
                                            <li class="list-inline-item"><i class="fa fa-object-group"></i> {{ $package->name }}</li> |
                                            @endforeach
                                            @endif
                                        </ul>
                                    </td>
                                </tr>
                                @endif

                                @endforeach
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="box-footer clearfix">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Ticket Widget -->
<div class="row">
    <div class="col-md-12">
        <div>
            <div class="box box-Success same-height-widget">
                <div class="box-header with-border">
                    <i class="fa fa-product-hunt"></i>
                    <h3 class="box-title">View My Tickets(s)</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                    <div rowspan="2" width="3px" style="vertical-align: middle;"><h1 class="no-margin"><i class="fa fa-user-o"></i></h1></div>
                <h4>Your Ticket(s)  - {{ $names." ".$surname }}</h4>
                <h4 class="btn btn-primary " style="vertical-align: right";>  {{ $ticketcount }}</h4>
                </div>

<div class="box-body" style="max-height: 274px; overflow-y: scroll;">
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <tr>
                @if (count($helpdeskTickets) > 0)
                @foreach($helpdeskTickets as $helpdeskTicket)
            <tr>
            </tr>
            @endforeach
            @endif
            </tr>
         </table>
         <table class="table no-margin">
            <thead>
                <tr>
                    <th><i class="fa fa-id-badge"></i> Ticket Number</th>
                    <th><i class="fa fa-envelope"></i> Subject</th>
                    <th><i class="fa fa-calendar-o"></i> Ticket Date</th>
                    <th style="text-align: right;"><i class="fa fa-info-circle"></i> Status</th>
                   
                </tr>
         </thead>
            <tbody>
                @if (!empty($tickets))
                @foreach($tickets as $ticket)
                <tr>
                    <td>TICK{{ (!empty($ticket->id)) ?  $ticket->id : ''}}</td>
                    <td>{{ (!empty($ticket->subject)) ?  $ticket->subject : ''}}</td>
                    <td>{{ !empty($ticket->ticket_date) ? date('d M Y ', $ticket->ticket_date) : '' }}</td>
                    <td style="text-align: right;">{{ (!empty($ticket->status)) ?  $ticketStatus[$ticket->status] : ''}} </td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
        <div class="box-footer">
            <button type="button" id="new_tickets" class="btn btn-primary pull-right fa fa-paper-plane" data-toggle="modal" data-target="#add-new-ticket-modal">Add Ticket</button>
        </div>
      </div>
     </div>
    </div>
   </div>
  </div>
</div>
 @include('dashboard.partials.add_ticket')
<!-- end Tickets -->

<!--  -->

 <div class="row">
        <div class="col-md-12">    
            <div class="box box-danger same-height-widget">
            <form class="form-horizontal" method="POST" action="/newquote/save">
                    {{ csrf_field() }}
               <div class="box-header with-border">
                    <i class="fa fa-product-hunt"></i>
                    <h3 class="box-title">New Quote</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
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
                        <div class="box-body" style="max-height: 274px; overflow-y: scroll;">
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th style="text-align: right;">Unit Price</th>
                                    <th></th>
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
                                    <tr class="{{ ($product->promotions->first()) ? 'warning' : '' }}"
                                        @if($promotion = $product->promotions->first())
                                        data-toggle="tooltip" title="{{ 'This item is on promotion from ' .
                                        date('d M Y', $promotion->start_date) . ' to ' . date('d M Y', $promotion->end_date) . '.' }}"
                                        @endif>

                                        <td style="vertical-align: middle;">{{ $loop->iteration }}</td>
                                        <td style="vertical-align: middle;">
                                            {{ $product->name }}
                                            @if($product->promotions->first())
                                                &nbsp;<i class="fa fa-info-circle"></i>
                                            @endif
                                        </td>
                                        <td style="vertical-align: middle; width: 80px;">
                                            <input type="number" class="form-control input-sm item-quantity" name="quantity[{{ $product->id }}]"
                                                   value="1" data-price="{{ $product->current_price }}" onchange="subtotal()" required>
                                        </td>

                                        <td style="vertical-align: middle; text-align: right;">
                                        <!--  {{ $product->price }} -->
                                            {{ $product->current_price ? 'R ' . number_format($product->current_price, 2) : '' }}
                                        </td>
                                        <td style="vertical-align: middle; width: 80px;"> <label class="radio-inline pull-right" style="padding-left: 0px;"><input class="rdo-iCheck" type="checkbox" id=""  name="selected_{{ $product->id }}_{{ $product->name }}_check[]" value="{{ $product->id }}" > <span class="label ">Active</span></label></td>
                                    </tr>
                                    <input type="hidden" name="price[{{ $product->id }}]"
                                           value="{{ ($product->current_price) ? $product->current_price : '' }}">
                                    <?php $prevCategory = $product->category_id; ?>
                                @endforeach
                                @foreach ($packages as $package)
                                    <tr class="{{ ($package->promotions->first()) ? 'warning' : 'success' }}"
                                        @if($promotion = $package->promotions->first())
                                        data-toggle="tooltip" title="{{ 'This item is on promotion from ' .
                                        date('d M Y', $promotion->start_date) . ' to ' . date('d M Y', $promotion->end_date) . '.' }}"
                                        @endif>

                                        <td style="vertical-align: middle;"><i class="fa fa-caret-down"></i></td>
                                        <th style="vertical-align: middle;">
                                            Package: {{ $package->name }}
                                            @if($package->promotions->first())
                                                &nbsp;<i class="fa fa-info-circle"></i>
                                            @endif
                                        </th>
                                        <td style="vertical-align: middle; width: 80px;">
                                            <input type="number" class="form-control input-sm item-quantity" name="package_quantity[{{ $package->id }}]"
                                                   value="1" data-price="{{ $package->price }}"
                                                   onchange="subtotal()" required>
                                        </td>
                                        <td style="vertical-align: middle; text-align: right;">

                                            {{ ($package->price) ? 'R ' . number_format($package->price, 2) : '' }}
                                        </td>
                                         <td style="vertical-align: middle; width: 80px;"> <label class="radio-inline pull-right" style="padding-left: 0px;"><input class="rdo-iCheck" type="checkbox" id=""  name="selected_{{ $package->id }}_{{ $package->name }}_check[]" value="1" > <span class="label ">Active</span></label></td>
                                    </tr>
                                    <input type="hidden" name="package_price[{{ $package->id }}]" value="{{ ($package->price) ? $package->price : '' }}">
                                    @if($package->products_type && count($package->products_type) > 0)
                                        @foreach($package->products_type as $product)
                                            <tr class="{{ ($package->promotions->first()) ? 'warning' : '' }}">
                                                <td style="vertical-align: middle;">{{ $loop->iteration }}</td>
                                                <td style="vertical-align: middle;">{{ $product->name }}</td>
                                                <td style="text-align: center; vertical-align: middle; width: 80px;">
                                                    &mdash;
                                                </td>
                                                <td style="vertical-align: middle; text-align: right;">
                                                    &mdash;
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            </table>
                        </div>
                       </div>
                      <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-send"></i> Submit Quote</button>
                    </div>
                </form>
            </div>
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
    <!-- ChartJS 1.0.1 -->
    <script src="/bower_components/AdminLTE/plugins/chartjs/Chart.min.js"></script>
    <!-- Admin dashboard charts ChartsJS -->
    <script src="/custom_components/js/admindbcharts.js"></script>
    <!-- matchHeight.js
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.0/jquery.matchHeight-min.js"></script>-->
    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
    <!-- Task timer -->
    <script src="/custom_components/js/tasktimer.js"></script>
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <script>
        function postData(id, data)
        {
            if (data == 'start')
                location.href = "/task/start/" + id;
            else if (data == 'pause')
                location.href = "/task/pause/" + id;
            else if (data == 'end')
                location.href = "/task/end/" + id;
        }
       $(function () {
            // hide end button when page load
            //$("#end-button").show();
            //Initialize Select2 Elements
            $(".select2").select2();

             $('#ticket').click(function () {
                location.href = '/helpdesk/ticket';
            });

            //Initialize iCheck/iRadio Elements
            $('.rdo-iCheck').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });

            //initialise matchHeight on widgets
            //$('.same-height-widget').matchHeight();

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
            $(window).on('resize', function () {
                $('.modal:visible').each(reposition);
            });

               //call the function to calculate the subtotal
            subtotal();

             //function to calculate the subtotal
        function subtotal() {
            var subtotal = 0;
            var discountAmount = 0;
            $( ".item-quantity" ).each(function( index ) {
                //console.log( index + ": " + $( this ).data('price') );
                var qty = $( this ).val();
                var price = $( this ).data('price');
                subtotal += (qty * price);
                $( "#subtotal" ).html('R ' + subtotal.formatMoney(2));
            });

            var discountPercent = $('#discount_percent').val();
            discountAmount = (subtotal * discountPercent) / 100;
            $( "#discount-amount" ).html('R ' + discountAmount.formatMoney(2));

            var total = (subtotal - discountAmount);

            var formattedVAT = '&mdash;';
            var vatCheckValue = $('#rdo_add_vat').iCheck('update')[0].checked;
            if (vatCheckValue) {
                var vatAmount = (total * 0.14);
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
          //Post module form to server using ajax (ADD)
            $('#add_tiket').on('click', function() {
                //console.log('strUrl');
               // var strUrl = '/help_desk/ticket/add';
                var strUrl = '/help_desk/ticket/client';
                var modalID = 'add-new-ticket-modal';
                var objData = {
                    name: $('#'+modalID).find('#name').val(),
                    email: $('#'+modalID).find('#email').val(),
                    helpdesk_id: $('#'+modalID).find('#helpdesk_id').val(),
                    subject: $('#'+modalID).find('#subject').val(),
                    message: $('#'+modalID).find('#message').val(),
                    _token: $('#'+modalID).find('input[name=_token]').val(),
                };
                var submitBtnID = 'new_tickets';
                var redirectUrl = '/';
                var successMsgTitle = 'Changes Saved!';
                var successMsg = 'The ticket has been Added successfully.';
                //var formMethod = 'PATCH';
                modalAjaxSubmit(strUrl, objData, modalID, submitBtnID, redirectUrl, successMsgTitle, successMsg);
            });

            // 

        });
    </script>
@endsection