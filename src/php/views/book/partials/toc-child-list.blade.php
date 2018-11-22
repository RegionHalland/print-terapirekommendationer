<li class="table-of-contents__chapter">
	{{$item->post_title}}
	<a href="#{{$key+1}}"></a>
	@if (!empty($item->children))
			@foreach($item->children as $item)
				@include('book.partials.toc-child-list')
			@endforeach
	@endif
</li>