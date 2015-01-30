<?php

namespace GeorgRinger\News\Tests\Unit\ViewHelpers\Be\Security;

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
 * Test case for the IfAccessToTableIsAllowedViewHelper class.
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class IfAccessToTableIsAllowedViewHelperTest extends \TYPO3\CMS\Fluid\Tests\Unit\ViewHelpers\ViewHelperBaseTestcase {

	/**
	 * @var \GeorgRinger\News\ViewHelpers\Be\Security\IfAccessToTableIsAllowedViewHelper
	 */
	protected $viewHelper;

	/**
	 * @var \TYPO3\CMS\Fluid\Core\ViewHelper\Arguments
	 */
	protected $mockArguments;

	public function setUp() {
		parent::setUp();

		$GLOBALS['BE_USER'] = $this->getMock('TYPO3\\CMS\\Core\\Authentication\\BackendUserAuthentication', array('dummy'));

		$this->viewHelper = $this->getAccessibleMock('GeorgRinger\\News\\ViewHelpers\\Be\\Security\\IfAccessToTableIsAllowedViewHelper', array('renderThenChild', 'renderElseChild'));
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
