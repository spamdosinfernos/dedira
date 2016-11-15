<?php
require_once __DIR__ . '/../../../variable/ISpecialTypesPublicizator.php';
/**
 * Converts MongoDb UTCDateTime to Datetime 
 * 
 * @author ensismoebius
 *        
 */
class MongoDateToDatetimePublicizator implements ISpecialTypesPublicizator {
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see ISpecialTypesPublicizator::getSpecialType()
	 */
	public function getSpecialType(): string {
		return "MongoDB\BSON\UTCDateTime";
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see ISpecialTypesPublicizator::convert()
	 */
	public function convert($type) {
		return $type->toDateTime();
	}
}