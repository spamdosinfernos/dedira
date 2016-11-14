<?php
require_once __DIR__ . '/../../../variable/ISpecialTypesPublicizator.php';
/**
 * Converts Datetime to MongoDb UTCDateTime
 *
 * @author ensismoebius
 *        
 */
class DatetimeToMongoDatePublicizator implements ISpecialTypesPublicizator {
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see ISpecialTypesPublicizator::getSpecialType()
	 */
	public function getSpecialType(): string {
		return "Datetime";
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see ISpecialTypesPublicizator::convert()
	 */
	public function convert($type) {
		return new MongoDB\BSON\UTCDateTime ( $type->format ( "U" ) * 1000 );
	}
}