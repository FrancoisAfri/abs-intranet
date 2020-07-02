@extends('layouts.main_layout')

@section('page_dependencies')
    <!-- iCheck -->
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/green.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- HR PEOPLE LIST -->
            <div class="box box-success">
                <form class="form-horizontal" method="POST" action="/users/search/activate">
                    {{ csrf_field() }}

                    <div class="box-header with-border">
                        <h3 class="box-title">Users Search Result</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        @if(!(count($persons) > 0))
                            <div class="callout callout-danger">
                                <h4><i class="fa fa-database"></i> No Records found</h4>

                                <p>No user matching your search criteria in the database. Please refine your search parameters.</p>
                            </div>
                        @endif
                        <ul class="products-list product-list-in-box">
                            <!-- item -->
                            @foreach($persons as $person)
                                <li class="item">
                                    <div class="product-img">
                                        <img src="{{ (!empty($person->profile_pic)) ? Storage::disk('local')->url("avatars/$person->profile_pic") : (($person->gender === 0) ? $f_silhouette : $m_silhouette) }}" alt="Profile Picture">
                                    </div>
                                    <div class="product-info">
                                        <a href="{{ '/users/' . $person->user_id . '/edit' }}" class="product-title">{{ $person->first_name . ' ' . $person->surname }}</a>
                                        <label class="radio-inline pull-right" style="padding-left: 0px;"><input class="rdo-iCheck" type="checkbox" id="{{ $person->user_id . '_rdo_status_active' }}" name="{{ "status[" . $person->user_id . "]" }}" value="1" {{ $person->status == 1 ? ' checked' : '' }}> <span class="label {{ ($person->status === 1) ? 'label-success' : 'label-danger' }}">Active</span></label>
                                        <input type="hidden" id="{{ $person->user_id . '_rdo_status_inactive' }}" name="{{ "status[" . $person->user_id . "]" }}" value="0" {{ $person->status == 1 ? ' disabled' : '' }}>
                                        <span class="product-description">
                                            @if(!empty($person->email))
                                                <i class="fa fa-envelope-o"></i> {{ $person->email }}
                                            @endif
                                            @if(!empty($person->jobTitle->name))
                                                &nbsp; {{ ' | ' }} &nbsp; <i class="fa fa-user-circle"></i> {{ $person->jobTitle->name }}
                                            @endif
                                        </span>
                                    </div>
                                </li>
                        @endforeach
                        <!-- /.item -->
                        </ul>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button id="back_to_user_search" type="button" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back to search</button>
                        <button type="submit" id="save_changes" class="btn btn-primary pull-right"><i class="fa fa-floppy-o"></i> Save Changes</button>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection

@section('page_script')
    <!-- iCheck -->
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>

    <script type="text/javascript">
        $(function () {
            //Cancel button click event
            document.getElementById("back_to_user_search").onclick = function () {
                location.href = "/users";
            };

            //Initialize iCheck/iRadio Elements
            $('.rdo-iCheck').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
                increaseArea: '20%' // optional
            });

            //checkbox on click handler
            @foreach($persons as $person)
                $("#"+'{{ $person->user_id . '_rdo_status' }}'+"_active").on("ifChecked", function (e) {
                    //console.log('gets here: checked' + '#'+'{{ $person->user_id . '_rdo_status' }}'+'_inactive');
                    $('#'+'{{ $person->user_id . '_rdo_status' }}'+'_inactive').attr('disabled', true);
                });
                $("#"+'{{ $person->user_id . '_rdo_status' }}'+"_active").on("ifUnchecked", function (e) {
                    $('#'+'{{ $person->user_id . '_rdo_status' }}'+'_inactive').attr('disabled', false);
                });
            @endforeach
        });
    </script>
@endsection