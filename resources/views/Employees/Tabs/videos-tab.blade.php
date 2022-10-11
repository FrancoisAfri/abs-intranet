
<div class="row">
    <div class="col-md-12 col-md-offset-0">
        <div class="box box-primary">
            <div class="box-header with-border">
                <i class="fa fa-barcode pull-right"></i>
                <h3 class="box-title"> My Videos </h3>
            </div>
            <div class="box-body">
                <div style="overflow-X:auto;">
                    <table id=" " class="display table table-bordered table-hover">
                        <thead>
                        <tr>
                            Description
                            <th style="width: 10px; text-align: center;">#</th>
                            <th style="text-align: center;"> Name</th>
                            <th style="text-align: center;">Details</th>

                        </tr>
                        </thead>
                        <tbody>
                        @if (count($videos) > 0)
                            <ul class="products-list product-list-in-box">
                                @foreach ($videos as $key => $video)
                                    <tr id="categories-list">
                                        <td></td>
                                        <td style="text-align: center;">{{ (!empty( $video->name)) ?  $video->name : ''}} </td>
                                        <td style="text-align: center;">{{ (!empty( $video->description)) ?  $video->description : ''}} </td>

                                    </tr>
                            @endforeach
                        @endif
                        </tbody>
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


