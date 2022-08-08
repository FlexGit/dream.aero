@extends('admin/layouts.master')

@section('content_header')
	<div class="row mb-2">
		<div class="col-sm-6">
			<h1 class="m-0 text-dark">
				Products
			</h1>
		</div>
		<div class="col-sm-6">
			<ol class="breadcrumb float-sm-right">
				<li class="breadcrumb-item"><a href="/">Home</a></li>
				<li class="breadcrumb-item active">Products</li>
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
							<a href="javascript:void(0)" data-toggle="modal" data-url="/product/add" data-action="/product" data-method="POST" data-title="Add" class="btn btn-secondary btn-sm" title="Add">Add</a>
						</div>
					</div>
					<table id="productTable" class="table table-hover table-sm table-bordered table-striped table-data table-no-filter">
						<thead>
						<tr>
							<th class="text-center">Name</th>
							<th class="text-center">Public name</th>
							<th class="text-center">Alias</th>
							<th class="text-center text-nowrap">Product type</th>
							<th class="text-center text-nowrap">Duration, min</th>
							<th class="text-center">Is active</th>
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
				<form id="product">
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
				var $selector = $('#productTable tbody');

				$selector.html('<tr><td colspan="30" class="text-center">Loading data...</td></tr>');

				$.ajax({
					url: '{{ route('productList') }}',
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

			$(document).on('submit', '#product', function(e) {
				e.preventDefault();

				var action = $(this).attr('action'),
					method = $(this).attr('method'),
					$iconFile = $('#icon_file');

				var formData = new FormData($(this)[0]);
				if ($iconFile.val()) {
					formData.append('icon_file', $iconFile.prop('files')[0]);
				}

				var realMethod = method;
				if (method === 'PUT') {
					formData.append('_method', 'PUT');
					realMethod = 'POST';
				}

				$.ajax({
					url: action,
					type: realMethod,
					data: formData,
					processData: false,
					contentType: false,
					cache: false,
					success: function(result) {
						if (result.status !== 'success') {
							toastr.error(result.reason);
							return;
						}

						var msg = 'Product successfully ';
						if (method === 'POST') {
							msg += 'added';
						} else if (method === 'PUT') {
							msg += 'saved';
						} else if (method === 'DELETE') {
							msg += 'deleted';
						}

						$('#modal').modal('hide');
						getList();
						toastr.success(msg);
					}
				});
			});

			$(document).on('show.bs.modal', '#modal', function(e) {
				var $durationSelector = $('#duration');

				if ($durationSelector.length) {
					var duration = $durationSelector.data('duration') ? $durationSelector.data('duration') : 0;
					getDurationByProductType(duration);
				}
			});

			function getDurationByProductType(duration) {
				var $durationSelector = $('#duration'),
					$userSelector = $('#user_id'),
					$productTypeIdSelector = $('#product_type_id'),
					durations = $productTypeIdSelector.find(':selected').data('duration'),
					withUser = $productTypeIdSelector.find(':selected').data('with_user'),
					productTypeAlias = $productTypeIdSelector.find(':selected').data('alias'),
					$coursesContainer = $('.courses-container'),
					$vipContainer = $('.vip-container');

				if (withUser) {
					$userSelector.closest('.form-group').removeClass('d-none');
				} else {
					$userSelector.closest('.form-group').addClass('d-none');
				}

				if (productTypeAlias == 'courses') {
					$coursesContainer.removeClass('hidden');
					//$vipContainer.addClass('hidden');
				} else if (productTypeAlias == 'vip') {
					//$vipContainer.removeClass('hidden');
					$coursesContainer.addClass('hidden');
				} else {
					$coursesContainer.addClass('hidden');
					//$vipContainer.addClass('hidden');
				}

				$durationSelector.html('<option></option>');
				$.each(durations, function(key, value) {
					$durationSelector.append('<option value="' + value + '" ' + ((value === duration) ? 'selected' : '')+ '>' + value + '</option>');
				});
			}

			$(document).on('change', '#product_type_id', function(e) {
				getDurationByProductType(0);
			});

			$(document).on('click', '.js-product-icon-delete', function(e) {
				if (!confirm('Are you sure?')) {
					return false;
				}

				$div = $(this).closest('div');

				$.ajax({
					url: '/product/' + $(this).data('id') + '/icon/delete',
					type: 'PUT',
					dataType: 'json',
					success: function(result) {
						if (result.status === 'error') {
							toastr.error(result.reason);
							return null;
						}

						$div.hide();
						toastr.success('File successfully deleted');
					}
				});
			});
		});
	</script>
@stop