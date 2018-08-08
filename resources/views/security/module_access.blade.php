@extends('layouts.main_layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <form class="form-horizontal" method="POST" action="/users/access_save/{{$user->id}}">
                    {{ csrf_field() }}
                    <div class="box-header with-border"><h3 class="box-title">Modules Access</h3></div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Name</th>
                                <th style="width: 400px">Access Level</th>
                            </tr>
                            @foreach($modules as $module)
                                <tr id="modules-list">
                                    <td style="vertical-align: middle;">{{ $module->mod_name }}</td>
                                    <td>
                                        <select class="form-control" name="module_access[{{$module->mod_id}}]"
                                                id="access_level">
                                            <option value="0" {{ (empty($module->access_level)) ? ' selected' : '' }}>
                                                None
                                            </option>
                                            <option value="1" {{ (!empty($module->access_level)) && $module->access_level == 1 ? ' selected' : '' }} >
                                                Read
                                            </option>
                                            <option value="2" {{ (!empty($module->access_level)) && $module->access_level == 2 ? ' selected' : '' }} >
                                                Write
                                            </option>
                                            <option value="3" {{ (!empty($module->access_level)) && $module->access_level == 3 ? ' selected' : '' }} >
                                                Modify
                                            </option>
                                            <option value="4" {{ (!empty($module->access_level)) && $module->access_level == 4 ? ' selected' : '' }} >
                                                Admin
                                            </option>
                                            <option value="5" {{ (!empty($module->access_level)) && $module->access_level == 5 ? ' selected' : '' }} >
                                                Super User
                                            </option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" class="btn btn-default pull-left" id="back_button"><i class="fa fa-arrow-left"></i> Back</button>
                        <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-floppy-o"></i> Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('page_script')
    <script>
        function postData(id, data) {
            if (data == 'ribbons')
                location.href = "/users/ribbons/" + id;
            else if (data == 'edit')
                location.href = "/users/module_edit/" + id;
            else if (data == 'actdeac')
                location.href = "/users/module_active/" + id;
            else if (data == 'access')
                location.href = "/users/module_access/" + id;
        }
        $(function () {
            document.getElementById("back_button").onclick = function () {
                if ({{$user->type}} == 2)	location.href = "/contacts/{{$user->person->id}}/edit";
				else location.href = "/users/{{$user->id}}/edit";;
            };
        });
    </script>
@endsection