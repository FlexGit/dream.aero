@extends('layouts.master')

@section('title')
	{{ $page->meta_title }}
@stop
@section('description', $page->meta_description)

@section('content')
	<div class="breadcrumbs container"><a href="{{ url('/') }}">@lang('main.home.title')</a> <span>@lang('main.contacts.title')</span></div>

	<div class="article">
		<div class="container">
			<h1 class="block-title">@lang('main.contacts.title')</h1>
			<div class="article-content">
				<div class="row">
					<div class="col-md-12 price">
						<div class="prices">
							<h3>Coming soon...</h3>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('css')
	<link rel="stylesheet" href="{{ asset('css/fly-737/pricestyle.css?v=1') }}">
@endpush