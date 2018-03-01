@extends('layouts.main_layout')
@section('page_dependencies')
    <!-- bootstrap file input -->
    <link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet"
          type="text/css"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
          rel="stylesheet">
    <link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css"/>
@endsection
@section('content')
    <!--  -->

    <!-- Ticket Widget -->

    <!--  -->
    <div class="row">
        <div class="col-md-12">
            <div>
                <div class="box box-warning same-height-widget">
                    <div class="box-header with-border">

                    </div>
                    <div class="box-body" style="max-height: auto; overflow-y: scroll;">

                        <div class="media-body">
                            <h4 class="media-heading">{{$Cmsnews->name }}</h4>
                            <p class="text-right"></p>
                            <p>
                                <img src="{{ Storage::disk('local')->url("CMS/images/$Cmsnews->image") }}"
                                     style="width:300px;height:170px;margin-right:15px;">
                                {!!$Cmsnews->summary!!}.</p>
                            <ul class="list-inline list-unstyled">
                                <li><span><i class="glyphicon glyphicon-calendar"></i> {{$Cmsnews->created_at}} </span>
                                </li>
                                <li>|</li>
                                {{--<span><i class="glyphicon glyphicon-comment"></i> 2 comments</span>--}}
                                <li>| <b>Please Rate This Article</b></li>
                                <li>
                                    {{--{{$Cmsnews->cmsRankings}}--}}
                                    @if (!empty($Cmsnews->cmsRankings->first()->rating_1))
                                        <a href="{{ '/rate/1/' . $Cmsnews->id }}" id="rate_cms"
                                           class="btn btn-default  btn-xs"><i class=""></i><span
                                                    class="glyphicon glyphicon-star"></span> </a>
                                    @else
                                        <a href="{{ '/rate/1/' . $Cmsnews->id }}" id="rate_cms"
                                           class="btn btn-default  btn-xs"><i class=""></i><span
                                                    class="glyphicon glyphicon-star-empty"></span> </a>
                                    @endif

                                    @if (!empty($Cmsnews->cmsRankings->first()->rating_2))
                                        <a href="{{ '/rate/2/' . $Cmsnews->id }}" id="rate_cms"
                                           class="btn btn-default  btn-xs"><i class=""></i><span
                                                    class="glyphicon glyphicon-star"></span> </a>
                                    @else
                                        <a href="{{ '/rate/2/' . $Cmsnews->id }}" id="rate_cms"
                                           class="btn btn-default  btn-xs"><i class=""></i><span
                                                    class="glyphicon glyphicon-star-empty"></span> </a>
                                    @endif

                                    @if (!empty($Cmsnews->cmsRankings->first()->rating_3))
                                        <a href="{{ '/rate/3/' . $Cmsnews->id }}" id="rate_cms"
                                           class="btn btn-default  btn-xs"><i class=""></i><span
                                                    class="glyphicon glyphicon-star"></span> </a>
                                    @else
                                        <a href="{{ '/rate/3/' . $Cmsnews->id }}" id="rate_cms"
                                           class="btn btn-default  btn-xs"><i class=""></i><span
                                                    class="glyphicon glyphicon-star-empty"></span> </a>
                                    @endif

                                    @if (!empty($Cmsnews->cmsRankings->first()->rating_4))
                                        <a href="{{ '/rate/4/' . $Cmsnews->id }}" id="rate_cms"
                                           class="btn btn-default  btn-xs"><i class=""></i><span
                                                    class="glyphicon glyphicon-star"></span> </a>
                                    @else
                                        <a href="{{ '/rate/4/' . $Cmsnews->id }}" id="rate_cms"
                                           class="btn btn-default  btn-xs"><i class=""></i><span
                                                    class="glyphicon glyphicon-star-empty"></span> </a>
                                    @endif

                                    @if (!empty($Cmsnews->cmsRankings->first()->rating_5))
                                        <a href="{{ '/rate/5/' . $Cmsnews->id }}" id="rate_cms"
                                           class="btn btn-default  btn-xs"><i class=""></i><span
                                                    class="glyphicon glyphicon-star"></span> </a>
                                    @else
                                        <a href="{{ '/rate/5/' . $Cmsnews->id }}" id="rate_cms"
                                           class="btn btn-default  btn-xs"><i class=""></i><span
                                                    class="glyphicon glyphicon-star-empty"></span> </a>
                                    @endif

                                </li>

                                <li>|</li>

                            </ul>
                        </div>
                    </div>
                    <div class="box-footer clearfix">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Ticket Widget -->
@endsection


@section('page_script')



@endsection