<h1>Terapirekommendationer - Print</h1>
<hr>
<strong>
	<input type="checkbox" name="select_all" id="select_all" class="js-select-all">
	<label for="select_all">Välj alla</label>
</strong>
<form action="{{ admin_url('admin-post.php') }}" method="post">
	<input type="hidden" name="action" value="createPdf">
	@if(isset($tree) && !empty($tree))
	<ul class="js-checkboxes">
		@each('tree-child-list', $tree, 'item')
	</ul>
	@endif
	{{ submit_button( 'Ladda ner PDF' ) }}
</form>