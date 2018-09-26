@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap datepicker -->
    <!-- Include Date Range Picker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/datepicker/datepicker3.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Inspection Documents/Images</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                <strong class="lead">Vehicle Details</strong><br>

                                @if(!empty($vehiclemaker))
                                    | &nbsp; &nbsp; <strong>Vehicle Make:</strong> <em>{{ $vehiclemaker->name }}</em>
                                    &nbsp;
                                    &nbsp;
                                @endif
                                @if(!empty($vehiclemodeler))
                                    -| &nbsp; &nbsp; <strong>Vehicle Model:</strong>
                                    <em>{{ $vehiclemodeler->name }}</em>
                                    &nbsp; &nbsp;
                                @endif
                                @if(!empty($vehicleTypes))
                                    -| &nbsp; &nbsp; <strong>Vehicle Type:</strong> <em>{{ $vehicleTypes->name }}</em>
                                    &nbsp;
                                    &nbsp;
                                @endif
                                @if(!empty($ispection->vehicle_registration))
                                    -| &nbsp; &nbsp; <strong>Vehicle Registration:</strong>
                                    <em>{{ $ispection->vehicle_registration }}</em> &nbsp; &nbsp;
                                @endif
                                @if(!empty($ispection->year))
                                    -| &nbsp; &nbsp; <strong>Year:</strong> <em>{{ $ispection->year }}</em> &nbsp;
                                    &nbsp;
                                @endif
                                @if(!empty($ispection->vehicle_color))
                                    -| &nbsp; &nbsp; <strong>Vehicle Color:</strong>
                                    <em>{{ $ispection->vehicle_color }}</em> &nbsp; &nbsp; -|
                                @endif

                            </p>
                        </div>
                    </div>
                    <!--  -->
                    <table class="table table-striped table-bordered">
                        <tr><th>Inspection Out</td><th>Inspection In</td></tr>
                        <tr><th colspan="2" style="text-align:center"> Document(s)</td></tr>
						<tr>
							@if(!empty($vehiclecollectdocuments))
								<td>
									@foreach ($vehiclecollectdocuments as $collectdocument)
											@if(!empty($collectdocument->id))
												<a class="btn btn-default btn-flat btn-block pull-right"
												   href="{{ Storage::disk('local')->url("Vehicle/collectiondocuments/$collectdocument->document") }}"
												   target="_blank"><i class="fa fa-file-pdf-o"></i> View Document</a>
											@else
												<a class="btn btn-default pull-centre "><i
															class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
											@endif
									@endforeach
								</td>
							@else
								<td><em>Nothing Uploaded</em></td>
							@endif
							@if(!empty($vehiclereturndocuments))
								<td>
									@foreach ($vehiclereturndocuments as $returndocuments)
											@if(!empty($returndocuments->id))
														<a class="btn btn-default btn-flat btn-block pull-right "
												   href="{{Storage::disk('local')->url("Vehicle/returndocuments/$returndocuments->document") }}"
												   target="_blank"><i class="fa fa-file-pdf-o"></i> View Document</a>
											@else
												<a class="btn btn-default pull-centre "><i
															class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
											@endif
									@endforeach
								</td>
							@else
								<td><em>Nothing Uploaded</em></td>
							@endif
                        </tr>
						<tr><th colspan="2" style="text-align:center"> Image(s)</td></tr>
                        <tr>
							@if(!empty($vehiclecollectimage))
								<td>
									@foreach ($vehiclecollectimage as $collectimage)
										<div class="product-img">
											<img src="{{ (!empty($collectimage->image)) ? Storage::disk('local')->url("Vehicle/collectionimages/$collectimage->image") : 'http://placehold.it/60x50' }}"
										  alt="Product Image"width="250" height="120">
										</div>
									@endforeach
								</td>
							@else
								<td><em>Nothing Uploaded</em></td>
							@endif
							@if(!empty($vehiclereturnimages))
								<td>
									@foreach ($vehiclereturnimages as $returnimage)
									   <div class="product-img">
											<img src="{{ (!empty($returnimage->image)) ? Storage::disk('local')->url("Vehicle/returnImages/$returnimage->image") : 'http://placehold.it/60x50' }}"
										  alt="Product Image"width="250" height="120">
										</div>
									@endforeach
								</td>
							@else
								<td><em>Nothing Uploaded</em></td>
							@endif
                        </tr>
                    </table>
                    <!--   </div> -->
                    <!-- /.box-body -->
                    <div class="box-footer">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page_script')       
@endsection
