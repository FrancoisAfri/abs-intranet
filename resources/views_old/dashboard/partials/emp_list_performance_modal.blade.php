<div id="emp-list-performance-modal" class="modal modal-default fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="emp-list-modal-title">Performance</h4>
            </div>
            <div class="modal-body no-padding">
                <div class="box box-default">
                    <div class="box-body">
                        <div class="row">
                            <!-- Ranking col -->
                            <div class="col-md-12">
                                <p class="text-center">
                                    <strong>Employees Performance Ranking ({{ date('Y') }})</strong>
                                </p>
                                <div class="no-padding" style="max-height: 420px; overflow-y: scroll;">
                                    <ul class="nav nav-pills nav-stacked products-list product-list-in-box" id="emp-ranking-list">
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- /.row -->
                    </div>
                    <div class="overlay" id="lo-emp-list-performance-modal">
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