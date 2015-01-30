<?php

namespace GeorgRinger\News\Tests\Unit\Utility;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use GeorgRinger\News\Utility\Validation;

/**
 * Tests for Validation
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Georg Ringer <typo3@ringerge.org>
 */
class ValidationTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	const ALLOWED_FIELDS = 'author,uid,title,teaser,author,tstamp,crdate,datetime,categories.title';

	/**
	 * Test if default file format works
	 *
	 * @test
	 * @dataProvider orderDataProvider
	 * @return void
	 */
	public function testForValidOrdering($expectedFields, $expected) {
		$validation = Validation::isValidOrdering($expectedFields, self::ALLOWED_FIELDS);
		$this->assertEquals($validation, $expected);

	}

	public function orderDataProvider() {
		return array(
			'allowedOrdering' => array(
				'title,uid', TRUE
			),
			'allowedOrderingWithSorting' => array(
				'title ASC, uid', TRUE
			),
			'allowedOrderingWithSorting2' => array(
				'title ASC, uid DESC', TRUE
			),
			'allowedOrderingWithSorting3' => array(
				'title, uid desc,teaser', TRUE
			),
			'allowedOrderingWithDotsAndSorting' => array(
				'categories.title DESC, uid ASC,author,teaser desc', TRUE
			),
			'nonAllowedField' => array(
				'title,teaserFo,uid', FALSE
			),
			'nonAllowedSorting' => array(
				'title,teaser ASCx,uid', FALSE
			),
			'nonAllowedDoubleSorting' => array(
				'title,teaser ASC DESC,uid', FALSE
			),
			'nonAllowedDoubleFields' => array(
				'title teaser,uid', FALSE
			),
			'emptySorting' => array(
				'', TRUE
			),
			'emptySorting2' => array(
				' ', TRUE
			),

		);
	}

}
