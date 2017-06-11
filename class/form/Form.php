<?php
require_once __DIR__ . '/../variable/Caster.php';
require_once __DIR__ . '/../filesystem/File.php';
/**
 * Retrives fields from form and return a desired object from it
 *
 * @author ensismoebius
 *        
 */
final class Form {
	
	/**
	 * The prefix uploaded file has to has
	 *
	 * @var string
	 */
	private $uploadedFilePrefix;
	
	/**
	 * Holds the destiny of files send by a form
	 * it must be informed when uploading some file
	 *
	 * @var string
	 */
	private $pathForFileUpload;
	
	/**
	 * Holds the filters for the properties
	 *
	 * @var array
	 */
	private $arrFilters;
	
	/**
	 * Holds the target object form must generate
	 *
	 * @var object
	 */
	private $targetObject;
	
	/**
	 * Holds the generated object
	 *
	 * @var object
	 */
	private $generatedObject;
	
	/**
	 * the source of data you will retrieve from
	 *
	 * @var integer
	 */
	private $getOrPost;
	
	/**
	 * the source of data you will retrieve from
	 *
	 * @var integer
	 */
	const TYPE_GET = 0;
	
	/**
	 * the source of data you will retrieve from
	 *
	 * @var integer
	 */
	const TYPE_POST = 1;
	
	// Constants for requests
	const SUCCESS = 1;
	const BAD_DATA = 2;
	const NO_REQUEST_DETECTED = 3;
	const NO_GET_OR_POST_SPECIFIED = 4;
	
	/**
	 * path for file upload
	 *
	 * @param string $pathForFileUpload        	
	 * @return Form
	 */
	public function setPathForFileUpload($pathForFileUpload) {
		$this->pathForFileUpload = $pathForFileUpload;
		return $this;
	}
	
	/**
	 * Informs the source of data you will retrieve from
	 *
	 * @param int $getOrPost        	
	 * @return Form
	 */
	public function setType($getOrPost): Form {
		$this->getOrPost = $getOrPost;
		return $this;
	}
	
	/**
	 * Sets an instace of the target object the form must generate
	 *
	 * @param object $object        	
	 * @return Form
	 */
	public function setTargetObject($object): Form {
		$this->targetObject = $object;
		return $this;
	}
	
	/**
	 * Informs if a request was made
	 *
	 * @return bool
	 */
	public function hasRequest(): bool {
		switch ($this->getOrPost) {
			case self::TYPE_GET :
				return count ( $_GET ) > 0 ? true : false;
				break;
			
			case self::TYPE_POST :
				return count ( $_POST ) > 0 ? true : false;
				break;
			
			default :
				return false;
		}
	}
	
	/**
	 * Generates the object from form data
	 *
	 * @return int
	 */
	public function generateObject(): int {
		
		// No request detected
		if (! $this->hasRequest ()) {
			return self::NO_REQUEST_DETECTED;
		}
		
		// You must set the source of data you will retrieve from
		switch ($this->getOrPost) {
			case self::TYPE_GET :
				// Sanitize data and generate the object
				// Try to validate all invalid fields
				$generatedObject = $this->validateAndSanitize ( $_GET );
				break;
			
			case self::TYPE_POST :
				// Sanitize data and generate the object
				// Try to validate all invalid fields
				$generatedObject = $this->validateAndSanitize ( $_POST, $_FILES );
				break;
			
			default :
				return self::NO_GET_OR_POST_SPECIFIED;
		}
		
		// If object is a boolean something goes wrong
		if (is_bool ( $generatedObject )) {
			return self::BAD_DATA;
		}
		
		// Sets the generated object
		$this->generatedObject = $generatedObject;
		return self::SUCCESS;
	}
	
	/**
	 * Register the form fields that must be filtered
	 *
	 * @param string $fieldName        	
	 * @param integer $filterType        	
	 */
	public function registerField(string $fieldName, int $filterType, bool $mandatory = true) {
		
		// This "false" means that, by default, all fields are invalid
		$this->arrFilters [false] [$mandatory] [$fieldName] = $filterType;
	}

	/**
	 * Set the filter of the field as valid 
	 * @param boolean $isMandatory
	 * @param string $fieldName
	 */
	private function validateField($isMandatory, $fieldName) {
		if (isset ( $this->arrFilters [true] [$isMandatory] [$fieldName] )) return;
		
		// Validates the field
		$this->arrFilters [true] [$isMandatory] [$fieldName] = $this->arrFilters [false] [$isMandatory] [$fieldName];
		unset ( $this->arrFilters [false] [$isMandatory] [$fieldName] );
	}
	
	/**
	 * Sanitizes fields and generate object
	 *
	 * @param array $arrFiltersAndFieldNames        	
	 * @param array $dataSource        	
	 * @return boolean|object
	 */
	private function validateAndSanitize(&$dataSource, &$fileDataSource = null) {
		
		// We start manipulating the default datasource ($_POS or $_GET)
		$manipulatedDataSource = &$dataSource;
		
		// Iterate over mandatory / non mandatory data
		foreach ( $this->arrFilters [false] as $isMandatory => $arrFiltersAndFieldNames ) {
			
			// Iterates over filters
			foreach ( $arrFiltersAndFieldNames as $fieldName => $filterType ) {
				
				if ($isMandatory) {
					// Filtering mandatory fields
					
					// Choosing the source of the data for mandatory fields
					if (isset ( $dataSource [$fieldName] )) {
						// There is a field in the default datasource
						$manipulatedDataSource = &$dataSource;
					} elseif (isset ( $fileDataSource [$fieldName] ) && trim($fileDataSource [$fieldName]['name']) != "") {
						
						// Im sending files too
						$manipulatedDataSource = &$fileDataSource;
						
						$file = new File ();
						$file->setCaminhoDoArquivo ( $fileDataSource [$fieldName] ['name'], false );
						$filename = $this->renameAndMoveFile ( $fileDataSource [$fieldName] ['tmp_name'], $file->getFileExtension () );
						
						// Keep the field invalidated and go to the next filter
						if (is_bool ( $filename )) {
							continue;
						}
						
						// Updates the file name
						$fileDataSource [$fieldName] = $filename;
						
						// Validates the field
						$this->validateField ( $isMandatory, $fieldName );
					} else {
						// I send nothing so all fields are invalid
						return false;
					}
				} else {
					// Filtering non mandatory fields
					
					// Choosing the source of the data for non mandatory fields
					if (isset ( $dataSource [$fieldName] )) {
						// There is a field in the default datasource
						$manipulatedDataSource = &$dataSource;
					} elseif (isset ( $fileDataSource [$fieldName] ) && trim($fileDataSource [$fieldName]['name']) != "") {
						// Im sending files too
						$manipulatedDataSource = &$fileDataSource;
						
						$file = new File ();
						$file->setCaminhoDoArquivo ( $fileDataSource [$fieldName] ['name'] );
						
						$filename = $this->renameAndMoveFile ( $fileDataSource [$fieldName] ['tmp_name'], $file->getFileExtension () );
						
						// Keep the field invalidated and go to the next filter
						if (is_bool ( $filename )) {
							continue;
						}
						
						// Updates the file name
						$fileDataSource [$fieldName] = $filename;
						
						// Validates the field
						$this->validateField ( $isMandatory, $fieldName );
					}
					
					// Well... we find nothing, but the field is not mandatory anyway...
					$manipulatedDataSource = &$dataSource;
				}
				
				// If the expected field does not exist create it with
				// a null value just to simplify the algorithm
				if (! isset ( $manipulatedDataSource [$fieldName] )) {
					$manipulatedDataSource [$fieldName] = null;
				}
				
				// Filtering array data from data source
				if (is_array ( $manipulatedDataSource [$fieldName] )) {
					
					foreach ( $manipulatedDataSource [$fieldName] as &$value ) {
						
						// Keep the field invalidated and go to the next filter
						if ($isMandatory && trim ( $value ) == "") {
							continue;
						}
						
						$result = filter_var ( trim ( $value ), $filterType );
						
						// If result is boolean the filtering has failed
						// So keep the field invalidated and go to the next filter
						if (is_bool ( $result )) {
							continue;
						}
						
						// Filter is ok, go on
						$this->validateField ( $isMandatory, $fieldName );
						$value = $result;
					}
					
					// got to next field
					continue;
				}
				
				if ($isMandatory && trim ( $manipulatedDataSource [$fieldName] ) == "") {
					// Keep the field invalidated and go to the next filter
					continue;
				}
				$result = filter_var ( trim ( $manipulatedDataSource [$fieldName] ), $filterType );
				
				// If result is boolean the filtering has failed
				// So keep the field invalidated and go to the next filter
				if (is_bool ( $result )) {
					continue;
				}
				
				// Filter is ok, go on
				$this->validateField ( $isMandatory, $fieldName );
				$manipulatedDataSource [$fieldName] = $result;
			}
		}
		
		$dataSource = array_merge ( $dataSource, $fileDataSource );
		
		return Caster::arrayToClassCast ( $dataSource, $this->targetObject );
	}
	
	
	/**
	 * Return all invalid fields
	 * @return Generator
	 */
	public function getAllInvalidFields(){
		foreach ($this->arrFilters[false] as $arrFilters) {
			foreach ($arrFilters as $fieldName => $filterType) {
				yield $fieldName;
			}
		}
	}
	
	// Rename and move file
	public function renameAndMoveFile(string $filename, string $extension) {
		
		// The path MUST be set
		if ($this->pathForFileUpload == "") {
			return false;
		}
		
		$finalFileName = $this->uploadedFilePrefix . "." . microtime ( true ) . "." . $extension;
		
		try {
			move_uploaded_file ( $filename, $this->pathForFileUpload . DIRECTORY_SEPARATOR . $finalFileName );
			return $finalFileName;
		} catch ( Exception $e ) {
			// The file naming and creation cant goes wrong
			return false;
		}
	}
	
	/**
	 * Returns the generated object
	 *
	 * @return object
	 */
	public function getObject() {
		return $this->generatedObject;
	}
	
	/**
	 * uploaded file prefix
	 *
	 * @param string $uploadedFilePrefix        	
	 * @return Form
	 */
	public function setUploadedFilePrefix($uploadedFilePrefix) {
		$this->uploadedFilePrefix = trim ( $uploadedFilePrefix );
		return $this;
	}
}
?>

