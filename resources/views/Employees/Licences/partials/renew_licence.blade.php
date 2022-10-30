<div id="add-licence-renewal-modal" class="modal modal-default fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="add-licence_renewal-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Renew licence for  {{ $LicenceDetails->name }}</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <div class="box-body" id="view_users">
                        <input type="hidden" name="license_id" id="license_id" value="{{ $LicenceDetails->id }}">
                        <div class="form-group new-field {{ $errors->has('transfer_date') ? ' has-error' : '' }}">
                            <label for="expiration_date" class="col-sm-2 control-label"> Renewal Date </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control datepicker" name="renewal_date" id="renewal_date"
                                       placeholder="  dd/mm/yyyy" value="{{ old('renewal_date') }}">
                            </div>
                        </div>
                        <div class="form-group new-field {{ $errors->has('transfer_date') ? ' has-error' : '' }}">
                            <label for="expiration_date" class="col-sm-2 control-label"> Expiry Date </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control datepicker" name="expiration_date" id="expiration_date"
                                       placeholder="  dd/mm/yyyy" value="{{ old('expiration_date') }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" id="add-licence-renewal" class="btn btn-warning"><i
                                    class="fa fa-cloud-upload"></i> Save
                        </button>
                    </div>
            </form>
        </div>
    </div>
</div>




