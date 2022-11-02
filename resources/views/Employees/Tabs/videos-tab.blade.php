
<div class="row">
    <div class="col-md-6 col-md-offset-0">
        <div class="box box-primary">
            <div class="box-header with-border">
                <i class="fa fa-barcode pull-right"></i>
                <h3 class="box-title"> General Videos </h3>
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
                        @if (count($general) > 0)
                            <ul class="products-list product-list-in-box">
                                @foreach ($general as $key => $video)
                                    <tr id="categories-list">

                                        <td>
                                            <video  height="60" width="150" controls>
                                                <source src="{{URL::asset("storage/public/videos/$video->path")}}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </td>
                                        <td style="text-align: center;">{{ (!empty( $video->name)) ?  $video->name : ''}} </td>
                                        <td style="text-align: center;">{{ (!empty( $video->description)) ?  $video->description : ''}} </td>

                                    </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    <!-- /.box-body -->
                    <div class="box-footer">

                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="col-md-6 col-md-offset-0">
        <div class="box box-primary">
            <div class="box-header with-border">
                <i class="fa fa-barcode pull-right"></i>
                <h3 class="box-title"> Specific Videos </h3>
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
                        @if (count($specific) > 0)
                            <ul class="products-list product-list-in-box">
                                @foreach ($specific as $key => $video)
                                    <tr id="categories-list">
                                        <td>
                                            <video  height="60" width="150" controls>
                                                <source src="{{URL::asset("storage/public/videos/$video->path")}}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </td>
                                        <td style="text-align: center;">{{ (!empty( $video->name)) ?  $video->name : ''}} </td>
                                        <td style="text-align: center;">{{ (!empty( $video->description)) ?  $video->description : ''}} </td>

                                    </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    <!-- /.box-body -->
                    <div class="box-footer">

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


