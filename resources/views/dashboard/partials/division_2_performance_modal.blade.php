<div id="sub-division-performance-modal-2" class="modal modal-default fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="sud-division-modal-title-2">Performance</h4>
            </div>
            <div class="modal-body no-padding">
                <div class="box box-default">
                    <div class="box-body">
                        <div class="row">
                            <!-- Chart col -->
                            <div class="col-md-8">
                                <p class="text-center">
                                    <strong id="sub-division-chart-title-2">Performance For {{ date('Y') }}</strong>
                                </p>

                                <div class="chart">
                                    <!-- Sales Chart Canvas-->
                                    <canvas id="subDivisionsPerformanceChart2" style="height: 220px;"></canvas>
                                </div>
                                <!-- /.chart-responsive -->
                            </div>
                            <!-- Ranking col -->
                            <div class="col-md-4">
                                <p class="text-center">
                                    <strong>Ranking</strong>
                                </p>
                                <div class="no-padding" style="max-height: 220px; overflow-y: scroll;">
                                    <ul class="nav nav-pills nav-stacked" id="sub-div-ranking-list-2">
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- /.row -->
                    </div>
                    <div class="overlay" id="lo-sub-division-performance-modal-2">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Back</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>