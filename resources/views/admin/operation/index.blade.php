@extends('admin/layouts.master')

@section('content_header')
	<div class="row mb-2">
		<div class="col-sm-6">
			<h1 class="m-0 text-dark">
				Expenses
			</h1>
		</div>
		<div class="col-sm-6">
			<ol class="breadcrumb float-sm-right">
				<li class="breadcrumb-item"><a href="/">Home</a></li>
				<li class="breadcrumb-item active">Expenses</li>
			</ol>
		</div>
	</div>
@stop

@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<div class="table-filter d-sm-flex">
						<div class="form-group">
							<div>
								<label for="filter_operated_at_from">Operation Date start</label>
							</div>
							<div>
								<input type="date" class="form-control" id="filter_operated_at_from" name="filter_operated_at_from" placeholder="" value="{{ \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}" style="width: 200px;">
							</div>
						</div>
						<div class="form-group ml-3">
							<div>
								<label for="filter_operated_at_to">Operation Date end</label>
							</div>
							<div>
								<input type="date" class="form-control" id="filter_operated_at_to" name="filter_operated_at_to" placeholder="" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" style="width: 200px;">
							</div>
						</div>
						<div class="form-group ml-3">
							<div>
								<label for="filter_operation_type_id">Type</label>
							</div>
							<div>
								<select class="form-control" id="filter_operation_type_id" name="filter_operation_type_id">
									<option value=""></option>
									@foreach($types as $type)
										<option value="{{ $type->id }}">{{ $type->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group ml-3">
							<div>
								<label for="filter_payment_method_id">Payment method</label>
							</div>
							<div>
								<select class="form-control" id="filter_payment_method_id" name="filter_payment_method_id">
									<option value=""></option>
									@foreach($paymentMethods as $paymentMethod)
										<option value="{{ $paymentMethod->id }}">{{ $paymentMethod->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group align-self-end text-right ml-auto">
							<a href="javascript:void(0)" data-toggle="modal" data-url="/operation/add" data-action="/operation" data-method="POST" data-title="Add Operation" class="btn btn-secondary btn-sm" title="Add">Add</a>
						</div>
					</div>
					<table id="operationTable" class="table table-hover table-sm table-bordered table-striped table-data table-no-filter">
						<thead>
							<tr>
								<th class="text-center">Date</th>
								<th class="text-center">Type</th>
								<th class="text-center">Payment method</th>
								<th class="text-center">Amount</th>
								<th class="text-center">Extra</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalLabel">Edit</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form id="operation">
					<div class="modal-body"></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@stop

@section('css')
	<link rel="stylesheet" href="{{ asset('vendor/toastr/toastr.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/admin/common.css') }}">
@stop

@section('js')
	<script src="{{ asset('vendor/toastr/toastr.min.js') }}"></script>
	<script src="{{ asset('js/admin/common.js?v=' . time()) }}"></script>
	<script>
		$(function() {
			function getList() {
				var $selector = $('#operationTable tbody');

				$.ajax({
					url: "{{ route('operationList') }}",
					type: 'GET',
					dataType: 'json',
					data: {
						"filter_operated_at_from": $('#filter_operated_at_from').val(),
						"filter_operated_at_to": $('#filter_operated_at_to').val(),
						"filter_operation_type_id": $('#filter_operation_type_id').val(),
						"filter_payment_method_id": $('#filter_payment_method_id').val(),
					},
					success: function(result) {
						if (result.status !== 'success') {
							toastr.error(result.reason);
							return;
						}

						if (result.html) {
							$selector.html(result.html);
						} else {
							$selector.html('<tr><td colspan="30" class="text-center">Nothing found</td></tr>');
						}
					}
				})
			}

			getList();

			$(document).on('click', '[data-url]', function(e) {
				e.preventDefault();

				var url = $(this).data('url'),
					action = $(this).data('action'),
					method = $(this).data('method'),
					title = $(this).data('title');

				if (!url) {
					toastr.error('Incorrect parameters');
					return null;
				}

				$('.modal .modal-title, .modal .modal-body').empty();

				$.ajax({
					url: url,
					type: 'GET',
					dataType: 'json',
					success: function(result) {
						if (result.status === 'error') {
							toastr.error(result.reason);
							return null;
						}

						if (action && method) {
							$('#modal form').attr('action', action).attr('method', method);
							$('button[type="submit"]').show();
						} else {
							$('button[type="submit"]').hide();
						}
						$('#modal .modal-title').text(title);
						$('#modal .modal-body').html(result.html);
						$('#modal').modal('show');
					}
				});
			});

			$(document).on('submit', '#operation', function(e) {
				e.preventDefault();

				var action = $(this).attr('action'),
					method = $(this).attr('method'),
					data = $(this).serializeArray();

				$.ajax({
					url: action,
					type: method,
					data: data,
					success: function(result) {
						if (result.status !== 'success') {
							toastr.error(result.reason);
							return;
						}

						$('#modal').modal('hide');
						getList();
						toastr.success(result.message);
					}
				});
			});

			$(document).on('change', '#filter_operated_at_from, #filter_operated_at_to, #filter_operation_type_id, #filter_payment_method_id', function(e) {
				getList();
			});
		});
	</script>
@stop