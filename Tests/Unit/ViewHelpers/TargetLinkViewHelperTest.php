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
 * Test for Tx_News_ViewHelpers_TargetLinkViewHelper
 */
class Tx_News_Tests_Unit_ViewHelpers_TargetLinkViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @return Tx_News_ViewHelpers_TargetLinkViewHelper
	 * @support
	 */
	protected function getPreparedInstance() {
		$instance = new Tx_News_ViewHelpers_TargetLinkViewHelper();
		return $instance;
	}

	/**
	 * @test
	 */
	public function canCreateViewHelperClassInstance() {
		$instance = $this->getPreparedInstance();
		$this->assertInstanceOf('Tx_News_ViewHelpers_TargetLinkViewHelper', $instance);
	}


	/**
	 * Test if correct target is returned
	 *
	 * @test
	 * @dataProvider correctTargetIsReturnedDataProvider
	 * @return void
	 */
	public function correctTargetIsReturned($link, $expectedResult) {
		$viewHelper = new Tx_News_ViewHelpers_TargetLinkViewHelper();
		$this->assertEquals($viewHelper->render($link), $expectedResult);
	}

	/**
	 * Data provider
	 *
	 * @return array
	 */
	public function correctTargetIsReturnedDataProvider() {
		return array(
			'noTargetSetAndUrlDefined' => array(
				'www.typo3.org', ''
			),
			'noTargetSetAndIdDefined' => array(
				'123', ''
			),
			'IdAndTargetDefined' => array(
				'123 _blank', '_blank'
			),
			'UrlAndPopupDefined' => array(
				'www.typo3.org 300x400', ''
			),
			'ComplexExample' => array(
				'www.typo3.org _fo my-class', '_fo'
			),

		);
	}

}
