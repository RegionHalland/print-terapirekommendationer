<h1>Terapirekommendationer - Print</h1>

<form method="post" action="options.php">
    {{ settings_fields( 'azure-uploads-options' ) }}
    {{ do_settings_sections( 'azure-uploads-options' ) }}
    {{ submit_button() }}
</form>

<form action="{{ admin_url('admin-post.php') }}" method="post">
	<input type="hidden" name="action" value="createPdf">
	{{ submit_button( 'Ladda ner PDF' ) }}
</form>