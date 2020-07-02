<div class="row">
    <div class="col-md-12">

        <!-- CONTACTS (CLIENTS) LIST -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Clients Search Result</h3>
            </div>
            <!-- /.box-header -->
            <form method="POST" action="/contacts/search/print" target="_blank">
                {{ csrf_field() }}
                <input type="hidden" name="person_name" value="{{ $personName }}">
                <input type="hidden" name="id_number" value="{{ $personIDNum }}">
                <input type="hidden" name="passport_number" value="{{ $personPassportNum }}">
                <input type="hidden" name="company_id" value="{{ $personCompanyID }}">
                <input type="hidden" name="company_name" value="{{ $personCompanyName }}">
                <input type="hidden" name="res_province_id" value="{{ $provinceID }}">
                <input type="hidden" name="res_province_name" value="{{ $provinceName }}">
                <div class="box-body">
                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                        <strong class="lead">Search Parameters</strong><br>
                        <strong>Client Name:</strong> <em>{{ empty($personName) ? '[all]' : $personName }}</em> &nbsp; &nbsp;
                        | &nbsp; &nbsp; <strong>ID Number:</strong> <em>{{ empty($personIDNum) ? '[all]' : $personIDNum }}</em> &nbsp; &nbsp;
                        | &nbsp; &nbsp; <strong>Passport Number:</strong> <em>{{ empty($personPassportNum) ? '[all]' : $personPassportNum }}</em> &nbsp; &nbsp;
                        | &nbsp; &nbsp; <strong>Province:</strong> <em>{{ empty($provinceName) ? '[all]' : $provinceName }}</em> &nbsp; &nbsp;
                        | &nbsp; &nbsp; <strong>Company:</strong> <em>
                            @if(empty($personCompanyName))
                                [all]
                            @else
                                {{ $personCompanyName . ' ' }}
                                <a href="{{ "/contacts/company/$personCompanyID/view" }}" class="btn btn-xs btn-link no-print"><i class="fa fa-eye"></i> View Company</a>
                            @endif
                        </em> &nbsp; &nbsp;
                    </p>
                    @if(!(count($persons) > 0))
                        <div class="callout callout-danger">
                            <h4><i class="fa fa-database"></i> No Records found</h4>

                            <p>No client matching your search criteria in the database. Please make sure there are clients registered in the system and refine your search parameters.</p>
                        </div>
                    @endif

                <!-- Include the contacts result list blade -->
                    @include('contacts.partials.contacts_result_list')

                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <button type="button" id="back_to_contact_search" class="btn btn-default no-print"><i class="fa fa-arrow-left"></i> Back To Search</button>
                    <button type="submit" class="btn btn-primary pull-right no-print"><i class="fa fa-print"></i> Print Result</button>
                </div>
                <!-- /.box-footer -->
            </form>
        </div>
        <!-- /.box -->
    </div>
</div>