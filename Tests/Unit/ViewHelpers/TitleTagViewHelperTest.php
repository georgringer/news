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
 * Test for Tx_News_ViewHelpers_TitleTagViewHelper
 */
class Tx_News_Tests_Unit_ViewHelpers_TitleTagViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController|\TYPO3\CMS\Core\Tests\AccessibleObjectInterface
	 */
	protected $tsfe = NULL;

	/**
	 * Set up
	 */
	public function setUp() {
		$this->tsfe = $this->getAccessibleMock('tslib_fe', array('dummy'), array(), '', FALSE);
		$GLOBALS['TSFE'] = $this->tsfe;
	}

	/**
	 * Test of strip tags viewhelper
	 *
	 * @test
	 * @return void
	 */
	public function titleTagIsSet() {
		$title = 'Some title';
		$viewHelper = $this->getMock('Tx_News_ViewHelpers_TitleTagViewHelper', array('renderChildren'));
		$viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue($title));

		$viewHelper->render();
		$this->assertEquals($title, $GLOBALS['TSFE']->page['title']);
		$this->assertEquals($title, $GLOBALS['TSFE']->indexedDocTitle);
	}
}