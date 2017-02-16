<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ $company_name }} | Statement</title>
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
      <div class="col-sm-4 invoice-col">
       From
        <address>
          <strong>{{ $company_name }}</strong><br>
          Postnet Suite 136<br>
          Private Bag X2600<br>
		  Houghton, Gauteng 2041 ZA<br>
          0114869000<br>
          info@laxmigroup.co.za<br>
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        To
        <address>
          <strong>{{ $client->first_name . ' ' . $client->surname }}</strong><br>
          {{ $client->res_address . ' '. $client->res_suburb}}<br>
          {{ $client->res_city}}<br>
          {{ $client->res_postal_code}}<br>
          {{ $client->cell_number}}<br>
		  {{ $client->email}}<br><br>
        </address>
      </div>
      <!-- /.col -->
       <div class="col-sm-4 invoice-col">
       <br> <b>Loan No: {{$loan->loan_number}}</b><br>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
	 <div class="row">
		<div class="col-sm-6">
			<div class="row">
				<div class="col-xs-6">
					<h5>Loan Amount:</h5>
					<h5>Current Prime Rate:</h5>
					<h5>Interest Rate:</h5>
					<h5>Term:</h5>
				</div>
				<div class="col-xs-6">
					<h5><b>{{ !empty($loan->amount_wanted) ? 'R ' . number_format($loan->amount_wanted, 2) : '&nbsp;' }}</b></h5>
					<h5>{{ !empty($prime_rate->prime_rate) ? number_format($prime_rate->prime_rate, 2) . ' %' : '&nbsp;' }}</h5>
					<h5>{{ $interest_rate }}</h5>
					<h5>{{ $term . ' Year(s)' }}</h5>
				</div>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="row">
				<div class="col-xs-6">
					<h5>Loan Start Date:</h5>
					<h5>Monthly Instalment:</h5>
					<h5>Number of Payments:</h5>
					<h5>Current Balance:</h5>
				</div>
				<div class="col-xs-6">
					<h5>{{ isset($approval_date) ? $approval_date->format('d/m/Y') : '&nbsp;' }}</h5>
					<h5>{{ isset($month_instalment) ? 'R ' . number_format($month_instalment, 2) : '&nbsp;' }}</h5>
					<h5>{{ isset($num_payments) ? $num_payments : '&nbsp;' }}{{ isset($num_rem_payments) ? " (". $num_rem_payments . " remaining)" : '' }}</h5>
					<h5><b>{{ isset($tot_loan_cost) ? 'R ' . number_format($tot_loan_cost, 2) : '&nbsp;' }}</b></h5>
				</div>
			</div>
		</div>
	 </div>
	<table class="table table-striped">
		<tr>
			<th style="width: 10px">#</th>
			<th>Date</th>
			<th>Activity</th>
			<th style="text-align: right;">Amount</th>
			<th style="text-align: right;">Balance</th>
		</tr>
		@if(count($loan_statement) > 0)
			@foreach($loan_statement as $statement)
				<tr>
					<td>{{ $statement->index }}</td>
					<td nowrap>{{ (!empty($statement->date)) ? $statement->date->format('d/m/Y') : '' }}</td>
					<td>{{ $statement->activity }}</td>
					<td style="text-align: right;" nowrap>{{ ($statement->isPayment) ? '-' : '' }}R {{ number_format($statement->amount, 2) }}</td>
					<td style="text-align: right;" nowrap>R {{ number_format($statement->balance, 2) }}</td>
				</tr>
			@endforeach
		@endif
		<tr class="bg-orange">
			<td><i class="fa fa-info-circle" data-toggle="tooltip" title="This payment must be made before this date: {{ $next_pmt_date->format('d/m/Y') }}" data-placement="right"></i></td>
			<td nowrap>{{ $next_pmt_date->format('d/m/Y') }} *</td>
			<td>Next Scheduled Payment</td>
			<td style="text-align: right;" nowrap>{{ isset($next_pmt_amt) ? 'R ' . number_format($next_pmt_amt, 2) : '&nbsp;' }}</td>
			<td style="text-align: right;" nowrap></td>
		</tr>
	</table>
	@if (($user->type != 2))
		<table class="table table-striped">
		<tr><th colspan="6">Amortization Schedule</th></tr>
		<tr>
			<th>Date</th>
			<th style="text-align: right;">Beginning Balance</th>
			<th style="text-align: right;">Total Payment</th>
			<th style="text-align: right;">Principal</th>
			<th style="text-align: right;">Interest</th>
			<th style="text-align: right;">Ending Balance</th>
		</tr>
		@if(count($loan_amortization) > 0)
			@foreach($loan_amortization as $amor)
				<tr>
					<td nowrap>{{ $amor['amr_date']->format('d/m/Y') }}</td>
					<td style="text-align: right;" nowrap>R {{ number_format($amor['beginning_bal'], 2) }}</td>
					<td style="text-align: right;" nowrap>R {{ number_format($amor['total_pmt'], 2) }}</td>
					<td style="text-align: right;" nowrap>R {{ number_format($amor['capital'], 2) }}</td>
					<td style="text-align: right;" nowrap>R {{ number_format($amor['interest'], 2) }}</td>
					<td style="text-align: right;" nowrap>R {{ number_format(round($amor['ending_bal'], 2), 2) }}</td>
				</tr>
			@endforeach
		@endif
		</table>
	@endif
    <div class="row">
      <!-- accepted payments column -->
      <div class="col-xs-12">
        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
          Note
        </p>
      </div>
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>
