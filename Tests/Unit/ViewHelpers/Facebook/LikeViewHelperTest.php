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

require_once(dirname(__FILE__) . '/../ViewHelperBaseTestcase.php');


/**
 * Tests for Facebook_LikeViewHelper
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @author Georg Ringer <typo3@ringerge.org>
 */
class Tx_News2_Tests_Unit_ViewHelpers_Facebook_LikeViewHelperTest extends Tx_News2_ViewHelpers_ViewHelperBaseTestcase {

	/**
	 * var Tx_News2_ViewHelpers_Facebook_LikeViewHelper
	 */
	protected $viewHelper;

	public function setUp() {
		parent::setUp();
		$this->viewHelper = $this->getAccessibleMock('Tx_News2_ViewHelpers_Facebook_LikeViewHelper', array('renderChildren'));
		$this->injectDependenciesIntoViewHelper($this->viewHelper);
		$this->viewHelper->initializeArguments();
	}

	/**
	 * @test
	 * @todo: not yet working
	 */
	public function viewHelperReturnsFacebookLikeCode() {
		$mockTagBuilder = $this->getMock('Tx_Fluid_Core_ViewHelper_TagBuilder', array('setTagName', 'addAttribute', 'setContent'));
		$mockTagBuilder->expects($this->once())->method('setTagName')->with('fb:like');
		$mockTagBuilder->expects($this->once())->method('addAttribute')->with('href', 'http://www.typo3.org');
		$this->viewHelper->_set('tag', $mockTagBuilder);

		$this->viewHelper->expects($this->any())->method('renderChildren')->will($this->returnValue('some casasasontent'));

		$this->viewHelper->initialize();
		$out = $this->viewHelper->render('http://www.typo3.org');
		die($out);
	}



}
?>
