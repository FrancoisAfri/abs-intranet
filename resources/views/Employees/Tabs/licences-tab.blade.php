<div class="row">
    <div class="col-md-12 col-md-offset-0">
        <div class="box box-primary">
            <div class="box-header with-border">
                <i class="fa fa-barcode pull-right"></i>
                <h3 class="box-title"> License </h3>
            </div>
            <div class="box-body">
                <div style="overflow-X:auto;">
                    <table id=" " class="display table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th style="width: 10px; text-align: center;"></th>
                            <th>Employee Number</th>
                            <th>Employee Name</th>
                            <th>Licence Name</th>

                        </tr>
                        </thead>
                        <tbody>
                        @if (!empty($license_allocation))
                            <ul class="products-list product-list-in-box">
                                @foreach ($license_allocation as $key => $license)
                                    <tr id="categories-list">
                                        <td></td>
                                        <td>{{ (!empty($license->Hrpersons->employee_number)) ? $license->Hrpersons->employee_number : ' ' }}</td>
                                        <td>{{ (!empty($license->Hrpersons->first_name . ' ' . $license->Hrpersons->surname )) ? $license->Hrpersons->first_name . ' ' . $license->Hrpersons->surname   : ' ' }}</td>
                                        <td>{{ (!empty($license->Licenses->name)) ? $license->Licenses->name : ' ' }}</td>

                                    </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        {{--						<button type="button" class="btn btn-default pull-left" id="back_button">Back</button>--}}
                        <button type="button" id="cat_module" class="btn btn-warning pull-right" data-toggle="modal"
                                data-target="#add-document-modal">Add Document
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>



