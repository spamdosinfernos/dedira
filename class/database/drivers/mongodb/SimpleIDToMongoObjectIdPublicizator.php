<?php
require_once __DIR__ . '/../../../variable/ISpecialTypesPublicizator.php';
/**
 * Converts string to MongoDb ObjectID
 *
 * @author ensismoebius
 *        
 */
class SimpleIDToMongoObjectIdPublicizator implements ISpecialTypesPublicizator {
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see ISpecialTypesPublicizator::getSpecialType()
	 */
	public function getSpecialType(): string {
		return "string";
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see ISpecialTypesPublicizator::convert()
	 */
	public function convert($type) {
		return new MongoDB\BSON\ObjectID($type);
	}
}