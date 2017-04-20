<div id="emp-list-performance-modal" class="modal modal-default fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="emp-list-modal-title">Performance</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Ranking col -->
                    <div class="col-md-12">
                        <p class="text-center">
                            <strong>Employees Performance Ranking ({{ date('Y') }})</strong>
                        </p>
                        <div class="no-padding" style="max-height: 220px; overflow-y: scroll;">
                            <ul class="nav nav-pills nav-stacked products-list product-list-in-box" id="emp-ranking-list">
                                <!--<li class="item">
                                    <a href="#">
                                        <div class="product-img">
                                            <img src="http://placehold.it/150x150" alt="Profile Picture">
                                        </div>
                                        <div class="product-info">
                                            <span class="product-title text-blue">Smalto Tsham</span>
                                            <!--<span class="label label-success pull-right">active</span>--
                                            <span class="product-description">
                                                <i class="fa fa-envelope-o"></i> {{ 'smalto@afrixcel.co.za' }}
                                                &nbsp; {{ ' | ' }} &nbsp; <i class="fa fa-user-circle"></i> SD Dir
                                            </span>
                                        </div>

                                        <div class="progress-group">
                                            <span class="progress-text">&nbsp;</span>
                                            <span class="progress-number text-red">49%</span><!-- <i class="fa fa-angle-down"></i> --

                                            <div class="progress xs" style="margin-bottom: 5px;">
                                                <div class="progress-bar progress-bar-red" style="width: 49%"></div>
                                            </div>
                                        </div>
                                    </a>
                                </li>-->
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Back</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>