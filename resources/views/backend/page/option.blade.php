@foreach($parentData as $parent)
	@if ( ! isset($data['id']) || $data['id'] != $parent['id'])
		<option value="{{ $parent['id'] }}"
	        @if (old('parent_id') !== null && old('parent_id') == $parent['id'])
	            selected="selected"
	        @elseif (isset($data['parent_id']) && $data['parent_id'] == $parent['id'])
	            selected="selected"
	        @endif
	    >
			{{ $prefix }} &nbsp; {{ $parent['title'] }}
		</option>
		@if (isset($parents[$parent['id']]))
			@include('backend.page.option', ['parentData' => $parents[$parent['id']], 'prefix' => $prefix. '--'])
		@endif
	@endif
@endforeach