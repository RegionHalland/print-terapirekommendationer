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
		$this->cache = PRINT_TR_PLUGIN_CACHE_DIR;
		$this->blade = new Blade($this->views, $this->cache);
		$this->frontpage = intval(get_option('page_on_front'));
		$this->pages = get_pages([
			'sort_order' => 'asc',
			'sort_column' => 'menu_order',
			'parent' => $this->frontpage,
			'exclude' => array(8694)
		]);
		$this->foreword = get_post(8694);

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
		wp_enqueue_script( 'print-tr-js', PRINT_TR_PLUGIN_URL . '/dist/js/app.min.js', false, '', true );
		wp_enqueue_style( 'print-tr-css', PRINT_TR_PLUGIN_URL . '/dist/css/index.min.css', false, false, '' );
	}

	/**
	 * Creates an option page and adds it to the settings menu.
	 * @return void
	 */
	public function createOptionsPage() 
	{
		$title = 'Skapa PDF (14)';
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

		// Set size of page - default A4
		if (!isset($_POST['pagesize'])){
			$intPagesize = 4;
		} else {
			$intPagesize = $_POST['pagesize'];
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

		$rendered = self::getChaptersAsHtml($chapters, $intPagesize);

 		$prince = new PrinceWrapper($this->princeBinary);
    	
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
	private function getChaptersAsHtml($chapters, $intPagesize) {
		
		// Get all pages with children
		foreach ($chapters as $key => $chapter) {
			$chapters[$key]->children = get_pages([
				'sort_order' => 'asc',
				'sort_column' => 'menu_order',
				'parent' => $chapter->ID,
			]);
		}

		// Render all pages with correct size
		$rendered = $this->blade->view()->make('book.book-'.$intPagesize, ["chapters" => $chapters], ["foreword" => $this->foreword])->render();

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