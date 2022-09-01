@extends('layouts.main_layout')
@section('content')

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header">
                        <!-- tools box -->
                        <div class="pull-right box-tools">
                            <button type="button" class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                        <!-- /. tools -->
                    </div>
                    <!-- /.box-header -->
{{--                    <div class="box-body pad align="center">--}}
                    <div align="center" class="box-body pad>
                    <div class="box-header with-border">
                        <h3 class="box-title">Asset Management</h3>
                    </div>
                <div class="row">
                    <div class="col"><hr></div>
                </div>

                <div align="center" class="box-body pad">
                            <div class="box-body">
                                <a href="{{ route('type.index') }}" class="btn btn-app">
                                    <i class="fa fa-barcode"></i>  Asset types
                                </a>

                                <a href="{{ route('licence.index') }}" class="btn btn-app">
                                    <i class="fa  fa-newspaper-o"></i>  Licence  types
                                </a>

                                <a href="{{ route('store-room.index') }}" class="btn btn-app">
                                    <i class="fa fa-simplybuilt" ></i> Store Room
                                </a>

                            </div>
                        </div>
                    </div>
                </div>

    </section>
@stop
