<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2013 Georg Ringer <typo3@ringerge.org>
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
 * Test for Tx_News_ViewHelpers_TargetLinkViewHelper
 */
class Tx_News_Tests_Unit_ViewHelpers_TargetLinkViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var $objectManager \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * @param $objectManager \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 * @return void
	 */
	protected function injectObjectManager(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager) {
		$this->objectManager = $objectManager;
	}

	/**
	 * @return Tx_News_ViewHelpers_TargetLinkViewHelper
	 * @support
	 */
	protected function getPreparedInstance() {
		$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		$instance = $objectManager->get('Tx_News_ViewHelpers_TargetLinkViewHelper');
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
