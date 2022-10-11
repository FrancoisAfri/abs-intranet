
    <div class="row">
        <div class="col-md-6 col-md-offset-0">
            <div class="box box-default">
                <div class="box-header with-border">
                    <i class="fa fa-barcode pull-right"></i>
                    <h3 class="box-title"> My Tasks </h3>
                </div>
                <div class="box-body">
                    <div style="overflow-X:auto;">
                        <table id=" " class="display table table-bordered table-hover">
                            <thead>
                            <tr>
                                Description
                                <th style="width: 10px; text-align: center;">#</th>
                                <th style="text-align: center;">Task Description</th>
                                <th style="text-align: center;">Task Duration</th>
                                <th style="width: 5px; text-align: center;">Due Date</th>
                                <th style="width: 5px; text-align: center;">Client Name</th>
                                <th style="width: 5px; text-align: center;">Document </th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($checkTasks) > 0)
                                <ul class="products-list product-list-in-box">
                                    @foreach ($checkTasks as $key => $Tasks)
                                        <tr id="categories-list">
                                            <td></td>
                                            <td style="text-align: center;">{{ (!empty( $Tasks->description)) ?  $Tasks->description : ''}} </td>
                                            <td style="text-align: center;">{{ (!empty( $Tasks->description)) ?  $Tasks->description : ''}} </td>
                                            <td style="text-align: center;">{{ (!empty( $Tasks->due_date)) ?  $Tasks->due_date : ''}} </td>
                                            <td style="text-align: center;">{{ (!empty( $Tasks->size)) ?  $Tasks->size : ''}} </td>
                                           <td></td>
                                        </tr>
                                @endforeach
                            @endif
                            </tbody>
                            <tfoot>
                            <tr>
                                <th style="width: 10px; text-align: center;"></th>
                                <th>Name</th>
                                <th>Description</th>
                                <th style="width: 5px; text-align: center;"></th>
                                <th style="width: 5px; text-align: center;"></th>
                                {{--                                <th style="width: 5px; text-align: center;"></th>--}}
                            </tr>
                            </tfoot>
                        </table>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="button" id="cat_module" class="btn btn-default pull-right" data-toggle="modal" data-target="#add-component-modal">Add component </button>
                            <button type="button" class="btn btn-default pull-left" id="back_button"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

            {{--  ASsign Check      --}}

        <div class="col-md-6 col-md-offset-0">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <i class="fa fa-barcode pull-right"></i>
                    <h3 class="box-title"> My Checks </h3>
                </div>
                <div class="box-body">
                    <div style="overflow-X:auto;">
                        <table id=" " class="display table table-bordered table-hover">
                            <thead>
                            <tr>
                                Description
                                <th style="width: 10px; text-align: center;">#</th>
                                <th style="text-align: center;">Task Description</th>
                                <th style="text-align: center;">Task Duration</th>
                                <th style="width: 5px; text-align: center;">Due Date</th>
                                <th style="width: 5px; text-align: center;">Client Name</th>
                                <th style="width: 5px; text-align: center;">Document </th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($checkTasks) > 0)
                                <ul class="products-list product-list-in-box">
                                    @foreach ($checkTasks as $key => $Tasks)
                                        <tr id="categories-list">
                                            <td></td>
                                            <td style="text-align: center;">{{ (!empty( $Tasks->description)) ?  $Tasks->description : ''}} </td>
                                            <td style="text-align: center;">{{ (!empty( $Tasks->description)) ?  $Tasks->description : ''}} </td>
                                            <td style="text-align: center;">{{ (!empty( $Tasks->due_date)) ?  $Tasks->due_date : ''}} </td>
                                            <td style="text-align: center;">{{ (!empty( $Tasks->size)) ?  $Tasks->size : ''}} </td>
                                            <td></td>
                                        </tr>
                                @endforeach
                            @endif
                            </tbody>
                            <tfoot>
                            <tr>
                                <th style="width: 10px; text-align: center;"></th>
                                <th>Name</th>
                                <th>Description</th>
                                <th style="width: 5px; text-align: center;"></th>
                                <th style="width: 5px; text-align: center;"></th>
                                {{--                                <th style="width: 5px; text-align: center;"></th>--}}
                            </tr>
                            </tfoot>
                        </table>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="button" id="cat_module" class="btn btn-default pull-right" data-toggle="modal" data-target="#add-component-modal">Add component </button>
                            <button type="button" class="btn btn-default pull-left" id="back_button"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


