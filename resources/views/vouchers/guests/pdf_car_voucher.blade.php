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
                                <h4 class="text-primary"><em><b>{{ $voucher->brn_name }}</b></em></h4>
                                <p>{!! html_entity_decode($voucher->c_agency_full_addr . $voucher->c_agency_reg . $voucher->c_agency_tel . $voucher->c_agency_tel_fax) !!}</p>
                            </td>
                        </tr>
                    </table>
                </td>
                <!-- ./Company details -->
                <!-- Voucher details -->
                <td>
                    <br>
                    <h4 class="text-center text-primary"><em><b>Vourcher No<br>{{ $voucher->c_sup_vouch_no }}</b></em></h4>
                    <br>
                    <h4 class="text-center text-primary"><em><b>{{ $voucher->c_date }}</b></em></h4>
                    <br>
                    <h4 class="text-center text-primary"><em><b>{{ $voucher->c_voucher_title }}</b></em></h4>
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
                                    @if(!empty($voucher->c_bill_name) || !empty($voucher->c_bill_postal_address_code) || !empty($voucher->c_bill_account_no))
                                        <tr>
                                            <!-- Supplier details -->
                                            <td>
                                                <h4 style="margin-top: 0;"><em><b>{{ $voucher->c_bill_name }}</b></em></h4>
                                                <table class="table" style="margin-bottom: 0;">
                                                    <tr>
                                                        <td>
                                                            <h5><em><b>{!! html_entity_decode($voucher->c_bill_postal_address_code . $voucher->sup_addblock2) !!}</b></em></h5>
                                                        </td>
                                                        <td nowrap>
                                                            <h5><em><b>{!! ($voucher->c_bill_account_no) ? 'Acc No: ' $voucher->c_bill_account_no : '' !!}</b>
															
															</em></h5>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <!-- ./Supplier details -->
                                        </tr>
                                    @endif
                                    @if($voucher->c_rental_addr1)
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
                                                            {!! ($voucher->c_rental_addr1) ? 'Rental Location: ' .$voucher->c_rental_addr1 . '<br>' : '' !!}
                                                            {!! ($voucher->c_rental_date) ? 'Date & Time: ' .$voucher->c_rental_date . '<br>' : '' !!}
                                                            {!! ($voucher->c_return_addr1) ? 'Return Location: ' .$voucher->c_return_addr1 . '<br>' : '' !!}
                                                            {!! ($voucher->c_return_date	) ? 'Date & Time: ' .$voucher->c_return_date . '<br>' : '' !!}
                                                            {!! ($voucher->c_duration) ? 'Rental Length (days): ' $voucher->c_duration : '' !!}
                                                            {!! ($voucher->c_flight_no) ? 'Flight: ' $voucher->c_flight_no : '' !!}
                                                            {!! ($voucher->c_tour_code) ? 'Tour Code: ' $voucher->c_tour_code : '' !!}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <!-- ./Dates and Times -->
                                        </tr>
                                    @endif
                                    @if(!empty($voucher->c_disclaimer))
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
                                                            {!! !empty($voucher->c_disclaimer) ? html_entity_decode($voucher->c_disclaimer)  : '' !!}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <!-- ./Special Instructions -->
                                        </tr>
                                    @endif
                                    @if(!empty($voucher->c_ct_card_warning))
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
                                                            <p style="font-size: 12px;">{!! !empty($voucher->c_ct_card_warning) ? html_entity_decode($voucher->c_ct_card_warning)  : '' !!}</p>
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
                                            @if(!empty($voucher->c_pax_name))
                                                <h4 class="text-primary" style="margin-bottom: 0;"><em><b>Client Details</b></em></h4>
                                                <table class="table" style="margin: 0;">
                                                    <tr>
                                                        <td style="padding: 0; width: 15%;">
                                                            <img width="50px" src="{{ $usersImg }}" alt="clientsicon">
                                                        </td>
                                                        <td style="padding: 0; width: 85%;">
                                                            {!! $voucher->c_pax_name . '</b>' . '<br>' !!}
                                                            {!! ((!empty(trim($voucher->c_pax_cell_no))) ? 'Cell: ' . $voucher->c_pax_cell_no : '') . ' ' .
                                                             ((!empty(trim($voucher->c_pax_email))) ? 'Email: ' . $voucher->c_pax_email : '') !!}
                                                        </td>
                                                    </tr>
                                                </table>
                                            @endif
                                            <!-- ./Client details -->
                                            <!-- Booking Refs -->
                                            @if(!empty($voucher->c_reservation_no) || !empty($voucher->c_issued_by))
                                                <h4 class="text-primary" style="margin-bottom: 0;"><em><b>Booking References</b></em></h4>
                                                <table class="table" style="margin: 0;">
                                                    <tr>
                                                        <td style="padding: 0; width: 15%;">
                                                            <img width="50px" src="{{ $folderImg }}" alt="folderImg">
                                                        </td>
                                                        <td style="padding: 0; width: 85%;">
                                                            {!! !empty($voucher->c_issued_by) ? 'Our Ref: ' . $voucher->c_issued_by . '<br>' : '' !!}
                                                            {!! !empty(trim($voucher->c_reservation_no)) ? 'Reservation No: ' . $voucher->c_reservation_no : '' !!}
                                                            {!! !empty(trim($voucher->c_currency)) ? 'Currency: ' . $voucher->c_currency : '' !!}
                                                            {!! !empty(trim($voucher->c_voucher_value)) ? 'Voucher Value: ' . $voucher->c_voucher_value : '' !!}
                                                            {!! !empty(trim($voucher->c_car_description)) ? 'Car Group/Code: ' . $voucher->c_car_description : '' !!}
                                                        </td>
                                                    </tr>
                                                </table>
                                            @endif
                                            <!-- ./Booking Refs -->
                                            <!-- Services Required -->
                                            @if(!empty($voucher->c_rate_name) || !empty($voucher->c_insurance))
                                                <h4 class="text-primary" style="margin-bottom: 0;"><em><b>Services Required</b></em></h4>
                                                <table class="table" style="margin: 0;">
                                                    <tr>
                                                        <td style="padding: 0; width: 15%;">
                                                            <img width="50px" src="{{ $calculatorImg }}" alt="calculatorImg">
                                                        </td>
                                                        <td style="padding: 0; width: 85%;">
                                                            {!! !empty($voucher->c_rate_name) ?  'Rate Code/Daily Rate: ' .$voucher->c_rate_name . '<br>' : '' !!}
                                                            {!! !empty($voucher->c_insurance) ? 'Insurances Required: ' . $voucher->c_insurance : '' !!}
                                                            {!! !empty($voucher->c_remarks) ? 'Remarks: ' . $voucher->c_remarks : '' !!}
                                                        </td>
                                                    </tr>
                                                </table>
                                            @endif
                                            <!-- ./Services Required -->
                                            <!-- Payment -->
                                            @if(!empty($voucher->c_client_code) || !empty($voucher->pmt_extras))
                                                <h4 class="text-primary" style="margin-bottom: 0;"><em><b>Account Infomartion</b></em></h4>
                                                <table class="table" style="margin: 0;">
                                                    <tr>
                                                        <td style="padding: 0; width: 15%;">
                                                            <img width="50px" src="{{ $paymentImg }}" alt="calculatorImg">
                                                        </td>
                                                        <td style="padding: 0; width: 85%;">
                                                            {!! !empty($voucher->c_client_code) ? 'Acc No: ' . $voucher->c_client_code . '<br>' : '' !!}
                                                            {!! !empty($voucher->c_client_code) ? 'CC No: ' . '' . '<br>' : '' !!}
                                                            {!! !empty($voucher->pmt_extras) ? 'Order No: ' . $voucher->c_cl_ord_no : '' !!}
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
                                                        <img width="70px" src="{{ $iataImg }}" alt="iataImg">
                                                        <p>{{ !empty($voucher->co_iata) ? $voucher->co_iata : '' }}</p>
                                                    </td>
                                                    <td style="padding: 0; width: 60%;">
                                                        <p class="text-center">{{ !empty($voucher->co_vat) ? 'Vat Reg No ' . $voucher->co_vat : '' }}</p>
                                                    </td>
                                                    <td style="padding: 0; width: 20%; text-align: right;">
                                                        <img width="80px" src="{{ $asataImg }}" alt="asataImg">
                                                        <p>Member</p>
                                                    </td>
                                                </tr>
                                            </table>
                                            <br><br>
                                            <table class="table" style="margin: 0;">
                                                <tr>
                                                    <td style="padding: 0;">
                                                        ...................................................................................................
                                                        <br>
                                                        <b><em>Issued by</em></b> <span class="pull-right"><b><em>{{ $voucher->c_issued_by }}</em></b></span>
                                                        <br>
                                                        {{ !empty($voucher->c_voucher_message) ? $voucher->c_voucher_message : '' }}
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
        <!--<div class="row no-gutter">
            <br><br>
            <div class="col-xs-8">
                <table class="table" style="margin-bottom: 0;">
                    <tr>
                        <td width="35%">
                            <br><br>
                            <img width="180px" src="{{ public_path() . $companyDetails['company_logo_url'] }}" alt="letterhead">
                        </td>
                        <td width="65%" style="">
                            <h4 class="text-primary"><em><b>XL Nexus Travel</b></em></h4>
                            <p>Postnet Suite 136 Private Bag X2600 Houghton 2041<br>
                                52 Engelworld Drive Saxonworld 2132 South Africa<br>
                                Tel : +27 11 486 9000 Fax : 086 570 3112<br>
                                Email: Web: www.nexustravel.co.za</p>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-xs-3">
                <br>
                <h4 class="text-center text-primary"><em><b>Transfer<br>{{ $voucher->vch_no_full }}</b></em></h4>
                <br>
                <h4 class="text-center text-primary"><em><b>{{ ($voucher->vch_dt) ? date('d/m/Y', $voucher->vch_dt) : '' }}</b></em></h4>
                <br>
            </div>
        </div>-->
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