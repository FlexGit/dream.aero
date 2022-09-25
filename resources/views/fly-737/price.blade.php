@extends('layouts.master')

@section('title')
	{{ $page->meta_title }}
@stop
@section('description', $page->meta_description)

@section('content')
	<div class="breadcrumbs container"><a href="{{ url('/') }}">@lang('main-fly737.home.title')</a> <span>@lang('main-fly737.price.title')</span></div>

	<article class="article">
		<div class="container">
			<h1 class="block-title">@lang('main-fly737.price.title')</h1>
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
	</article>
@endsection

@push('css')
	<link rel="stylesheet" href="{{ asset('css/fly-737/pricestyle.css?v=1') }}">
@endpush

@push('scripts')
@endpush