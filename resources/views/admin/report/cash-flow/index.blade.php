@extends('admin/layouts.master')

@section('content_header')
	<div class="row mb-2">
		<div class="col-sm-6">
			<h1 class="m-0 text-dark">
				Cash Flow
			</h1>
		</div>
		<div class="col-sm-6">
			<ol class="breadcrumb float-sm-right">
				<li class="breadcrumb-item"><a href="/">Home</a></li>
				<li class="breadcrumb-item"><a href="/report">Reports</a></li>
				<li class="breadcrumb-item active">Cash Flow</li>
			</ol>
		</div>
	</div>
@stop

@section('content')
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<div class="table-filter d-sm-flex mb-2">
						<div class="form-group">
							<label for="filter_date_from_at">Period start</label>
							<div>
								<input type="date" class="form-control" id="filter_date_from_at" name="filter_date_from_at" value="{{ \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}" style="width: 200px;">
							</div>
						</div>
						<div class="form-group ml-3">
							<label for="filter_date_to_at">Period end</label>
							<div>
								<input type="date" class="form-control" id="filter_date_to_at" name="filter_date_to_at" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" style="width: 200px;">
							</div>
						</div>
						<div class="form-group ml-3">
							<div>
								<label for="filter_operation_type">Type</label>
							</div>
							<div>
								<select class="form-control" id="filter_operation_type" name="filter_operation_type">
									<option value=""></option>
									<option value="expenses">Expenses</option>
									<option value="deals">Deals</option>
									<option value="taxes">Taxes</option>
									<option value="tips">Tips</option>
								</select>
							</div>
						</div>
						<div class="form-group ml-3 filter_product_container hidden">
							<div>
								<label for="filter_product_id">Product</label>
							</div>
							<div>
								<select class="form-control" id="filter_product_id" name="filter_product_id">
									<option value=""></option>
									@foreach($products ?? [] as $productTypeName => $productItem)
										<optgroup label="{{ $productTypeName }}"></optgroup>
										@foreach($productItem as $productId => $product)
											<option value="{{ $product->id }}">{{ $product->name }}</option>
										@endforeach
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group ml-3 filter_operation_type_container hidden">
							<div>
								<label for="filter_operation_type_id">Expenses</label>
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
						<div class="form-group ml-3" style="padding-top: 31px;">
							<button type="button" id="show_btn" class="btn btn-secondary">Show</button>
							<button type="button" id="export_btn" class="btn btn-light"><i class="far fa-file-excel"></i> Excel</button>
						</div>
					</div>
					<div id="reportTable"></div>
				</div>
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
	<script src="{{ asset('js/admin/common.js') }}"></script>
	<script>
		$(function() {
			function getList(isExport) {
				var $selector = $('#reportTable'),
					$btn = isExport ? $('#export_btn') : $('#show_btn'),
					$loader = $('<i class="fas fa-circle-notch fa-spin"></i>');

				$btn.attr('disabled', true);
				if (!isExport) {
					$selector.html($loader);
				}

				$.ajax({
					url: '{{ route('cashFlowList') }}',
					type: 'GET',
					dataType: 'json',
					data: {
						'filter_date_from_at': $('#filter_date_from_at').val(),
						'filter_date_to_at': $('#filter_date_to_at').val(),
						'filter_payment_method_id': $('#filter_payment_method_id').val(),
						'filter_operation_type': $('#filter_operation_type').val(),
						'filter_operation_type_id': $('#filter_operation_type_id').val(),
						'filter_product_id': $('#filter_product_id').val(),
						'is_export': isExport,
					},
					success: function(result) {
						//console.log(result);
						if (result.status !== 'success') {
							toastr.error(result.reason);
							return;
						}

						$btn.attr('disabled', false);

						if (result.fileName) {
							window.location.href = '/report/file/' + result.fileName;
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

			//getList(false);

			$(document).on('click', '#show_btn', function(e) {
				getList(false);
			});

			$(document).on('click', '#export_btn', function(e) {
				getList(true);
			});

			$(document).on('change', '#filter_operation_type', function(e) {
				if ($(this).val() === 'deals') {
					$('.filter_product_container').removeClass('hidden');
					$('.filter_operation_type_container').addClass('hidden');
				} else if ($(this).val() === 'expenses') {
					$('.filter_operation_type_container').removeClass('hidden');
					$('.filter_product_container').addClass('hidden');
				} else {
					$('.filter_operation_type_container, .filter_product_container').addClass('hidden');
				}
			});
		});
	</script>
@stop