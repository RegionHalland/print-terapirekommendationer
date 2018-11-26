<li>
	<input type="checkbox" class="js-checkbox" name="posts[]" id="{{ $item->post_name }}" value="{{ $item->ID }}">
	<label for="{{ $item->post_name }}">{{ $item->post_title }}</span>
	@if (!empty($item->children))
	<ul style="padding-left: 1rem;">
		@foreach($item->children as $item)
			@include('tree-child-list')
		@endforeach
	</ul>
	@endif
</li>