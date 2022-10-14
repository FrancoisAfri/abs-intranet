
<div class="row">
	<div class="col-md-12 col-md-offset-0">
		<div class="box box-primary">
			<div class="box-header with-border">
				<i class="fa fa-barcode pull-right"></i>
				<h3 class="box-title"> My Documents Videos </h3>
			</div>
			<div class="box-body">
				<div style="overflow-X:auto;">
					<table id=" " class="display table table-bordered table-hover">
						<thead>
						<tr>
							<th style="width: 10px; text-align: center;"></th>
							<th style="width: 5px; text-align: center;"></th>
							<th>Document Type</th>
							<th>Description</th>
							<th>Date From</th>
							<th>Expiry Date</th>
							<th style="width: 5px; text-align: center;"></th>
						</tr>
						</thead>
						<tbody>
						@if (count($documents) > 0)
							<ul class="products-list product-list-in-box">
								@foreach ($documents as $key => $video)
									<tr id="categories-list">

										<td nowrap>
											<button document="button" id="edit_compan" class="btn btn-warning  btn-xs"
													data-toggle="modal" data-target="#edit-newdoc-modal"
													data-id="{{ $doc->id }}" data-doc_description="{{ $doc->doc_description }}"
													data-doc_type_id="{{ $doc->doc_type_id }}"
													data-date_from="{{  date(' d M Y', $doc->date_from) }}"
													data-expirydate="{{ date(' d M Y', $doc->expirydate) }}"
											><i class="fa fa-pencil-square-o"></i> Edit
											</button>
										</td>
										<td nowrap>
											<div class="form-group{{ $errors->has('supporting_docs') ? ' has-error' : '' }}">
												<label for="document" class="control-label"></label>
												@if(!empty($doc->supporting_docs))
													<a class="btn btn-default btn-flat btn-block pull-right btn-xs"
													   href="{{ Storage::disk('local')->url("Employee/documents/$doc->supporting_docs") }}"
													   target="_blank"><i class="fa fa-file-pdf-o"></i> View Document</a>
												@else
													<a class="btn btn-default pull-centre btn-xs"><i
																class="fa fa-exclamation-triangle"></i> Nothing Uploaded</a>
												@endif
											</div>
										</td>
										<td>{{ !empty($doc->documentType->name) ? $doc->documentType->name : ''}}</td>
										<td>{{ !empty($doc->doc_description) ? $doc->doc_description : ''}}</td>
										<td>{{ !empty($doc->date_from) ? date(' d M Y', $doc->date_from) : '' }}</td>
										<td>{{ !empty($doc->expirydate) ? date(' d M Y', $doc->expirydate) : '' }}</td>
										<td>

										</td>

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
			@include('Employees.partials.add_document_modal')
			@include('Employees.partials.edit_document_modal')
		</div>
	</div>

</div>


