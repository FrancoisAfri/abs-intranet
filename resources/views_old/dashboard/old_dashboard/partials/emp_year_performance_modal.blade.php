<div id="emp-year-performance-modal" class="modal modal-default fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="emp-year-modal-title">Appraisal</h4>
            </div>
            <div class="modal-body no-padding">
                <div class="box box-default">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-8">
                                <p class="text-center">
                                    <strong id="emp-year-chart-title">Employee Performance For {{ date('Y') }}</strong>
                                </p>

                                <div class="chart">
                                    <!-- Sales Chart Canvas-->
                                    <canvas id="empMonthlyPerformanceModalChart" style="height: 220px;"></canvas>
                                </div>
                                <!-- /.chart-responsive -->
                            </div>
                            <!-- Appraised months list col -->
                            <div class="col-md-4">
                                <p class="text-center">
                                    <strong>Appraised Months List</strong>
                                </p>
                                <div class="no-padding" style="max-height: 220px; overflow-y: scroll;">
                                    <ul class="nav nav-pills nav-stacked" id="emp-appraised-month-modal-list"></ul>
                                </div>
                            </div>
                        </div>
                        <!-- /.row -->
                    </div>
                    <div class="overlay" id="lo-emp-year-performance-modal">
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