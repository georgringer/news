<?php
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

/**
 * Test class for Tx_News_Utility_Url
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Georg Ringer <typo3@ringerge.org>
 */
class Tx_News_Tests_Unit_Utility_UrlTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @test
	 * @dataProvider correctUrlIsDeliveredDataProvider
	 */
	public function correctUrlIsDelivered($actual, $expected) {
		$this->assertEquals($expected, Tx_News_Utility_Url::prependDomain($actual));
	}

	/**
	 * Data provider
	 *
	 * @return array
	 */
	public function correctUrlIsDeliveredDataProvider() {
		$currentDomain = \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL');
		return array(
			'absoluteUrlIsUsed' => array(
				$currentDomain . 'index.php?id=123', $currentDomain . 'index.php?id=123'
			),
			'relativeUrlIsUsed' => array(
				'index.php?id=123', $currentDomain . 'index.php?id=123'
			),
			'domainOnlyIsGiven' => array(
				$currentDomain, $currentDomain
			),
		);
	}
}
