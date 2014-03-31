<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Georg Ringer <typo3@ringerge.org>
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
 * Tests for FileSizeViewHelper
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Georg Ringer <typo3@ringerge.org>
 */
class Tx_News_Tests_Unit_ViewHelpers_Format_FileSizeViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * Test if default file format works
	 *
	 * @test
	 * @return void
	 */
	public function viewHelperReturnsFileSizeWithDefaultFormat() {
		$viewHelper = new Tx_News_ViewHelpers_Format_FileSizeViewHelper();
		$actualResult = $viewHelper->render(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('news', 'Tests/Unit/ViewHelpers/Format/') . 'dummy.txt');
		$this->assertEquals('14.4 K', $actualResult);
	}

	/**
	 * Test if given format works
	 *
	 * @test
	 * @return void
	 */
	public function viewHelperReturnsFileSizeWithGivenFormat() {
		$viewHelper = new Tx_News_ViewHelpers_Format_FileSizeViewHelper();
		$actualResult = $viewHelper->render(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('news', 'Tests/Unit/ViewHelpers/Format/') . 'dummy.txt', '| A| B| C');
		$this->assertEquals('14.4 A', $actualResult);
	}

	/**
	 * Test if exception handling works
	 *
	 * @test
	 * @expectedException Tx_Fluid_Core_ViewHelper_Exception
	 * @return void
	 */
	public function viewHelperThrowsExceptionIfFileNotFound() {
		$viewHelper = new Tx_News_ViewHelpers_Format_FileSizeViewHelper();
		$viewHelper->render('fo', 'bar');
	}

}
