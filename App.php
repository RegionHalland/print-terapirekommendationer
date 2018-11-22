<?php

namespace TrPrint;

use Philo\Blade\Blade;
use PrinceXMLPhp\PrinceWrapper;

class App
{	
	public function __construct()
	{
		$this->views = __DIR__ . '/src/php/views';
        $this->cache = wp_upload_dir()['basedir'] . '/cache';

		//add_action( 'admin_enqueue_scripts', array($this, 'enqueue') );
		add_action( 'admin_menu', array($this, 'createOptionsPage') );
		add_action( 'admin_post_createPdf', array($this, 'createPdf') );
	}

	/**
	 * Enqueue scripts and styles.
	 * @return void
	 */
	public function enqueue()
	{
		wp_enqueue_script( 'azure-uploads-tab-js', AUT_PLUGIN_URL . '/dist/index.min.js', false, '', true );
		wp_enqueue_style( 'azure-uploads-tab-css', AUT_PLUGIN_URL . '/dist/index.min.css', false, false, '' );
	}

	/**
	 * Creates an option page and adds it to the settings menu.
	 * @return void
	 */
	public function createOptionsPage() 
	{
		$title = 'Print Terapirekommendationer';
		$slug = 'print-terapirekommendationer';
		$sectionId = 'azure_uploads_settings_section';

		// Add options page
		add_options_page(
			$title, 
			$title, 
			'manage_options', 
			$slug, 
			array($this, 'renderOptionsPage')
		);

		// Add settings section to the created options page
		add_settings_section(
			$sectionId,   
			'Azure Uploads Options',
			function() {
				echo '<p>Fill out the form with your Azure credentials. All fields are required.</p>';
			},
			$slug
	    );

		// Add the tab name field
	    add_settings_field( 
			'azure_uploads_tab_name',
			'Uploads Tab Name',
			array($this, 'addSettingsFieldCallback'),
			$slug,
			$sectionId,
			array( 'azure_uploads_tab_name' )
		);

		// Add the settings fields
	    add_settings_field( 
			'azure_uploads_account_name',
			'Azure Account Name',
			array($this, 'addSettingsFieldCallback'),
			$slug,
			$sectionId,
			array( 'azure_uploads_account_name' )
		);

		add_settings_field( 
			'azure_uploads_account_key',
			'Azure Account Key',
			array($this, 'addSettingsFieldCallback'),
			$slug,
			$sectionId,
			array( 'azure_uploads_account_key' )
		);

		add_settings_field( 
			'azure_uploads_container_name',
			'Azure Container',
			array($this, 'addSettingsFieldCallback'),
			$slug,
			$sectionId,
			array( 'azure_uploads_container_name' )
		);

		// Register the created fields
		register_setting( $slug, 'azure_uploads_tab_name' );
		register_setting( $slug, 'azure_uploads_account_name' );
		register_setting( $slug, 'azure_uploads_account_key' );
		register_setting( $slug, 'azure_uploads_container_name' );  
	}

	/**
	 * Render the options page
	 * @return void
	 */
	public function renderOptionsPage()
	{ 
		$blade = new Blade($this->views, $this->cache);
		$page = $blade->view()->make('settings')->render();

		$pages = get_pages();

		//var_dump(self::build($pages));

		echo $page;
	}

	/**
	 * Callback to add the settings field
	 * @return void
	 */
	public function addSettingsFieldCallback($args)
	{
		echo '<input type="text" id="' . $args[0] . '" name="' . $args[0] . '" value="' . get_option($args[0]) . '"/>';
	}

	public function createPdf() {
		// Throw error if Prince is not installed on the server.
		if (!file_exists('/usr/bin/prince')) {
			throw new Exception('Could not find Prince binary. Make sure you installed it on the server. https://www.princexml.com/doc-install/#linux');
		}

		$rendered = $this->getChaptersAsHtml();

 		$prince = new PrinceWrapper('/usr/bin/prince');
    	//$prince->addStyleSheet(__DIR__.'/min.css');
		$err = [];
		
		header('Content-Type: application/pdf');
		header('Content-Disposition: attachment; filename="tr_' . date("Y-m-d") . '.pdf"');

		$pdf = $prince->convert_string_to_passthru($rendered, $err);
	}

	/**
	 * Echoes the table list view
	 * @return void
	 */
	public function render_list_page()
	{
		$page_id = 3913;
    	$page_id_chapter1 = 183;
    	$chapterOne = get_page($page_id_chapter1);

		$args = array(
			'sort_order' => 'asc',
			'sort_column' => 'menu_order',
			//'child_of' => 0,
			'parent' => $page_id,
		);
		$pages = get_pages($args);



		$chapters = array();

		foreach ($pages as $key => $value) {
			if ($key == 2) {
				array_push($chapters, $value);
			}
		}

		foreach ($chapters as $key => $chapter) {
			$argsTwo = array(
				'sort_order' => 'asc',
				'sort_column' => 'menu_order',
				'parent' => $chapter->ID,
			);
			$chapters[$key]->children = get_pages($argsTwo);

		}

	    $this->VIEWS_PATHS = apply_filters('RegionHalland/blade/view_paths', array(
        	get_stylesheet_directory() . '/views',
        	get_template_directory() . '/views'
        ));

		$this->CACHE_PATH = WP_CONTENT_DIR . '/uploads/cache/blade-cache';

        $blade = new Blade($this->VIEWS_PATHS, $this->CACHE_PATH);
        
        $rendered = $blade->view()->make('whole-chapter', [
    		"chapter_one" => $chapterOne,
    		"chapters" => $chapters
    	])->render();


    	$prince = new PrinceWrapper('/usr/bin/prince');
    	//$prince->addStyleSheet(__DIR__.'/min.css');
		$err = [];
		
		header('Content-Type: application/pdf');
		header('Content-Disposition: attachment; filename="foo.pdf"');

		$pdf = $prince->convert_string_to_passthru($rendered, $err);

    	echo $rendered;

		die();
	}

	/**
	 * Returns the chapters as a HTML string
	 * @return string
	 */
	public function getChaptersAsHtml()
	{
		$page_id = 3913;
    	$page_id_chapter1 = 183;
    	$chapterOne = get_page($page_id_chapter1);

		$args = array(
			'sort_order' => 'asc',
			'sort_column' => 'menu_order',
			//'child_of' => 0,
			'parent' => $page_id,
		);
		$pages = get_pages($args);

		$chapters = array();

		foreach ($pages as $key => $value) {
			if ($key == 2) {
				array_push($chapters, $value);
			}
		}

		foreach ($chapters as $key => $chapter) {
			$argsTwo = array(
				'sort_order' => 'asc',
				'sort_column' => 'menu_order',
				'parent' => $chapter->ID,
			);
			$chapters[$key]->children = get_pages($argsTwo);

		}

        $blade = new Blade($this->views, $this->cache);

        $rendered = $blade->view()->make('book', [
    		"chapter_one" => $chapterOne,
    		"chapters" => $chapters
    	])->render();

    	return $rendered;
	}


	/**
     * Builds a tree from a flat array of pages
     * https://stackoverflow.com/a/28429487
     * @param array $posts     Array of posts.
     * @param int   $parentId  ID of the highest ancestor to build the tree from.
     * @param int   $currentId ID of the current post.
     * @param int   $frontpage ID of the sites frontpage.
     * @return array
     */
    private function build(array &$posts, $parentId = 0) 
    {
        $branch = array();

        foreach ($posts as &$post) {
            if ($post->post_parent == $parentId) {
                $children = self::build($posts, $post->ID);
                if ($children) {
                    $post->children = $children;
                }
                $branch[$post->ID] = $post;
                unset($post);
            }
        }

        return $branch;
    }

}