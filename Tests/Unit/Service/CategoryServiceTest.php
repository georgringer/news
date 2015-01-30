<?php

namespace GeorgRinger\News\Tests\Unit\Service;

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
use GeorgRinger\News\Service\CategoryService;

/**
 * Test class for CategoryService
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Georg Ringer <typo3@ringerge.org>
 */
class CategoryServiceTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @test
	 * @dataProvider removeValuesFromStringDataProvider
	 */
	public function removeValuesFromString($expected, $given) {
		$result = CategoryService::removeValuesFromString($given[0], $given[1]);
		$this->assertEquals($expected, $result);
	}

	/**
	 * Data provider
	 *
	 * @return array
	 */
	public function removeValuesFromStringDataProvider() {
		return array(
			'simpleExampleWithRemovalAtEnd' => array(
				'1,2,3,4', array('1,2,3,4,5', '5')
			),
			'simpleExampleWithMixedRemovals' => array(
				'1,2,3,4', array('1,7,2,9,3,4', '9,7')
			),
			'removalIsSame' => array(
				'', array('1,2,3', '3,2,1')
			),
			'noRemovalFound' => array(
				'1,2,3', array('1,2,3', '9,8,7')
			),
			'noInputGiven' => array(
				'', array('', '9,8,7')
			),
		);
	}
}
