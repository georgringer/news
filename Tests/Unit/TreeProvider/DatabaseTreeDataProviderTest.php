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
 * Tests for Tx_News_TreeProvider_DatabaseTreeDataProvider
 */
class Tx_News_Tests_Unit_TreeProvider_DatabaseTreeDataProviderTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var array
	 */
	private $backupGlobalVariables;

	public function setUp() {
		$this->backupGlobalVariables = array(
			'BE_USER' => $GLOBALS['BE_USER'],
		);
	}

	public function tearDown() {
		foreach ($this->backupGlobalVariables as $key => $data) {
			$GLOBALS[$key] = $data;
		}
		unset($this->backupGlobalVariables);
	}

	/**
	 * @test
	 */
	public function canSingleCategoryAclSettingBeActivated() {
		$mockTemplateParser = $this->getAccessibleMock('Tx_News_TreeProvider_DatabaseTreeDataProvider',array('dummy'), array(), '',
			$callOriginalConstructor = FALSE);

		$this->assertEquals(FALSE, $mockTemplateParser->_call('isSingleCategoryAclActivated'));

		// Add TsConfig array
		$GLOBALS['BE_USER']->userTS['tx_news.'] = array();
		$this->assertEquals(FALSE, $mockTemplateParser->_call('isSingleCategoryAclActivated'));

		// Set the access
		$GLOBALS['BE_USER']->userTS['tx_news.']['singleCategoryAcl'] = '1';
		$this->assertEquals(TRUE, $mockTemplateParser->_call('isSingleCategoryAclActivated'));

		// Remove access again
		$GLOBALS['BE_USER']->userTS['tx_news.']['singleCategoryAcl'] = '0';
		$this->assertEquals(FALSE, $mockTemplateParser->_call('isSingleCategoryAclActivated'));
	}

}
