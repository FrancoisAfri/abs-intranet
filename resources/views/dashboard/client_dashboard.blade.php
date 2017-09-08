@extends('layouts.main_layout')
@section('page_dependencies')
<!-- bootstrap file input -->
<link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
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
<div class="box-body" style="max-height: 274px; overflow-y: scroll;">
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <tr>
                @if (count($helpdeskTickets) > 0)
                @foreach($helpdeskTickets as $helpdeskTicket)
            <tr>

                <th rowspan="2" width="3px" style="vertical-align: middle;"><h1 class="no-margin"><i class="fa fa-user-o"></i></h1></th>
                <th>Your Ticket(s)  - {{ $names." ".$surname }}</th>
                <th class="btn btn-primary ">  {{ $ticketcount }}</th>
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
                    <th></th>
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
 @include('dashboard.partials.add_ticket')
<!-- end Tickets -->

<!-- products -->
<div class="row">
    <div class="col-md-12">
        <div>
             <div class="box box-danger same-height-widget">
               <div class="box-header with-border">
                    <i class="fa fa-product-hunt"></i>
                    <h3 class="box-title">View Package-Product(s)</h3>
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
                                    <th><i class="fa fa-object-group"></i> Package</th>
                                    <th><i class="fa fa-commenting-o"></i> Description</th>
                                    <th><i class="fa fa-percent"></i> Discount</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                @if (!empty($packages))
                                @foreach($packages as $package)
                                <tr>
                                   <td>{{ (!empty($package->name)) ?  $package->name : ''}}</td>
                                    <td>{{ (!empty($package->description)) ?  $package->description : ''}}</td>
                                    <td>% {{  (!empty($package->discount)) ?  $package->discount : ''}}</td>
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
                                    <th><i class="material-icons">folder_special</i>Product(s)</th>
                                    <th><i class="fa fa-commenting-o"></i> Description</th>
                                    <th>Price <i class="fa fa-credit-card-alt"></i></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody >
                                @foreach($package->products_type as $productstype )
                                <tr>
                                    <td width="5px"><i class="fa fa-caret-down"></i></td>
                                   <!--  <td><a href="/">{{ ($accounts->quote_number) ? $accounts->quote_number : $accounts->id }}</a></td> -->
                                    <td >{{ (!empty($productstype->name)) ?  $productstype->name : ''}}</td>
                                     <td >{{ (!empty($productstype->description)) ?  $productstype->description : ''}}</td>
                                      <td >{{ (!empty($productstype->price)) ?  $productstype->price : ''}}</td>
                                       <td>
                                        <button type= "button" id="Accept" class="btn btn-success btn-xs btn-detail fa fa-plus open-modal" value=" ">Add</button>

                                    </td>
                                </tr>
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
        });
    </script>
@endsection