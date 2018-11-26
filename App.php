<?php

namespace TrPrint;

use Philo\Blade\Blade;
use PrinceXMLPhp\PrinceWrapper;

class App
{	
	public function __construct()
	{
		$this->princeBinary = '/usr/bin/prince';
		$this->views = __DIR__ . '/src/php/views';
		$this->cache = wp_upload_dir()['basedir'] . '/cache';
		$this->blade = new Blade($this->views, $this->cache);
		$this->frontpage = intval(get_option('page_on_front'));
		$this->pages = get_pages([
			'sort_order' => 'asc',
			'sort_column' => 'menu_order',
			'parent' => $this->frontpage
		]);

		add_action( 'admin_enqueue_scripts', array($this, 'enqueue') );
		add_action( 'admin_menu', array($this, 'createOptionsPage') );
		add_action( 'admin_post_createPdf', array($this, 'createPdf') );
	}

	/**
	 * Enqueue scripts and styles.
	 * @return void
	 */
	public function enqueue()
	{
		wp_enqueue_script( 'azure-uploads-tab-js', PRINT_TR_PLUGIN_URL . '/dist/index.min.js', false, '', true );
		wp_enqueue_style( 'azure-uploads-tab-css', PRINT_TR_PLUGIN_URL . '/dist/index.min.css', false, false, '' );
	}

	/**
	 * Creates an option page and adds it to the settings menu.
	 * @return void
	 */
	public function createOptionsPage() 
	{
		$title = 'Print Terapirekommendationer';
		$slug = 'print-terapirekommendationer';

		// Add options page
		add_options_page(
			$title, 
			$title, 
			'manage_options', 
			$slug, 
			array($this, 'renderOptionsPage')
		);
	}

	/**
	 * Render the options page
	 * @return void
	 */
	public function renderOptionsPage()
	{
		$tree = self::buildTree($this->pages, $this->frontpage);

		$page = $this->blade->view()->make('settings', [
			'tree' => $tree
		])->render();

		echo $page;
	}

	/**
	 * Creates the PDF File
	 * @return void
	 */
	public function createPdf() {
		// Throw error if Prince is not installed on the server.
		if (!file_exists($this->princeBinary)) {
			throw new \Exception('Could not find Prince binary. Make sure you installed it on the server. https://www.princexml.com/doc-install/#linux');
		}

		// Throw error if there are no posts.
		if (!isset($_POST['posts'])){
			throw new \Exception('No posts selected');
		}

		$selectedChapters = $_POST['posts'];

		$chapters = array();

		foreach ($this->pages as $key => $value) {
			if (in_array($value->ID, $selectedChapters)) {
				array_push($chapters, $value);
			}
		}

		$rendered = self::getChaptersAsHtml($chapters);

 		$prince = new PrinceWrapper($this->princeBinary);
    	//$prince->addStyleSheet(__DIR__.'/min.css');
		$err = [];
		
		header('Content-Type: application/pdf');
		header('Content-Disposition: attachment; filename="tr_' . date("Y-m-d") . '.pdf"');

		$pdf = $prince->convert_string_to_passthru($rendered, $err);
	}

	/**
	 * Returns the chapters with children as a HTML string
	 * @return string
	 * @param array of pages.
	 */
	private function getChaptersAsHtml($chapters) {
		
		foreach ($chapters as $key => $chapter) {
			$chapters[$key]->children = get_pages([
				'sort_order' => 'asc',
				'sort_column' => 'menu_order',
				'parent' => $chapter->ID,
			]);
		}

		$rendered = $this->blade->view()->make('book.book', [
			"chapters" => $chapters
		])->render();

		return $rendered;
	}

	/**
     * Builds a tree from a flat array of pages
     * https://stackoverflow.com/a/28429487
     * @param array $posts     Array of posts.
     * @param int   $parentId  ID of the highest ancestor to build the tree from.
     * @return array
     */
    private function buildTree(array &$posts, $parentId = 0) 
    {
        $branch = array();

        foreach ($posts as &$post) {
            if ($post->post_parent == $parentId) {
                $children = self::buildTree($posts, $post->ID);
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