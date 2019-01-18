<h1>Terapirekommendationer - Skapa PDF</h1>
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
	A4 <input type="radio" name="pagesize" value="4" checked>&nbsp;&nbsp;
	A5 <input type="radio" name="pagesize" value="5">&nbsp;&nbsp;
	A6 <input type="radio" name="pagesize" value="6"><br><br> 
	{{ submit_button( 'Ladda ner PDF' ) }}
</form>