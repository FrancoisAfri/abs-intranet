@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <!-- Search User Form -->
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-search pull-right"></i>
                    <h3 class="box-title">Send Message</h3>
                    <p>Select clients to send the message to:</p>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="/contacts/send-message">
                    {{ csrf_field() }}

                    <div class="box-body">
                        <div class="form-group{{ $errors->has('clients') ? ' has-error' : '' }}">
                            <label for="clients" class="col-sm-2 control-label">Client(s)</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-users"></i>
                                    </div>
                                    <select name="clients[]" id="clients" class="form-control select2" multiple data-placeholder="*** Select a Client ***">
                                        @foreach($contactPersons as $contactPerson)
                                            <option value="{{ $contactPerson->id . '|' . $contactPerson->email . '|' . $contactPerson->cell_number }}">{{ $contactPerson->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('sms_content') ? ' has-error' : '' }}">
                            <label for="sms_content" class="col-sm-2 control-label">Message</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-comments-o"></i>
                                    </div>
                                    <textarea name="sms_content" id="sms_content" class="form-control" placeholder="Message" rows="3" maxlength="180">{{ old('sms_content') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-paper-plane-o"></i> Send</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
                <!-- End Form-->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col-md-12 -->
    </div>
@endsection

@section('page_script')
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>

    <script type="text/javascript">
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        });
    </script>
@endsection