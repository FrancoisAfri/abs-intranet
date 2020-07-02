<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Procument Request Printed By {{ $user->person->first_name.' '. $user->person->surname }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
   -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/dist/css/AdminLTE.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<style type="text/css" media="print">
@page { size: landscape; }
</style>
</head>
<body onload="window.print();">
<div class="wrapper">
    <!-- Main content -->
    <section class="invoice">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <img width="196" height="60" src="{{ $company_logo }}" alt="logo">
                    <small class="pull-right">Date: {{$date}}</small>
                </h2>
            </div>
            <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-8 invoice-col">
                <address>
                    <strong>{{ $company_name }}</strong><br>
                </address>
            </div>
			<h3 class="box-title">Request #: {{$procurement->po_number}}</h3>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <div class="row">
            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
            <div class="box-body">
				<table class="table table-striped table-bordered">
					<tr>
						<td><b>Date Requeted:</b></td>
						<td>{{ !empty($procurement->date_created) ? date(' d M Y', $procurement->date_created) : '' }}</b></td>
						<td><b>Title:</b></td>
						<td>{{ !empty($procurement->title_name) ? $procurement->title_name : '' }}</b></td>
					</tr>
					<tr>
						<td><b>Employee:</b></td>
						<td>{{ (!empty($procurement->employees)) ?  $procurement->employees->first_name . ' ' .  $procurement->employees->surname : ''}}</b></td>
						<td><b>On Behalf Of:</td>
						<td>{{ (!empty($procurement->employeeOnBehalf)) ?  $procurement->employeeOnBehalf->first_name . ' ' .  $procurement->employeeOnBehalf->surname : ''}}</b></td>
					</tr>
					<tr>
						<td><b>Detail of Expenditure:</b></td>
						<td>{{ (!empty($procurement->detail_of_expenditure)) ?  $procurement->detail_of_expenditure : ''}}</b></td>
						<td><b>Justification of Expenditure:</b></td>
						<td>{{ !empty($procurement->justification_of_expenditure) ? $procurement->justification_of_expenditure : '' }}</b></td>
					</tr>
					<tr>
						<td><b>Special Instructions:</b></td>
						<td>{{ (!empty($procurement->special_instructions)) ?  $procurement->special_instructions : ''}}</b></td>
						<td><b>Status:</b></td>
						<td>{{ !empty($procurement->status) ? $procurement->requestStatus->step_name : '' }}</b></td>
					</tr>
					<tr>
						<td><b>Rejection Reason:</b></td>
						<td></b></td>
						<td><b>PO Number:</b></td>
						<td>{{ (!empty($procurement->po_number)) ?  $procurement->po_number : ''}}</td>
					</tr>
				</table>
				<table class="table table-striped table-bordered">
					<hr class="hr-text" data-content="Procurement Request Items">
					<tr>
						<td>#</td>
						<td><b>Category</b></td>
						<td><b>Product</b></td>
						<td style="text-align:center"><b>Quantity</b></td>
						<td style="text-align:center"><b>Price</b></td>
						<td></td>
					</tr>
					@if (count($procurement->procurementItems) > 0)
						@if ($procurement->item_type == 1)
							@foreach ($procurement->procurementItems as $items)
								<tr>
									<td>{{ $loop->iteration }}</td>
									<td>{{ !empty($items->categories->name) ? $items->categories->name : '' }}</td>
									<td>{{ !empty($items->products->name) ? $items->products->name : '' }}</td>
									<td style="text-align:right">{{ !empty($items->quantity) ? $items->quantity : '' }}</td>
									<td style="text-align:right">{{ !empty($items->item_price) ? $items->item_price : '' }}</td>
									<td>
										@if ($procurement->status == 1)
											<button type="button" class="btn btn-danger btn-xs" data-toggle="modal"
												data-target="#remove-items-warning-modal"
												data-id="{{ $items->id }}"><i class="fa fa-trash"></i>  Remove
											</button>
										@endif
									</td>
								</tr>
							@endforeach
						@else
							@foreach ($procurement->procurementItems as $items)
								<tr>
									<td>{{ $loop->iteration }}</td>								<td>N/A</td>
									<td>{{ !empty($items->item_name) ? $items->item_name : '' }}</td>
									<td style="text-align:center">{{ !empty($items->quantity) ? $items->quantity : '' }}</td>
									<td style="text-align:center">{{ !empty($items->item_price) ? $items->item_price : '' }}</td>
									<td>
										@if ($procurement->status == 1)
											<button type="button" class="btn btn-danger btn-xs" data-toggle="modal"
												data-target="#remove-items-warning-modal"
												data-id="{{ $items->id }}"><i class="fa fa-trash"></i>  Remove
											</button>
										@endif
									</td>
								</tr>
							@endforeach
						@endif
					@else
						<tr><td colspan="5"></td><td style="text-align:center">{{ !empty($items->item_price) ? $items->item_price : '' }}</td><td></td></tr>
					@endif
					<tr>
						<th style="text-align: center;"  colspan="5">Totals</th>
					</tr>
					<tr>
						<th style="text-align: right;"  colspan="4">Subtotal:</th>
						<td style="text-align: right;" id="subtotal" nowrap>{{ 'R ' . number_format($subtotal, 2) }}</td>
						<td></td>
					</tr>
					<tr>
						<th style="text-align: right; vertical-align: middle;"  colspan="4">VAT:</th>
						<td style="text-align: right; vertical-align: middle;" id="vat-amount" nowrap>{{ ($vatAmount > 0) ? 'R ' . number_format($vatAmount, 2) : '&mdash;' }}</td>
						<td></td>
					</tr>
					<tr>
						<th style="text-align: right; vertical-align: middle;"  colspan="4">Total:</th>
						<td style="text-align: right; vertical-align: middle;" id="total-amount" nowrap>{{ 'R ' . number_format($total, 2) }}</td>
						<td></td>
					</tr>
				</table>
            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>