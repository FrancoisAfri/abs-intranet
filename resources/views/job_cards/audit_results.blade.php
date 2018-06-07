@extends('layouts.main_layout')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Audit Report</h3>
                </div>
                <!-- /.box-header -->
                <form class="form-horizontal" method="POST" action="/jobcards/audits/print">

                    <input type="hidden" name="card_id" value="{{!empty($card_id) ? $card_id : ''}}">

                    {{ csrf_field() }}
                    <div class="box-body">
                        <!-- Collapsible section containing the amortization schedule -->
                        <div class="box-group" id="accordion">
                            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                            <div class="box-body">
                                <table class="table table-striped">
                                    <tr>
                                        <th>Module Name</th>
                                        <th>User</th>
                                        <th>Action</th>
                                        <th>Action Date</th>
                                        <th>Notes</th>
                                    </tr>
                                    @if(!empty($audits))
                                        @foreach($audits as $audit)
                                            <tr>
                                                <td>{{ !empty($audit->module_name) ? $audit->module_name : '' }}</td>
                                                <td>{{ !empty($audit->firstname) && !empty($audit->surname) ? $audit->firstname.' '.$audit->surname : '' }}</td>
                                                <td>{{ !empty($audit->action) ? $audit->action : '' }}</td>
                                                <td>{{ !empty($audit->action_date) ? date('Y M d : H : i : s', $audit->action_date) : '' }}</td>
                                                <td>{{ !empty($audit->notes) ? $audit->notes : '' }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </table>
                                <div class="row no-print">
                                    <div class="col-xs-12">
                                        <button type="submit" class="btn btn-primary pull-right"><i
                                                    class="fa fa-print"></i>Print report
                                        </button>
                                    </div>
                                </div>
                                <!-- End amortization /table -->
                            </div>
                        </div>
                        <!-- /. End Collapsible section containing the amortization schedule -->
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection