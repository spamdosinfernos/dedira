<?php
require __DIR__ . '/../../../../lib/vendor/autoload.php';
require_once __DIR__ . '/../../../variable/ISpecialTypesPublicizator.php';

/**
 * Converts Datetime to MongoDb UTCDateTime
 * @author ensismoebius
 */
class DatetimeToMongoDatePublicizator implements ISpecialTypesPublicizator {

	/**
	 * {@inheritdoc}
	 *
	 * @see ISpecialTypesPublicizator::getSpecialType()
	 */
	public function getSpecialType(): string {
		return "Datetime";
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see ISpecialTypesPublicizator::convert()
	 */
	public function convert($type) {
		if (! ($type instanceof DateTime)) {
			throwException ( "The variable must be an instance of DateTime" );
			return;
		}

		return new MongoDB\BSON\UTCDateTime ( $type );
	}
}