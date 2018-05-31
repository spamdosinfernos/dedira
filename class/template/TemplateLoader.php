<?php
require_once __DIR__ . '/../../lib/vendor/autoload.php';

/**
 * Changes the default behavior of template class
 */
class TemplateLoader extends Twig_Environment {
	const ERROR_1 = 1;
	
	/**
	 * 
	 * @var array
	 */
	private $data;
	
	/**
	 * 
	 * @var string
	 */
	private $filename;
	
	public function __construct(string $folder) {
		$this->filename = $filename;
		parent::__construct ( new Twig_Loader_Filesystem ( $folder ) );
	}
	
	public function assign(string $key, $data){
		$this->data[$key] = $data;
	}
	
	public function clearAssigns(){
		$this->data = array();
	}
	
	public function render(string $filename){
		return parent::render($filename, $this->data);
	}
}
?>