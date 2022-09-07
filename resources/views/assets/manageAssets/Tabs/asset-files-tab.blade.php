<div class="row">
    <div class="col-md-12 col-md-offset-0">
        <div class="box box-primary">
            <div class="box-header with-border">
                <i class="fa fa-barcode pull-right"></i>
                <h3 class="box-title"> Files</h3>
            </div>
            <div class="box-body">
                <div style="overflow-X:auto;">
                    <table id="example2"
                           class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th style="width: 10px; text-align: center;">#
                            </th>
                            <th>Name</th>
                            <th>Description</th>
                            <th style="width: 5px; text-align: center;">.
                            </th>
                            <th style="width: 5px; text-align: center;">.
                            </th>
                            {{--                                <th style="width: 5px; text-align: center;">.</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($licenceType) > 0)
                            <ul class="products-list product-list-in-box">
                                @foreach ($licenceType as $key => $licenceTypes)
                                    <tr id="categories-list">
                                        <td nowrap>
                                            <button vehice="button"
                                                    id="edit_licence"
                                                    class="btn btn-warning  btn-xs"
                                                    data-toggle="modal"
                                                    data-target="#edit-licence-modal"
                                                    data-id="{{ $licenceTypes->id }}"
                                                    data-name="{{ $licenceTypes->name }}"
                                                    data-description="{{$licenceTypes->description}}">
                                                <i
                                                        class="fa fa-pencil-square-o"></i>
                                                Edit
                                            </button>
                                        </td>

                                        <td>{{ (!empty( $licenceTypes->name)) ?  $licenceTypes->name : ''}} </td>
                                        <td>{{ (!empty( $licenceTypes->description)) ?  $licenceTypes->description : ''}} </td>
                                        <td>
                                            <!--   leave here  -->
                                            <button vehice="button"
                                                    id="view_ribbons" class="btn {{ (!empty($licenceTypes->status) && $licenceTypes->status == 1) ? " btn-danger " : "btn-success " }}
                                      btn-xs" onclick="postData({{$licenceTypes->id}}, 'actdeac');"><i class="fa {{ (!empty($licenceTypes->status) && $licenceTypes->status == 1) ?
                                      " fa-times " : "fa-check " }}"></i> {{(!empty($licenceTypes->status) && $licenceTypes->status == 1) ? "De-Activate" : "Activate"}}
                                            </button>
                                        </td>
                                        <td>
                                            <form action="{{ route('licence.destroy', $licenceTypes->id ) }}"
                                                  method="POST"
                                                  style="display: inline-block;">
                                                <input type="hidden"
                                                       name="_method"
                                                       value="DELETE">
                                                <input type="hidden"
                                                       name="_token"
                                                       value="{{ csrf_token() }}">

                                                <button type="submit"
                                                        class="btn btn-xs btn-danger btn-flat delete_confirm"
                                                        data-toggle="tooltip"
                                                        title='Delete'>
                                                    <i class="fa fa-trash">
                                                        Delete </i>
                                                </button>
                                            </form>
                                        </td>
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
                        <button type="button" id="cat_module"
                                class="btn btn-default pull-right"
                                data-toggle="modal"
                                data-target="#add-licence-modal">Add Licence
                            Type
                        </button>
                        <button type="button"
                                class="btn btn-default pull-left"
                                id="back_button"><i
                                    class="fa fa-arrow-left"></i> Back
                        </button>
                    </div>
                </div>
            </div>
            @include('assets.licenseType.partials.create')
            @include('assets.licenseType.partials.edit')
        </div>
    </div>
</div>
