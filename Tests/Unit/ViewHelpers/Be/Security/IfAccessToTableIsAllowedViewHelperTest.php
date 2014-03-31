<?php
/***************************************************************
 * Copyright notice
 *
 * (c) 2014 Georg Ringer (typo3@ringerge.org)
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Test case for the Tx_News_ViewHelpers_Be_Security_IfAccessToTableIsAllowedViewHelper class.
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_Tests_Unit_ViewHelpers_Be_Security_IfAccessToTableIsAllowedViewHelperTest extends \TYPO3\CMS\Fluid\Tests\Unit\ViewHelpers\ViewHelperBaseTestcase {

	/**
	 * @var \Tx_News_ViewHelpers_Be_Security_IfAccessToTableIsAllowedViewHelper
	 */
	protected $viewHelper;

	/**
	 * @var \TYPO3\CMS\Fluid\Core\ViewHelper\Arguments
	 */
	protected $mockArguments;

	public function setUp() {
		parent::setUp();
		$this->viewHelper = $this->getAccessibleMock('Tx_News_ViewHelpers_Be_Security_IfAccessToTableIsAllowedViewHelper', array('renderThenChild', 'renderElseChild'));
		$this->injectDependenciesIntoViewHelper($this->viewHelper);
		$this->viewHelper->initializeArguments();
	}

	/**
	 * @test
	 */
	public function vieHelperReturnsTrueForAdmins() {
		$backup = $GLOBALS['BE_USER']->user['admin'];
		$GLOBALS['BE_USER']->user['admin'] = 1;

		$this->viewHelper->expects($this->at(0))->method('renderThenChild')->will($this->returnValue('foo'));

		$this->assertEquals('foo', $this->viewHelper->render('any_table'));

		$GLOBALS['BE_USER']->user['admin'] = $backup;
	}

	/**
	 * @test
	 */
	public function vieHelperReturnsFalseForEditorsAndUnknownTable() {
		$backup = $GLOBALS['BE_USER']->user['admin'];
		$GLOBALS['BE_USER']->user['admin'] = 0;

		$this->viewHelper->expects($this->at(0))->method('renderElseChild')->will($this->returnValue('foo'));

		$this->assertEquals('foo', $this->viewHelper->render('any_table'));

		$GLOBALS['BE_USER']->user['admin'] = $backup;
	}

	/**
	 * @test
	 */
	public function vieHelperReturnsTrueForEditorsAndKnownTable() {
		$backup = array($GLOBALS['BE_USER']->user['admin'], $GLOBALS['BE_USER']->groupData['tables_modify']);
		$GLOBALS['BE_USER']->user['admin'] = 0;
		$GLOBALS['BE_USER']->groupData['tables_modify'] = 'fo,bar,tt_content,pages';

		$this->viewHelper->expects($this->at(0))->method('renderThenChild')->will($this->returnValue('foo'));

		$this->assertEquals('foo', $this->viewHelper->render('tt_content'));

		$GLOBALS['BE_USER']->user['admin'] = $backup[0];
		$GLOBALS['BE_USER']->groupData['tables_modify'] = $backup[1];
	}

	/**
	 * Renders <f:then> child if BE user is allowed to edit given table, otherwise renders <f:else> child.
	 *
	 * @param string $table Name of the table
	 * @return string the rendered string
	 * @api
	 */
	public function render($table) {
		if ($GLOBALS['BE_USER']->isAdmin() || \TYPO3\CMS\Core\Utility\GeneralUtility::inList($GLOBALS['BE_USER']->groupData['tables_modify'], $table)) {
			return $this->renderThenChild();
		}
		return $this->renderElseChild();
	}
}
