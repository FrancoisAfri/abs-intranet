@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <form class="form-horizontal" method="POST" action="">
                    {{ csrf_field() }}
                    <div class="box-header with-border">
                        <h3 class="box-title">Account Details</h3>
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

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th rowspan="2" width="5px" style="vertical-align: middle;"><h1 class="no-margin"><i class="fa fa-id-badge"></i></h1></th>
                                    <th>Account Number</th>
                                    <th rowspan="2" width="5px" style="vertical-align: middle;"><h1 class="no-margin"><i class="fa fa-building-o"></i></h1></th>
                                    <th>Company</th>
                                    <th rowspan="2" width="5px" style="vertical-align: middle;"><h1 class="no-margin"><i class="fa fa-user"></i></h1></th>
                                    <th>Contact Person</th>
                                    <th rowspan="2" width="5px" style="vertical-align: middle;"><h1 class="no-margin"><i class="fa fa-calendar-o"></i></h1></th>
                                    <th>Date Created</th>
                                    <th rowspan="2" width="5px" style="vertical-align: middle;"><h1 class="no-margin"><i class="fa fa-info-circle"></i></h1></th>
                                    <th>Status</th>
                                </tr>
                                <tr>
                                    <td>{{ ($account->account_number) ? $account->account_number : '' }}</td>
                                    <td>{{ ($account->company) ? $account->company->name : '[individual]' }}</td>
                                    <td>{{ ($account->client) ? $account->client->full_name : '' }}</td>
                                    <td>{{ ($account->start_date) ? date('d/m/Y', $account->start_date) : '' }}</td>
                                    <td>{{ $account->str_status }}</td>
                                </tr>
                            </table>
                        </div>

                        <hr class="hr-text" data-content="PURCHASES">

                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                <tr>
                                    <td></td>
                                    <th>Quote #</th>
                                    <th>Date Ordered</th>
                                    <th>Payment Option</th>
                                    <th>Status</th>
                                    <th class="text-right">Cost</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($account->quotations as $quotation)
                                    <tr>
                                        <td width="5px"><i class="fa fa-caret-down"></i></td>
                                        <td><a href="/">{{ ($quotation->quote_number) ? $quotation->quote_number : $quotation->id }}</a></td>
                                        <td>{{ $quotation->created_at }}</td>
                                        <td>{{ $quotation->str_payment_option }}</td>
                                        <td><span class="label label-{{ $labelColors[$quotation->status] }}">{{ $purchaseStatus[$quotation->status] }}</span></td>
                                        <td class="text-right"></td>
                                    </tr>
                                    @if($quotation && (count($quotation->products) > 0 || count($quotation->packages) > 0))
                                        <tr>
                                            <td></td>
                                            <td class="warning" colspan="5">
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
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer text-center">
                        <a href="" class="btn btn-success"><i class="fa fa-credit-card"></i> Capture Payment</a>
                        <a href="" class="btn btn-primary"><i class="fa fa-print"></i> Send Invoice</a>
                        <a href="" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print Invoice</a>
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
    <!-- date picker -->
    <script src="/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

    <script>
        $(function () {
        });
    </script>
@endsection
