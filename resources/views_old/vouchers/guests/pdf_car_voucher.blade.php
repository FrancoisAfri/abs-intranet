<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $page_title or "PDF View" }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap 3.3.6 -->
    @include('layouts.printables.partials.bootstrap_3_3_6css')
    <!-- Font Awesome -->
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">-->
    <!-- Ionicons --
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">-->
    <!-- Theme style -->
    @include('layouts.printables.partials.adminltecss')

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- custom style -->
    @include('layouts.printables.partials.custom_style')

    <style>
        @page { margin: 0px; }
        body { margin: 0px; }
        .row.no-gutter {
            margin-left: 0;
            margin-right: 0;
        }
        .row.no-gutter [class*='col-']:not(:first-child),
        .row.no-gutter [class*='col-']:not(:last-child) {
            padding-right: 0;
            padding-left: 0;
        }
        .row > div {
            /*background: lightgrey;*/
            border: 1px solid;
        }
        table.table-bordered{
            border:1px solid #000;
            /*margin-top:20px;*/
        }
        table.table-bordered > thead > tr > th{
            border:1px solid #000;
        }
        table.table-bordered > tbody > tr > td{
            border:1px solid #000;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Main content -->
        <br><br>
        <table class="table table-bordered">
            <tr>
                <!-- Company details -->
                <td>
                    <table class="table" style="margin-bottom: 0;">
                        <tr>
                            <td width="35%">
                                <br><br>
                                <img width="200px" src="{{ public_path() . $companyDetails['company_logo_url'] }}" alt="letterhead">
                            </td>
                            <td width="65%" style="">
                                <h4 class="text-primary"><em><b>{{ $carVoucher->brn_name }}</b></em></h4>
                                <p>{!! html_entity_decode($carVoucher->c_agency_full_addr . '<br>' . $carVoucher->c_agency_reg . '<br>' .$carVoucher->c_agency_tel. '<br>' .$carVoucher->c_agency_tel_fax) !!}</p>
                            </td>
                        </tr>
                    </table>
                </td>
                <!-- ./Company details -->
                <!-- Voucher details -->
                <td>
                    <br>
                    <h4 class="text-center text-primary"><em><b>Vourcher No<br>{{ $carVoucher->c_sup_vouch_no }}</b></em></h4>
                    <br>
                    <h4 class="text-center text-primary"><em><b>{{ $carVoucher->c_date }}</b></em></h4>
                    <br>
                    <h4 class="text-center text-primary"><em><b>{{ $carVoucher->c_carVoucher_title }}</b></em></h4>
                    <br>
                </td>
                <!-- ./Voucher details -->
            </tr>
            <tr>
                <td colspan="2" class="no-padding">
                    <table class="table table-bordered" style="margin: -1px; border-bottom: 0 none #fff; border-right: 0 none #fff; border-left: 0 none #fff;">
                        <tr>
                            <td width="50%" class="no-padding" style="border-right: 0 none #fff; border-left: 0 none #fff;">
                                <table class="table table-bordered" style="margin: -1px;">
                                        <tr>
                                            <!-- Supplier details -->
                                            <td>
                                                <h4 style="margin-top: 0;"><em><b>{{ $carVoucher->c_bill_name }}</b></em></h4>
                                                <table class="table" style="margin-bottom: 0;">
                                                    <tr>
                                                        <td>
                                                            <h5><em><b>{!! html_entity_decode($carVoucher->c_bill_postal_address1 . '<br>'. $carVoucher->c_bill_postal_address2. '<br>'. $carVoucher->c_bill_postal_address3. '<br>'. $carVoucher->c_bill_postal_address_code . '<br>'  . $carVoucher->sup_addblock2). '<br>'  !!}</b></em></h5>
                                                        </td>
                                                        <td nowrap>
                                                            <h5><em><b>{!! ($carVoucher->c_bill_account_no) ? 'Acc No: ' . $carVoucher->c_bill_account_no : '' !!}</b>
															
															</em></h5>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <!-- ./Supplier details -->
                                        </tr>
                                    @if($carVoucher->c_rental_addr1)
                                        <tr>
                                            <!-- Dates and Times -->
                                            <td>
                                                <h4 class="text-primary" style="margin-bottom: 0;"><em><b>Rental Dates, Times & Location</b></em></h4>
                                                <table class="table" style="margin: 0;">
                                                    <tr>
                                                        <td style="padding: 0; width: 15%;">
                                                            <img width="50px" src="{{ $calendarClockImg }}" alt="folderImg">
                                                        </td>
                                                        <td style="padding: 0; width: 85%;">
                                                            {!! ($carVoucher->c_rental_addr1) ? 'Rental Location: ' . $carVoucher->c_rental_addr1 . '<br>' : '' !!}
                                                            {!! ($carVoucher->c_rental_date) ? 'Date & Time: ' . $carVoucher->c_rental_date . '<br>' : '' !!}
                                                            {!! ($carVoucher->c_return_addr1) ? 'Return Location: ' . $carVoucher->c_return_addr1 . '<br>' : '' !!}
                                                            {!! ($carVoucher->c_return_date	) ? 'Date & Time: ' . $carVoucher->c_return_date . '<br>' : '' !!}
                                                            {!! ($carVoucher->c_duration) ? 'Rental Length (days): ' . $carVoucher->c_duration : '' !!}
                                                            {!! ($carVoucher->c_flight_no) ? 'Flight: ' . $carVoucher->c_flight_no : '' !!}
                                                            {!! ($carVoucher->c_tour_code) ? 'Tour Code: ' . $carVoucher->c_tour_code : '' !!}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <!-- ./Dates and Times -->
                                        </tr>
                                    @endif
                                    @if(!empty($carVoucher->c_disclaimer))
                                        <tr>
                                            <!-- Special Instructions -->
                                            <td>
                                                <h4 class="text-primary" style="margin-bottom: 0;"><em><b>Insurance Disclaimer</b></em></h4>
                                                <table class="table" style="margin: 0;">
                                                    <tr>
                                                        <td style="padding: 0; width: 15%;">
                                                            <img width="50px" src="{{ $starImg }}" alt="folderImg">
                                                        </td>
                                                        <td style="padding: 0; width: 85%;">
                                                            {!! !empty($carVoucher->c_disclaimer) ? html_entity_decode($carVoucher->c_disclaimer)  : '' !!}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <!-- ./Special Instructions -->
                                        </tr>
                                    @endif
                                    @if(!empty($carVoucher->c_ct_card_warning))
                                        <tr>
                                            <!-- General Ts & Cs -->
                                            <td style="border-bottom: 0 none #fff;">
                                                <h4 class="text-primary" style="margin-bottom: 0;"><em><b>General Terms and Conditions</b></em></h4>
                                                <table class="table" style="margin: 0;">
                                                    <tr>
                                                        <td style="padding: 0; width: 15%;">
                                                            <img width="50px" src="{{ $tcImg }}" alt="folderImg">
                                                        </td>
                                                        <td style="padding: 0; width: 85%;">
                                                            <p style="font-size: 12px;"><b>{!! !empty($carVoucher->c_ct_card_warning) ? html_entity_decode($carVoucher->c_ct_card_warning)  : '' !!}</b></p>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <!-- ./General Ts & Cs -->
                                        </tr>
                                    @endif
                                </table>
                            </td>
                            <td width="50%" class="no-padding" style="border-right: 0 none #fff; border-left: 0 none #fff;">
                                <table class="table table-bordered" style="margin: -1px; border-bottom: 0 none #fff;">
                                    <tr>
                                        <!-- Client details / Booking Refs / Services Required / Payment -->
                                        <td style="border-right: 0 none #fff; border-left: 0 none #fff;">
                                            <!-- Client details -->
                                            @if(!empty($carVoucher->c_pax_name))
                                                <h4 class="text-primary" style="margin-bottom: 0;"><em><b>Client Details</b></em></h4>
                                                <table class="table" style="margin: 0;">
                                                    <tr>
                                                        <td style="padding: 0; width: 15%;">
                                                            <img width="50px" src="{{ $usersImg }}" alt="clientsicon">
                                                        </td>
                                                        <td style="padding: 0; width: 85%;">
                                                            {!! $carVoucher->c_pax_name . '</b>' . '<br>' !!}
                                                            {!! ((!empty(trim($carVoucher->c_pax_cell_no))) ? 'Cell: ' . $carVoucher->c_pax_cell_no : '') . ' ' .
                                                             ((!empty(trim($carVoucher->c_pax_email))) ? 'Email: ' . $carVoucher->c_pax_email : '') !!}
                                                        </td>
                                                    </tr>
                                                </table>
                                            @endif
                                            <!-- ./Client details -->
                                            <!-- Booking Refs -->
                                            @if(!empty($carVoucher->c_reservation_no) || !empty($carVoucher->c_issued_by))
                                                <h4 class="text-primary" style="margin-bottom: 0;"><em><b>Booking References</b></em></h4>
                                                <table class="table" style="margin: 0;">
                                                    <tr>
                                                        <td style="padding: 0; width: 15%;">
                                                            <img width="50px" src="{{ $folderImg }}" alt="folderImg">
                                                        </td>
                                                        <td style="padding: 0; width: 85%;">
                                                            {!! !empty($carVoucher->c_issued_by) ? 'Our Ref: ' . $carVoucher->c_issued_by . '<br>' : '' !!}
                                                            {!! !empty(trim($carVoucher->c_reservation_no)) ? 'Reservation No: ' . $carVoucher->c_reservation_no. '<br>' : '' !!}
                                                            {!! !empty(trim($carVoucher->c_currency)) ? 'Currency: ' . $carVoucher->c_currency. '<br>' : '' !!}
                                                            {!! !empty(trim($carVoucher->c_carVoucher_value)) ? 'Voucher Value: ' . $carVoucher->c_carVoucher_value. '<br>' : '' !!}
                                                            {!! !empty(trim($carVoucher->c_car_description)) ? 'Car Group/Code: ' . $carVoucher->c_car_description. '<br>' : '' !!}
                                                        </td>
                                                    </tr>
                                                </table>
                                            @endif
                                            <!-- ./Booking Refs -->
                                            <!-- Services Required -->
                                            @if(!empty($carVoucher->c_rate_name) || !empty($carVoucher->c_insurance))
                                                <h4 class="text-primary" style="margin-bottom: 0;"><em><b>Services Required</b></em></h4>
                                                <table class="table" style="margin: 0;">
                                                    <tr>
                                                        <td style="padding: 0; width: 15%;">
                                                            <img width="50px" src="{{ $calculatorImg }}" alt="calculatorImg">
                                                        </td>
                                                        <td style="padding: 0; width: 85%;">
                                                            {!! !empty($carVoucher->c_rate_name) ?  'Rate Code/Daily Rate: ' .$carVoucher->c_rate_name . '<br>' : '' !!}
                                                            {!! !empty($carVoucher->c_insurance) ? 'Insurances Required: ' . $carVoucher->c_insurance : '' !!}
                                                            {!! !empty($carVoucher->c_remarks) ? 'Remarks: ' . $carVoucher->c_remarks : '' !!}
                                                        </td>
                                                    </tr>
                                                </table>
                                            @endif
                                            <!-- ./Services Required -->
                                            <!-- Payment -->
                                            @if(!empty($carVoucher->c_client_code) || !empty($carVoucher->c_cl_ord_no))
                                                <h4 class="text-primary" style="margin-bottom: 0;"><em><b>Account Infomartion</b></em></h4>
                                                <table class="table" style="margin: 0;">
                                                    <tr>
                                                        <td style="padding: 0; width: 15%;">
                                                            
                                                        </td>
                                                        <td style="padding: 0; width: 85%;">
                                                            {!! !empty($carVoucher->c_client_code) ? 'Acc No: ' . $carVoucher->c_client_code . '<br>' : '' !!}
                                                            {!! !empty($carVoucher->c_client_code) ? 'CC No: ' . '' . '<br>' : '' !!}
                                                            {!! !empty($carVoucher->c_cl_ord_no) ? 'Order No: ' . $carVoucher->c_cl_ord_no : '' !!}
                                                        </td>
                                                    </tr>
                                                </table>
                                            @endif
                                            <!-- ./Payment -->
                                        </td>
                                        <!-- ./Client details / Booking Refs / Services Required / Payment -->
                                    </tr>
                                    <tr>
                                        <!-- Authorization section -->
                                        <td style="border-bottom: 0 none #fff; border-right: 0 none #fff; border-left: 0 none #fff;">
                                            <table class="table" style="margin: 0;">
                                                <tr>
                                                    <td style="padding: 0; width: 20%;">
                                                         </td>
                                                    <td style="padding: 0; width: 60%;">
                                                       </td>
                                                    <td style="padding: 0; width: 20%; text-align: right;">
                                                        
                                                    </td>
                                                </tr>
                                            </table>
                                            <br><br>
                                            <table class="table" style="margin: 0;">
                                                <tr>
                                                    <td style="padding: 0;">
                                                        ...................................................................................................
                                                        <br>
                                                        <b><em>Issued by</em></b> <span class="pull-right"><b><em>{{ $carVoucher->c_issued_by }}</em></b></span>
                                                        <br>
                                                        {{ !empty($carVoucher->c_voucher_message) ? $carVoucher->c_voucher_message : '' }}
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <!-- ./Authorization section -->
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        
    </div>
    <!-- ./wrapper -->



    <!-- REQUIRED JS SCRIPTS -->
    <!-- jQuery 2.2.3 -->
    <script src="/bower_components/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="/bower_components/AdminLTE/bootstrap/js/bootstrap.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/bower_components/AdminLTE/dist/js/app.min.js"></script>
    <!-- Additional page script -->
</body>
</html>