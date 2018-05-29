<div id="add-safe-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="edit-module-form">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"> Print </h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>

                    <a href="{{ '/jobcards/addparts/' . $card->id }}" id="edit_compan"
                       class="btn btn-warning  btn-xs"><i class="fa fa-steam"></i> Parts</a></td>
                    <a href="{{ '/jobcards/addparts/' . $card->id }}" id="edit_compan"
                       class="btn btn-warning  btn-xs"><i class="fa fa-steam"></i> Parts</a></td>

                    <table id="emp-list-table" class="table table-bordered table-striped table-hover">
                        <thead>
                        <tr>

                            <th> Employee Name</th>

                        </tr>
                        </thead>
                        <tbody>

                        <tr>


                        </tr>

                        </tbody>
                        <tfoot>
                        <tr>
                            <th> Employee Name</th>

                        </tr>
                        </tfoot>
                    </table>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="add-safe" class="btn btn-warning"><i class="fa fa-cloud-upload"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
           