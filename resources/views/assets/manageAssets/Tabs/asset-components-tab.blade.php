<div class="row">
    <div class="col-md-12 col-md-offset-0">
        <div class="box box-primary">
            <div class="box-header with-border">
                <i class="fa fa-barcode pull-right"></i>
                <h3 class="box-title"> Components</h3>
            </div>
            <div class="box-body">
                <div style="overflow-X:auto;">
                    <table id=" " class="display table table-bordered data-table my-2">
                        <thead>
                        <tr>
                            <th style="width: 10px; text-align: center;"></th>
                            <th>Name</th>
                            <th>Description</th>
                            <th style="width: 5px; text-align: center;">Size</th>
                            <th style="width: 5px; text-align: center;">.</th>
                            {{--                                <th style="width: 5px; text-align: center;">.</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @if (count($assetComponents) > 0)
                            <ul class="products-list product-list-in-box">
                                @foreach ($assetComponents as $key => $component)
                                    <tr id="categories-list">
{{--                                        <td nowrap>--}}
{{--                                            <button vehice="button" id="edit_licence" class="btn btn-warning  btn-xs"--}}
{{--                                                    data-toggle="modal" data-target="#edit-licence-modal"--}}
{{--                                                    data-id="{{ $component->id }}"--}}
{{--                                                    data-name="{{ $component->name }}"--}}
{{--                                                    data-description="{{$component->description}}"><i--}}
{{--                                                        class="fa fa-pencil-square-o"></i> Edit--}}
{{--                                            </button>--}}
{{--                                        </td>--}}

                                        <td></td>
                                        <td>{{ $component->name ??   ''}} </td>
                                        <td>{{ $component->description ??   ''}} </td>
                                        <td>{{ $component->size ??   ''}} </td>

                                        <td>
                                            <form action="{{ route('component.destroy', $component->id ) }}" method="POST"
                                                  style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                                <button type="submit"
                                                        class="btn btn-xs btn-danger btn-flat delete_confirm"
                                                        data-toggle="tooltip" title='Delete'>
                                                    <i class="fa fa-trash"> Delete </i>
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
                            <th style="width: 5px; text-align: center;"><strong>size</strong></th>
                            <th style="width: 5px; text-align: center;"></th>
                            {{--                                <th style="width: 5px; text-align: center;"></th>--}}
                        </tr>
                        </tfoot>
                    </table>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" id="cat_module" class="btn btn-default pull-right" data-toggle="modal" data-target="#add-component-modal">Add Component</button>
                        <button type="button" class="btn btn-default pull-left" id="back_button"><i class="fa fa-arrow-left"></i> Back</button>
                    </div>
                </div>
            </div>
            @include('assets.manageAssets.partials.create_component')
        </div>
    </div>
</div>
