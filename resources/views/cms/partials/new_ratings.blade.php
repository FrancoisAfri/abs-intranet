@extends('layouts.main_layout')
@section('page_dependencies')
<!-- bootstrap file input -->
<link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
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
                            {!!$Cmsnews->summary!!}.</p>
                        <ul class="list-inline list-unstyled">
                            <li><span><i class="glyphicon glyphicon-calendar"></i>  {{$Cmsnews->created_at}} </span></li>
                            <li>|</li>
                            {{--<span><i class="glyphicon glyphicon-comment"></i> 2 comments</span>--}}
                            {{--<li>|</li>--}}
                           <!--  <li>
                                <span class="glyphicon glyphicon-star"></span>
                                <span class="glyphicon glyphicon-star"></span>
                                <span class="glyphicon glyphicon-star"></span>
                                <span class="glyphicon glyphicon-star"></span>
                                <span class="glyphicon glyphicon-star-empty"></span>
                            </li> -->
                            <li>|</li>
                            {{--<li>--}}
                                {{--<!-- Use Font Awesome http://fortawesome.github.io/Font-Awesome/ -->--}}
                                {{--<span><i class="fa fa-facebook-square"></i></span>--}}
                                {{--<span><i class="fa fa-twitter-square"></i></span>--}}
                                {{--<span><i class="fa fa-google-plus-square"></i></span>--}}
                            {{--</li>--}}
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
    <!-- Select2 -->
    <script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <!-- ChartJS 1.0.1 -->
    <script src="/bower_components/AdminLTE/plugins/chartjs/Chart.min.js"></script>
    <!-- Admin dashboard charts ChartsJS -->
    <script src="/custom_components/js/admindbcharts.js"></script>
    <!-- matchHeight.js
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.0/jquery.matchHeight-min.js"></script>-->
    <!-- the main fileinput plugin file -->
    <script src="/bower_components/bootstrap_fileinput/js/fileinput.min.js"></script>
    <!-- Ajax form submit -->
    <script src="/custom_components/js/modal_ajax_submit.js"></script>
    <!-- Ajax dropdown options load -->
    <script src="/custom_components/js/load_dropdown_options.js"></script>
    <!-- Task timer -->
    <script src="/custom_components/js/tasktimer.js"></script>
    <script src="/bower_components/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <script>
        function postData(id, data)
        {
            if (data == 'start')
                location.href = "/task/start/" + id;
            else if (data == 'pause')
                location.href = "/task/pause/" + id;
            else if (data == 'end')
                location.href = "/task/end/" + id;
        }
       $(function () {
            // hide end button when page load
            //$("#end-button").show();
            //Initialize Select2 Elements
            $(".select2").select2();

             $('#ticket').click(function () {
                location.href = '/helpdesk/ticket';
            });

            //Initialize iCheck/iRadio Elements
            $('.rdo-iCheck').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });

            //initialise matchHeight on widgets
            //$('.same-height-widget').matchHeight();

            //Vertically center modals on page
            function reposition() {
                var modal = $(this),
                        dialog = modal.find('.modal-dialog');
                modal.css('display', 'block');

                // Dividing by two centers the modal exactly, but dividing by three
                // or four works better for larger screens.
                dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 2));
            }
            // Reposition when a modal is shown
            $('.modal').on('show.bs.modal', reposition);
            // Reposition when the window is resized
            $(window).on('resize', function () {
                $('.modal:visible').each(reposition);
            });


            // 

        });
    </script>
@endsection