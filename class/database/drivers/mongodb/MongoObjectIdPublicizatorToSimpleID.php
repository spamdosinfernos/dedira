<?php
require_once __DIR__ . '/../../../variable/ISpecialTypesPublicizator.php';
/**
 * Converts MongoDb ObjectID to string
 *
 * @author ensismoebius
 *        
 */
class MongoObjectIdPublicizatorToSimpleID implements ISpecialTypesPublicizator {
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see ISpecialTypesPublicizator::getSpecialType()
	 */
	public function getSpecialType(): string {
		return "MongoDB\BSON\ObjectID";
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see ISpecialTypesPublicizator::convert()
	 */
	public function convert($type) {
		return $type->__toString();
	}
}