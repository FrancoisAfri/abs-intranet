<div class="row">
	<div class="col-md-12 col-md-offset-0">
		<div class="box box-default">
			<div class="box-header with-border">
				<i class="fa fa-barcode pull-right"></i>
				<h3 class="box-title"> My Tasks </h3>
			</div>
			<div class="box-body">
				<div style="overflow-X:auto;">
					@include('Employees.partials.widgets.tasks_widget')
					@if(Session('error_starting'))
						@include('tasks.partials.error_tasks', ['modal_title' => "Task Error!", 'modal_content' => session('error_starting')])
					@endif
					@include('tasks.partials.end_task')
				</div>
			</div>
		</div>
	</div>
</div>