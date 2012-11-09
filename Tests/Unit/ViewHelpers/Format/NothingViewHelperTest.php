<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Georg Ringer <typo3@ringerge.org>
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
 * Tests for NothingViewHelper
 *
 * * @author Georg Ringer <typo3@ringerge.org>
 */
class Tx_News_Tests_Unit_ViewHelpers_Format_NothingViewHelperTest extends Tx_Extbase_Tests_Unit_BaseTestCase {

	/**
	 * Test of strip tags viewhelper
	 *
	 * @test
	 * @return void
	 */
	public function noResultExpected() {
		$viewHelper = $this->getMock('Tx_News_ViewHelpers_Format_NothingViewHelper', array('renderChildren'));
		$viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue('whatever content'));
		$actualResult = $viewHelper->render();
		$this->assertEquals(NULL, $actualResult);
	}

}
?>