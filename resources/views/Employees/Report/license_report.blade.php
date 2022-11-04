@extends('layouts.main_layout')
@section('page_dependencies')

    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">

@stop
@section('content')
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-barcode pull-right"></i>
                    <h3 class="box-title">License Report </h3>
                </div>
                <div class="box-body">
                    <div style="overflow-X:auto;">
                        <div class="form-group">
                            <form class="form-horizontal" method="get" action="{{ route('licence.report') }}">
                                {{ csrf_field() }}
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="col-sm-4">
                                            <label>License Status</label>
                                            <select class="form-control select2 " style="width: 100%;"
                                                    id="status_id" name="status_id" data-select2-id="1" tabindex="-1"
                                                    aria-hidden="true">
                                                <option value="">** Select a Status **</option>
												<option value="1">Active</option>
												<option value="2">Inactive</option>
                                            </select>
                                        </div>

                                        <div class="col-sm-4">
                                            <label>License Types</label>
                                            <select class="form-control select2 " style="width: 100%;"
                                                    id="license_type" name="license_type" data-select2-id="1"
                                                    tabindex="-1" aria-hidden="true">
                                                <option value="0">** Select License Type **</option>
                                                @foreach( $licenseTypes as $type)
                                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
										<div class="col-sm-4">
                                            <label>Licenses</label>
                                            <select class="form-control select2 " style="width: 100%;"
                                                    id="license_id" name="license_id" data-select2-id="1"
                                                    tabindex="-1" aria-hidden="true">
                                                <option value="0">** Select License **</option>
                                                @foreach( $licenses as $license)
                                                    <option value="{{ $license->id }}">{{ $license->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <br>
                                        <button type="submit" class="btn btn-primary pull-left">Submit</button>
                                        <br>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <br>
                        <table class="table table-bordered user_datatable">
                            <thead>
								<tr>
									<th style="width: 5px; text-align: center;">License Name</th>
									<th style="width: 5px; text-align: center;">License Type</th>
									<th style="width: 5px; text-align: center;">Serial Number</th>
									<th style="width: 5px; text-align: center;">Purchase Date</th>
									<th style="width: 5px; text-align: center;">Expiration Date</th>
									<th style="width: 5px; text-align: center;">Employee</th>
									<th style="width: 5px; text-align: center;">Email</th>
									<th style="width: 5px; text-align: center;">Licence Status</th>
									<th style="width: 5px; text-align: center;">Cost</th>
								</tr>
                            </thead>
                            <tbody>
								@if (count(array($LicenceAllocations)) > 0)
									@foreach ($LicenceAllocations as  $allocations)
										<tr>
											<td style="width: 5px; text-align: center;">{{ (!empty($allocations->Licenses->name)) ?  $allocations->Licenses->name : ''}}</td>
											<td style="width: 5px; text-align: center;">{{ (!empty($allocations->Licenses->LicensesType->name)) ?  $allocations->Licenses->LicensesType->name : ''}}</td>
											<td style="width: 5px; text-align: center;">{{ (!empty($allocations->Licenses->serial)) ?  $allocations->Licenses->serial : ''}} </td>
											<td style="width: 5px; text-align: center;">{{ (!empty($allocations->Licenses->purchase_date)) ?  $allocations->Licenses->purchase_date : ''}} </td>
											<td style="width: 5px; text-align: center;">{{ (!empty($allocations->Licenses->expiration_date)) ?  $allocations->Licenses->expiration_date : ''}} </td>
											<td style="width: 5px; text-align: center;">{{ (!empty($allocations->Hrpersons->first_name)) ?  $allocations->Hrpersons->first_name.' '.$allocations->Hrpersons->surname : ''}} </td>
											<td style="width: 5px; text-align: center;">{{ (!empty($allocations->Hrpersons->email)) ?  $allocations->Hrpersons->email : ''}} </td>
											<td style="width: 5px; text-align: center;">{{ (!empty($allocations->Licenses->status)) && ($allocations->Licenses->status == 1)  ? 'Active': 'De Activated' }}											</td>
											<td style="width: 5px; text-align: center;">R {{ (!empty($allocations->Licenses->purchase_cost)) ?  number_format($allocations->Licenses->purchase_cost, 2) : ''}} </td>
										</tr>
									@endforeach
								@endif
                            </tbody>
                            <tfoot>
								<tr>
									<th style="width: 5px; text-align: center;">License Name</th>
									<th style="width: 5px; text-align: center;">License Type</th>
									<th style="width: 5px; text-align: center;">Serial Number</th>
									<th style="width: 5px; text-align: center;">Purchase Date</th>
									<th style="width: 5px; text-align: center;">Expiration Date</th>
									<th style="width: 5px; text-align: center;">Employee</th>
									<th style="width: 5px; text-align: center;">Email</th>
									<th style="width: 5px; text-align: center;">Licence Status</th>
									<th style="width: 5px; text-align: center;">Cost</th>
								</tr>
								<tr>
									<th colspan="8" style="text-align:right;"> Total Cost</th> 
									<th style="width: 5px; text-align: center;">R {{!empty($totalCost) ? number_format($totalCost, 2) : 0}} </th>
								</tr>
                            </tfoot>
                        </table>
                        <!-- /.box-body -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('page_script')
    <!-- DataTables -->
    {{--    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js"') }}"></script>--}}
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('custom_components/js/modal_ajax_submit.js') }}"></script>
    <script src="{{ asset('custom_components/js/deleteAlert.js') }}"></script>
    <script src="{{ asset('bower_components/bootstrap_fileinput/js/fileinput.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
    <!-- End Bootstrap File input -->
    <script type="text/javascript">
        $(function () {
            $('.user_datatable').DataTable({
                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });

        });
    </script>
@stop

