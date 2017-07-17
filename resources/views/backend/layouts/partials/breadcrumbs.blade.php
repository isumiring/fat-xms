@if (isset($breadcrumbs))
	@foreach ($breadcrumbs as $breadcrumb)
	<li class="{{ $breadcrumb['class'] }}"><a href="{{ $breadcrumb['url'] }}">{!! $breadcrumb['text'] !!}</a></li>
	@endforeach
@endif