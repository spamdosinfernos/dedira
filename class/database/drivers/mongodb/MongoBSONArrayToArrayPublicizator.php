<?php
require_once __DIR__ . '/../../../variable/ISpecialTypesPublicizator.php';

/**
 * Converts MongoDb ObjectID to string
 * @author ensismoebius
 */
class MongoBSONArrayToArrayPublicizator implements ISpecialTypesPublicizator {

	/**
	 * {@inheritdoc}
	 *
	 * @see ISpecialTypesPublicizator::getSpecialType()
	 */
	public function getSpecialType(): string {
		return "MongoDB\Model\BSONArray";
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see ISpecialTypesPublicizator::convert()
	 */
	public function convert($type) {
		return $type->getArrayCopy ();
	}
}