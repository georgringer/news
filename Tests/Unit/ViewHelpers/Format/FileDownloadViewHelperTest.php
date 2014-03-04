<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2014 Georg Ringer <typo3@ringerge.org>
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
 * Tests for Tx_News_ViewHelpers_Format_FileDownloadViewHelper
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_Tests_Unit_ViewHelpers_Format_FileDownloadViewHelperTest extends \TYPO3\CMS\Fluid\Tests\Unit\ViewHelpers\ViewHelperBaseTestcase {

	/**
	 * @var \Tx_News_ViewHelpers_Format_FileDownloadViewHelper
	 */
	protected $viewHelper;

	/**
	 * @var \TYPO3\CMS\Fluid\Core\ViewHelper\Arguments
	 */
	protected $mockArguments;

	public function setUp() {
		parent::setUp();
		$this->viewHelper = $this->getAccessibleMock('Tx_News_ViewHelpers_Format_FileDownloadViewHelper', array('renderChildren'));
		$this->injectDependenciesIntoViewHelper($this->viewHelper);
		$this->viewHelper->initializeArguments();
	}

	/**
	 * Test if exception handling works
	 *
	 * @test
	 * @expectedException Tx_Fluid_Core_ViewHelper_Exception
	 * @return void
	 */
	public function viewHelperThrowsExceptionIfFileNotFound() {
		$viewHelper = new Tx_News_ViewHelpers_Format_FileDownloadViewHelper();
		$viewHelper->render('any file');
	}

	/**
	 * Test if default file format works
	 *
	 * @test
	 * @return void
	 */
	public function viewHelperReturnsFileSizeWithDefaultFormat() {

		$file = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('news', 'Tests/Unit/ViewHelpers/Format/') . 'dummy.txt';
		$actualResult = $this->viewHelper->render($file);
		$this->assertEquals('<a href="' . $file .'" class="download-link basic-class txt"></a>', $actualResult);
	}


}
