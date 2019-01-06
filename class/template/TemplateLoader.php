<?php
require_once __DIR__ . '/../../lib/vendor/autoload.php';

/**
 * Changes the default behavior of template class
 */
class TemplateLoader extends Twig_Environment {
	const ERROR_1 = 1;

	/**
	 * @var array
	 */
	private $data;

	public function __construct(array $paths = array()) {
		parent::__construct ( new Twig_Loader_Filesystem ( $paths ) );
	}

	public function assign(string $key, $data) {
		$this->data [$key] = $data;
	}
	
	public function mergeAssignments(array $data){
		$this->data = array_merge($this->data, $data);
	}

	public function clearAssigns() {
		$this->data = array ();
	}

	public function render($filename, array $context = array()) {
		return parent::render ( $filename, $this->data );
	}
}
?>