@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <!-- CONTACTS (CLIENTS) LIST -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Clients Search Result</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                        <strong class="lead">Search Parameters</strong><br>
                        <strong>Client Name:</strong> <em>{{ empty($personName) ? '[all]' : $personName }}</em> &nbsp; &nbsp;
                        | &nbsp; &nbsp; <strong>ID Number:</strong> <em>{{ empty($personIDNum) ? '[all]' : $personIDNum }}</em> &nbsp; &nbsp;
                        | &nbsp; &nbsp; <strong>Passport Number:</strong> <em>{{ empty($personPassportNum) ? '[all]' : $personPassportNum }}</em> &nbsp; &nbsp;
                        | &nbsp; &nbsp; <strong>Company:</strong> <em>
                            @if(empty($personCompanyName))
                                [all]
                            @else
                                {{ $personCompanyName . ' ' }}
                                <a href="{{ "/contacts/company/$personCompanyID/view" }}" class="btn btn-xs btn-link no-print"><i class="fa fa-eye"></i> View Company</a>
                            @endif
                        </em> &nbsp; &nbsp;
                    </p>
                    @if(!(count($persons) > 0))
                        <div class="callout callout-danger">
                            <h4><i class="fa fa-database"></i> No Records found</h4>

                            <p>No client matching your search criteria in the database. Please make sure there are clients registered in the system and refine your search parameters.</p>
                        </div>
                    @endif
                    <ul class="products-list product-list-in-box">
                        <!-- item -->
                        @foreach($persons as $person)
                            <li class="item">
                                <div class="product-img">
                                    <img src="{{ $person->profile_pic_url }}" alt="Profile Picture">
                                </div>
                                <div class="product-info">
                                    <a href="{{ '/contacts/' . $person->id . '/edit' }}" class="product-title">{{ $person->full_name }}</a>
                                    <span class="label {{ ($person->status === 1) ? 'label-success' : 'label-danger' }} pull-right">{{ $status_values[$person->status] }}</span><!-- </a> -->
                        <span class="product-description">
                          {{ $person->email }}
                            {{ (!empty($person->position)) ? " ($person->position)" : '' }}
                        </span>
                                </div>
                            </li>
                            @endforeach
                                    <!-- /.item -->
                    </ul>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button id="back_to_contact_search" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back to search</button>
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
        document.getElementById("back_to_contact_search").onclick = function () {
            location.href = "/contacts";
        };
    </script>
@endsection