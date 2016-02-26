<?php

namespace GeorgRinger\News\Tests\Unit\ViewHelpers\Format;

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
use GeorgRinger\News\ViewHelpers\Format\FileSizeViewHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Tests for FileSizeViewHelper
 *
 */
class FileSizeViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * Test if default file format works
	 *
	 * @test
	 * @return void
	 */
	public function viewHelperReturnsFileSizeWithDefaultFormat() {
		$viewHelper = new FileSizeViewHelper();
		$actualResult = $viewHelper->render(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('news', 'Tests/Unit/ViewHelpers/Format/') . 'dummy.txt');
		$this->assertEquals('14.35 Ki', $actualResult);
	}

	/**
	 * Test if given format works
	 *
	 * @test
	 * @return void
	 */
	public function viewHelperReturnsFileSizeWithGivenFormat() {
		$viewHelper = new FileSizeViewHelper();
		$actualResult = $viewHelper->render(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('news', 'Tests/Unit/ViewHelpers/Format/') . 'dummy.txt', '| A| B| C');
		$this->assertEquals('14.35 A', $actualResult);
	}

	/**
	 * Test if exception handling works
	 * Using expectedException does not work supporting 7 + 8.
	 *
	 * @test
	 * @return void
	 */
	public function viewHelperThrowsExceptionIfFileNotFoundFor()
	{
		try {
			$viewHelper = new FileSizeViewHelper();
			$viewHelper->render('fo', 'bar');
		} catch (\Exception $e) {
			$expectedException = GeneralUtility::compat_version('8.0.0') ? 'TYPO3Fluid\Fluid\Core\Exception' : 'TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException';
			$this->assertEquals($expectedException, get_class($e));
		}
	}

}
