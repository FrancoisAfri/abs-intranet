@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">

            <!-- HR PEOPLE LIST -->
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Users Search Result</h3>

                    <!--
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                    -->
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

                                  <a href="{{ '/users/' . $person->user_id . '/edit' }}" class="product-title">{{ $person->first_name . ' ' . $person->surname }}</a>

                                        <span class="label {{ ($person->status === 1) ? 'label-success' : 'label-danger' }} pull-right">{{ $status_values[$person->status] }}</span><!-- </a> --> 
                            <span class="product-description">
                                @if(!empty($person->email))
                                    <i class="fa fa-envelope-o"></i> {{ $person->email }}
                                @endif
                                @if(!empty($person->position) && count($positions) > 0)
                                    &nbsp; {{ ' | ' }} &nbsp; <i class="fa fa-user-circle"></i> {{ $positions[$person->position] }}
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
                    <button  id="back_to_employee_search" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back to search</button>
                </div>
                <!-- /.box-footer -->
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection

@section('page_script')
    <script type="text/javascript">
    //Cancel button click event
    document.getElementById("back_to_user_search").onclick = function () {
        location.href = "/hr/emp_document";
    };
    </script>
@endsection