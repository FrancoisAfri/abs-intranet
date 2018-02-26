@extends('layouts.main_layout')
@section('page_dependencies')
<!-- bootstrap file input -->
<link href="/bower_components/bootstrap_fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/iCheck/square/blue.css">
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
                    <h3 class="box-title">{{$Cmsnews->name }}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body" style="max-height: auto; overflow-y: scroll;">

                    <div class="col-md-12">
                        <div class="box box-muted same-height-widget">
                            <div class="box-header with-border">
                                <i class="fa fa-comments-o"></i>
                                <h3 class="box-title"> Campony Ceo Message</h3>
                            </div>

                            <div class="container">
                                <!--  <div class="page-header">
                                     <h1 id="timeline">Timeline</h1>
                                 </div> -->
                                <ul class="timeline">

                                    <li>
                                        <div class="timeline-badge"><i class="glyphicon glyphicon-check"></i></div>
                                        <div class="timeline-panel" style="max-height: 300px; overflow-y: scroll;">
                                            {{--<div class="no-padding" style="max-height: 220px; overflow-y: scroll;">--}}
                                            <div class="timeline-heading">
                                                <h4 class="timeline-title">
                                                    <N>{{$Cmsnews->name}}</N>
                                                </h4>
                                                <p>
                                                    <small class="text-muted"><i
                                                                class="glyphicon glyphicon-time"></i> {{date(' d M Y', $Cmsnews->date) }}
                                                    </small>
                                                </p>
                                            </div>
                                            <div class="timeline-body">
                                                <p>{!!$Cmsnews->summary!!}.</p>
                                                <div>
                                                    <div class="pull-right">
                                                        {{--<span class="label label-info">{{$surname . ' ' . $names}}</span>--}}
                                                        <span class="label label-primary">story</span>
                                                        {{--<span class="label label-success">blog</span>--}}
                                                        {{--<span class="label label-info">personal</span>--}}
                                                        {{--<span class="label label-warning">Warning</span>--}}
                                                        {{--<span class="label label-danger">Danger</span>--}}
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>

                                        </div>
                                    </li>

                                </ul>
                            </div>
                        </div>
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