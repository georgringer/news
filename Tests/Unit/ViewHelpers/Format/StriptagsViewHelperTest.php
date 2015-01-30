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

/**
 * Tests for StriptagsViewHelper
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Georg Ringer <typo3@ringerge.org>
 */
class StriptagsViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * Test of strip tags viewhelper
	 *
	 * @test
	 * @return void
	 */
	public function stripTagsFromContent() {
		$viewHelper = $this->getMock('GeorgRinger\\News\\ViewHelpers\\Format\\StriptagsViewHelper', array('renderChildren'));
		$viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue('Test<p>Fo</p>'));
		$actualResult = $viewHelper->render();
		$this->assertEquals('TestFo', $actualResult);
	}

	/**
	 * Test if given format works
	 *
	 * @test
	 * @return void
	 */
	public function stripTagsFromContentWithAllowedTags() {
		$viewHelper = $this->getMock('GeorgRinger\\News\\ViewHelpers\\Format\\StriptagsViewHelper', array('renderChildren'));
		$viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue('Test<p>Fo</p><strong>Bar</strong>'));
		$actualResult = $viewHelper->render('<strong>');
		$this->assertEquals('TestFo<strong>Bar</strong>', $actualResult);
	}
}
