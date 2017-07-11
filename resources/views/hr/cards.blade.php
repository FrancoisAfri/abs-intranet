@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">

            <!-- HR PEOPLE LIST -->
           <div class="col-md-7 col-md-offset-2">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                 <i class="fa fa-search pull-right"></i>
                    <h3 class="box-title">Business Cards</h3>
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
                        <!-- <div align="right">
                           <input type="checkbox" onclick="toggle(this);" /> Check all?<br />
                           </div> -->
                        @foreach($persons as $person)
                            <li class="item">
                                <div class="product-img">
                                    <img src="{{ (!empty($person->profile_pic)) ? Storage::disk('local')->url("avatars/$person->profile_pic") : (($person->gender === 0) ? $f_silhouette : $m_silhouette) }}" alt="Profile Picture">
                                </div>
                                <div class="product-info">
                                    <a href="{{ '/users/' . $person->user_id . '/edit' }}" class="product-title">{{ $person->first_name . ' ' . $person->surname }}</a>
                                   <!--  <span class="label {{ ($person->status === 1) ? 'label-success' : 'label-danger' }} pull-right">{{ $status_values[$person->status] }}</span> -->

                                    <span class="chkCheckbox pull-right "> 
                                         
                            <button type="button" id="view_ribbons" class="btn {{ (!empty($person->card_status) && $person->card_status == 1) ? " btn-danger " : "btn-success " }}
                              btn-xs" onclick="postData({{$person->id}}, 'actdeac');"><i class="fa {{ (!empty($person->card_status) && $person->card_status == 1) ?
                              " fa-times " : "fa-check " }}"></i> {{(!empty($person->card_status) && $person->card_status == 1) ? "De-Activate" : "Activate"}}</button>
                                    </span>

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
                    <button id="back_to_user_search" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back to search</button>
                  
                  <!--   <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-check"></i> Submit</button> -->
                </div>
                <!-- /.box-footer -->
           
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection
<script src="/custom_components/js/modal_ajax_submit.js"></script>
@section('page_script')
    <script type="text/javascript">

    //
     function postData(id, data) {
      
         if (data == 'actdeac') 
            location.href = "/hr/card_active/" + id; 
    }

   
	//Cancel button click event
	document.getElementById("back_to_user_search").onclick = function () {
		location.href = "/hr/business_card";
	};

    function toggle(source) {
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i] != source)
            checkboxes[i].checked = source.checked;
    }
}
    </script>
@endsection