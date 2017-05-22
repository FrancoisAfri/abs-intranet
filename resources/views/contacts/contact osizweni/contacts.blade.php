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
                                <div class="product-info">
                                    <a href="{{ '/contacts/' . $person->id . '/edit' }}" class="product-title">{{ $person->first_name . ' ' . $person->surname }}</a>
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