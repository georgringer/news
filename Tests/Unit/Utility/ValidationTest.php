<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Georg Ringer <typo3@ringerge.org>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Tests for Validation
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Georg Ringer <typo3@ringerge.org>
 */
class Tx_News_Tests_Unit_Utility_ValidationTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	const ALLOWED_FIELDS = 'author,uid,title,teaser,author,tstamp,crdate,datetime,categories.title';

	/**
	 * Test if default file format works
	 *
	 * @test
	 * @dataProvider orderDataProvider
	 * @return void
	 */
	public function testForValidOrdering($expectedFields, $expected) {
		$validation = Tx_News_Utility_Validation::isValidOrdering($expectedFields, self::ALLOWED_FIELDS);
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
