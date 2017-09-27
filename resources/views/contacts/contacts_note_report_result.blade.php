@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <form class="form-horizontal" method="POST" action="">
                    {{ csrf_field() }}
                    <div class="box-header with-border">
                         <i class="fa fa-file-text-o pull-right"></i>
                        <h3 class="box-title">Notes Details</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-ban"></i> Invalid Input Data!</h4>
                                <ul>
                                   
                                </ul>
                            </div>
                        @endif
                        <div style="overflow-x:auto;">
                          <!--   <h4 style="text-align: center; class="box-title">Company Details</h4> -->
                                
                                 <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                    <strong class="lead">Company Details</strong><br>
                                  
                                    @if(!empty($companyname))
                                    <strong>Company Name :</strong> <em>{{ $companyname }}</em> &nbsp; &nbsp;
                                    @endif
                                </p>

                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th style="width: 10px"></th>
                                     <th>Client name</th>
                                    <th>Notes</th>
                                    <th>Next Action</th>
                                   <th>Follow Up Date</th>
                                </tr>
                               
                            <tbody>
                                    <!-- loop through the leave application info   -->
                                    @if(count($notes) > 0)

                                    @endif
                                <ul class="products-list product-list-in-box">
                                      @foreach($notes as $notereport)
                                   <tr>
                                    <td></td>
                                  <td>{{ !empty($notereport->name) && !empty($notereport->surname) ? $notereport->name.' '.$notereport->surname : '' }}</td>
                                  <td>{{ !empty($notereport->notes) ? $notereport->notes : '' }}</td>
                                  <td>{{ (!empty($notereport->next_action)) ?  $notesStatus[$notereport->next_action] : ''}} </td>
                                  <td>{{ !empty($notereport->follow_date) ? date('d M Y ', $notereport->follow_date) : '' }}</td>
                                        @endforeach
                                </tbody>      
                              </table>
                            </div>
                         <!--  -->
                    
                  
                   <div class="box-footer no-print">
                    <button type="button" class="btn btn-default pull-left" id="back_button"><i class="fa fa-arrow-left"></i> Back</button>
                    <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print Report</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page_script')

    <script>
       $(function () {
            //Cancel button click event
            $('#back_button').click(function () {
                location.href = '/contacts/Clients-reports';
            });
        });
      
    </script>
@endsection
