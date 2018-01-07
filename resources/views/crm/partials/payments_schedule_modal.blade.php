<div id="payments-schedule-modal" class="modal modal-default fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal" method="POST" name="payments-schedule-form">
                {{ csrf_field() }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Invoices/Payments</h4>
                </div>
                <div class="modal-body">
                    <div id="invalid-input-alert"></div>
                    <div id="success-alert"></div>
                    <table class="table no-margin">
                        <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Payment Due</th>
                            <th>Status</th>
                            <th class="text-right">Amount</th>
                            <th class="text-right">Balance</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody id="invoices">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Close</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
