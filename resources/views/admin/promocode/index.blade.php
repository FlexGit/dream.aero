@extends('admin/layouts.master')

@section('content_header')
	<div class="row mb-2">
		<div class="col-sm-6">
			<h1 class="m-0 text-dark">
				Promocodes
			</h1>
		</div>
		<div class="col-sm-6">
			<ol class="breadcrumb float-sm-right">
				<li class="breadcrumb-item"><a href="/">Home</a></li>
				<li class="breadcrumb-item active">Promocodes</li>
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
						<div class="form-group align-self-end text-right ml-auto">
							<a href="javascript:void(0)" data-toggle="modal" data-url="/promocode/add" data-action="/promocode" data-method="POST" data-title="Add" class="btn btn-secondary btn-sm" title="Add">Add</a>
						</div>
					</div>
					<table id="promocodeTable" class="table table-hover table-sm table-bordered table-striped table-data table-no-filter">
						<thead>
							<tr>
								<th class="text-center">Number</th>
								<th class="text-center">Discount</th>
								<th class="text-center">Is active</th>
								<th class="text-center">Activity start date</th>
								<th class="text-center">Activity end date</th>
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
				<form id="promocode">
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
	<link rel="stylesheet" href="{{ asset('css/admin/common.css?v=' . time()) }}">
@stop

@section('js')
	<script src="{{ asset('vendor/toastr/toastr.min.js') }}"></script>
	<script src="{{ asset('js/admin/common.js?v=' . time()) }}"></script>
	<script>
		$(function() {
			function getList() {
				var $selector = $('#promocodeTable tbody');

				$selector.html('<tr><td colspan="30" class="text-center">Loading data...</td></tr>');

				$.ajax({
					url: "{{ route('promocodeList') }}",
					type: 'GET',
					dataType: 'json',
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

			$(document).on('submit', '#promocode', function(e) {
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

			/*$(document).on('show.bs.modal', '#modal', function() {
				$('#city_id').multiselect({
					includeSelectAllOption: true,
					selectAllText: 'Все города',
					buttonWidth: '100%',
					selectAllValue: 0,
					buttonTextAlignment: 'left',
					buttonText: function (options, select) {
						if (options.length === 0) {
							return '';
						} else {
							var labels = [];
							options.each(function () {
								if ($(this).attr('label') !== undefined) {
									labels.push($(this).attr('label'));
								} else {
									labels.push($(this).html());
								}
							});
							return labels.join(', ') + '';
						}
					},
				});
			});*/
		});
	</script>
@stop