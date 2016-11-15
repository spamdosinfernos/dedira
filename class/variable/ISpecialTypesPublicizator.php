<?php
/**
 * Rules for special types on publicization
 * @author ensismoebius
 *
 */
interface ISpecialTypesPublicizator {
	
	/**
	 * Gets the special type name to detect
	 * 
	 * @return String $typeName
	 */
	public function getSpecialType(): string;
	
	/**
	 * Converts the detected type to an target type
	 * 
	 * @param $type mixed        	
	 * @return mixed
	 */
	public function convert($type);
}
?>