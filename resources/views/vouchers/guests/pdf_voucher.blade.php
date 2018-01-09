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
                                <p>{!! html_entity_decode($voucher->co_fulldetails ) !!}</p>
                            </td>
                        </tr>
                    </table>
                </td>
                <!-- ./Company details -->
                <!-- Voucher details -->
                <td>
                    <br>
                    <h4 class="text-center text-primary"><em><b>Accommodation<br>{{ $voucher->vch_no_full }}</b></em></h4>
                    <br>
                    <h4 class="text-center text-primary"><em><b>{{ ($voucher->vch_dt) ? date('d/m/Y', $voucher->vch_dt) : '' }}</b></em></h4>
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
                                    @if(!empty($voucher->sup_name) || !empty($voucher->sup_addblock1) || !empty($voucher->sup_addblock2 || !empty($voucher->sup_contactblock)))
                                        <tr>
                                            <!-- Supplier details -->
                                            <td>
                                                <h4 style="margin-top: 0;"><em><b>{{ $voucher->sup_name }}</b></em></h4>
                                                <table class="table" style="margin-bottom: 0;">
                                                    <tr>
                                                        <td>
                                                            <h5><em><b>{!! html_entity_decode($voucher->sup_addblock2) !!}</b></em></h5>
                                                        </td>
                                                        <td nowrap>
                                                            <h5><em><b>{!! html_entity_decode($voucher->sup_contactblock) !!}</b></em></h5>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <!-- ./Supplier details -->
                                        </tr>
                                    @endif
                                    @if(($voucher->arr_date && $voucher->arr_date > 0) || ($voucher->dep_date && $voucher->dep_date > 0))
                                        <tr>
                                            <!-- Dates and Times -->
                                            <td>
                                                <h4 class="text-primary" style="margin-bottom: 0;"><em><b>Dates & Times</b></em></h4>
                                                <table class="table" style="margin: 0;">
                                                    <tr>
                                                        <td style="padding: 0; width: 15%;">
                                                            <img width="50px" src="{{ $calendarClockImg }}" alt="folderImg">
                                                        </td>
                                                        <td style="padding: 0; width: 85%;">
                                                            {!! ($voucher->arr_date && $voucher->arr_date > 0) ? $voucher->arr_desc . ': '
                                                            . date('D d/m/Y', $voucher->arr_date) . '<br>' : '' !!}
                                                            {!! ($voucher->dep_date && $voucher->dep_date > 0) ? $voucher->dep_desc . ': '
                                                            . date('D d/m/Y', $voucher->dep_date) : '' !!}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <!-- ./Dates and Times -->
                                        </tr>
                                    @endif
                                    @if(!empty($voucher->msg_ins))
                                        <tr>
                                            <!-- Special Instructions -->
                                            <td>
                                                <h4 class="text-primary" style="margin-bottom: 0;"><em><b>Special Instructions</b></em></h4>
                                                <table class="table" style="margin: 0;">
                                                    <tr>
                                                        <td style="padding: 0; width: 15%;">
                                                            <img width="50px" src="{{ $starImg }}" alt="folderImg">
                                                        </td>
                                                        <td style="padding: 0; width: 85%;">
                                                            {!! !empty($voucher->msg_ins) ? html_entity_decode($voucher->msg_ins)  : '' !!}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <!-- ./Special Instructions -->
                                        </tr>
                                    @endif
                                    @if(!empty($voucher->msg_terms))
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
                                                            <p style="font-size: 12px;">{!! !empty($voucher->msg_terms) ? html_entity_decode($voucher->msg_terms)  : '' !!}</p>
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
                                            @if(!empty($voucher->clnt_name) || !empty($voucher->dr_name_order))
                                                <h4 class="text-primary" style="margin-bottom: 0;"><em><b>Client Details</b></em></h4>
                                                <table class="table" style="margin: 0;">
                                                    <tr>
                                                        <td style="padding: 0; width: 15%;">
                                                            <img width="50px" src="{{ $usersImg }}" alt="clientsicon">
                                                        </td>
                                                        <td style="padding: 0; width: 85%;">
                                                            {!! '<b>' . (($voucher->no_pax && $voucher->no_pax > 0) ? $voucher->no_pax . ' Pax: ' : '') .
                                                            $voucher->clnt_name . '</b>' . '<br>' !!}
                                                            {!! ((!empty(trim($voucher->clnt_cellno))) ? 'Cell: ' . $voucher->clnt_cellno : '') . ' ' .
                                                            $voucher->dr_name_order !!}
                                                        </td>
                                                    </tr>
                                                </table>
                                            @endif
                                            <!-- ./Client details -->
                                            <!-- Booking Refs -->
                                            @if(!empty($voucher->our_ref_full) || !empty($voucher->sup_ref))
                                                <h4 class="text-primary" style="margin-bottom: 0;"><em><b>Booking References</b></em></h4>
                                                <table class="table" style="margin: 0;">
                                                    <tr>
                                                        <td style="padding: 0; width: 15%;">
                                                            <img width="50px" src="{{ $folderImg }}" alt="folderImg">
                                                        </td>
                                                        <td style="padding: 0; width: 85%;">
                                                            {!! !empty($voucher->our_ref_full) ? 'Our Ref: ' . $voucher->our_ref_full . '<br>' : '' !!}
                                                            {!! !empty(trim($voucher->sup_ref)) ? 'Your Ref: ' . $voucher->sup_ref : '' !!}
                                                        </td>
                                                    </tr>
                                                </table>
                                            @endif
                                            <!-- ./Booking Refs -->
                                            <!-- Services Required -->
                                            @if(!empty($voucher->serv_full) || !empty($voucher->rate_full))
                                                <h4 class="text-primary" style="margin-bottom: 0;"><em><b>Services Required</b></em></h4>
                                                <table class="table" style="margin: 0;">
                                                    <tr>
                                                        <td style="padding: 0; width: 15%;">
                                                            <img width="50px" src="{{ $calculatorImg }}" alt="calculatorImg">
                                                        </td>
                                                        <td style="padding: 0; width: 85%;">
                                                            {!! !empty($voucher->serv_full) ? $voucher->serv_full . '<br>' : '' !!}
                                                            {!! !empty($voucher->rate_full) ? $voucher->rate_full : '' !!}
                                                        </td>
                                                    </tr>
                                                </table>
                                            @endif
                                            <!-- ./Services Required -->
                                            <!-- Payment -->
                                            @if(!empty($voucher->pmt_serv) || !empty($voucher->pmt_extras))
                                                <h4 class="text-primary" style="margin-bottom: 0;"><em><b>Payment</b></em></h4>
                                                <table class="table" style="margin: 0;">
                                                    <tr>
                                                        <td style="padding: 0; width: 15%;">
                                                            <img width="50px" src="{{ $paymentImg }}" alt="calculatorImg">
                                                        </td>
                                                        <td style="padding: 0; width: 85%;">
                                                            {!! !empty($voucher->pmt_serv) ? 'Booked Services: ' . $voucher->pmt_serv . '<br>' : '' !!}
                                                            {!! !empty($voucher->pmt_extras) ? 'Client Extras: ' . $voucher->pmt_extras : '' !!}
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
                                                        <b><em>Authorized by</em></b> <span class="pull-right"><b><em>(Signed)</em></b></span>
                                                        <br>
                                                        {{ !empty($voucher->footer) ? $voucher->footer : '' }}
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