<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Stock Delivery Note Printed By {{ $user->person->first_name.' '. $user->person->surname }}</title>
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
			<h3 class="box-title">Request #: {{$stock->request_number}}</h3>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <div class="row">
            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
            <div class="box-body">
				<table class="table table-striped table-bordered">
					<tr>
						<td class="caption"><b>Delivery #</b></td>
						<td>{{ !empty($stock->delivery_number) ? date(' d M Y', $stock->delivery_number) : '' }}</b></td>
						<td class="caption"><b>Invoice #:</b></td>
						<td>{{ !empty($stock->invoice_number) ? $stock->invoice_number : '' }}</b></td>
					</tr>
					<tr>
						<td class="caption"><b>Date Requeted:</b></td>
						<td>{{ !empty($stock->date_created) ? date(' d M Y', $stock->date_created) : '' }}</b></td>
						<td class="caption"><b>Title:</b></td>
						<td>{{ !empty($stock->title_name) ? $stock->title_name : '' }}</b></td>
					</tr>
					<tr>
						<td class="caption"><b>Requested By:</b></td>
						<td>{{ (!empty($stock->employees)) ?  $stock->employees->first_name . ' ' .  $stock->employees->surname : ''}}</b></td>
						<td class="caption"><b>On Behalf Of:</td>
						<td>{{ (!empty($stock->employeeOnBehalf)) ?  $stock->employeeOnBehalf->first_name . ' ' .  $stock->employeeOnBehalf->surname : ''}}</b></td>
					</tr>
				</table>
				<table class="table table-striped table-bordered">
					<hr class="hr-text" data-content="Stock Items">
					<tr>
						<td>#</td>
						<td><b>Category</b></td>
						<td><b>Product</b></td>
						<td style="text-align:center"><b>Quantity</b></td>
					</tr>
					@if (count($stock->stockItems) > 0)
						@foreach ($stock->stockItems as $item)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{ !empty($item->categories->name) ? $item->categories->name : '' }}</td>
								<td>{{ !empty($item->products->name) ? $item->products->name : '' }}</td>
								<td style="text-align:center">{{ !empty($item->quantity) ? $item->quantity : '' }}</td>
							</tr>
						@endforeach
					@else
						<tr><td colspan="4"></td></tr>
					@endif
				</table></br></br></br></br>
				<table class="table table-striped">
					<tr>
						<td colspan="2"><b><u>Stock Controller Signature</u></b></td>
						<td colspan="2"><b><u>Employee Signature</u></b></td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</td>
						<td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</td>
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