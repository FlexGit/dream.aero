@extends('layouts.master')

@section('title')
	{{ $page->meta_title }}
@stop
@section('description', $page->meta_description)

@section('content')
	<div class="breadcrumbs container"><a href="/">@lang('main.home.title')</a> <span>@lang('main.payment.title')</span></div>

	<article class="article">
		<div class="container">
			<h1 class="article-title">@lang('main.payment.title')</h1>
			<div class="article-content">
				<div class="row">
					<div class="item">
						<p>{!! $message ?? '' !!}</p>
						<p>{!! $error ?? '' !!}</p>
					</div>
				</div>
			</div>
		</div>
	</article>
@endsection
