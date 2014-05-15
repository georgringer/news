<?php
/***************************************************************
 *  Copyright notice
 *  (c) 2014 Georg Ringer <typo3@ringerge.org>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Tests for Tx_News_Hooks_CmsLayout
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_Tests_Unit_Hooks_CmsLayoutTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/** @var  \TYPO3\CMS\Core\Tests\AccessibleObjectInterface */
	protected $cmsLayout;

	public function setUp() {
		parent::setUp();
		$this->cmsLayout = $this->getAccessibleMock('Tx_News_Hooks_CmsLayout', array('dummy'));
	}

	/**
	 * @test
	 * @return void
	 */
	public function getArchiveSettingAddsValueIfFilled() {
		$flexform = array();
		$this->addContentToFlexform($flexform, 'settings.archiveRestriction', 'something');

		$this->cmsLayout->_set('flexformData', $flexform);
		$this->cmsLayout->_call('getArchiveSettings');
		$this->assertEquals(count($this->cmsLayout->_get('tableData')), 1);
	}

	/**
	 * @test
	 * @return void
	 */
	public function getDetailPidSettingAddsValueIfFilled() {
		$flexform = array();
		$this->addContentToFlexform($flexform, 'settings.detailPid', '9999999999', 'additional');

		$this->cmsLayout->_set('flexformData', $flexform);
		$this->cmsLayout->_call('getDetailPidSetting');
		$this->assertEquals(count($this->cmsLayout->_get('tableData')), 1);
	}


	/**
	 * @test
	 * @return void
	 */
	public function getTagRestrictionSettingAddsValueIfFilled() {
		$flexform = array();
		$this->addContentToFlexform($flexform, 'settings.tags', '9999999999', 'additional');

		$this->cmsLayout->_set('flexformData', $flexform);
		$this->cmsLayout->_call('getTagRestrictionSetting');
		$this->assertEquals(count($this->cmsLayout->_get('tableData')), 1);
	}



	/**
	 * @test
	 * @return void
	 */
	public function getListPidSettingAddsValueIfFilled() {
		$flexform = array();
		$this->addContentToFlexform($flexform, 'settings.listPid', '9999999999', 'additional');

		$this->cmsLayout->_set('flexformData', $flexform);
		$this->cmsLayout->_call('getListPidSetting');
		$this->assertEquals(count($this->cmsLayout->_get('tableData')), 1);
	}

	/**
	 * @test
	 * @return void
	 */
	public function getOrderBySettingAddsValueIfFilled() {
		$flexform = array();
		$this->addContentToFlexform($flexform, 'settings.orderBy', 'fo');

		$this->cmsLayout->_set('flexformData', $flexform);
		$this->cmsLayout->_call('getOrderSettings');
		$this->assertEquals(count($this->cmsLayout->_get('tableData')), 1);
	}

	/**
	 * @test
	 * @return void
	 */
	public function getOrderDirectionSettingAddsValueIfFilled() {
		$flexform = array();
		$this->addContentToFlexform($flexform, 'settings.orderDirection', 'asc');

		$this->assertEquals($this->cmsLayout->_call('getOrderDirectionSetting'), '');

		$this->cmsLayout->_set('flexformData', $flexform);
		$out = $this->cmsLayout->_call('getOrderDirectionSetting');
		$this->assertEquals((strlen($out) > 1), TRUE);
	}

	/**
	 * @test
	 * @return void
	 */
	public function getTopNewsFirstSettingAddsValueIfFilled() {
		$flexform = array();
		$this->addContentToFlexform($flexform, 'settings.topNewsFirst', '1', 'additional');

		$this->assertEquals($this->cmsLayout->_call('getTopNewsFirstSetting'), '');

		$this->cmsLayout->_set('flexformData', $flexform);
		$out = $this->cmsLayout->_call('getTopNewsFirstSetting');
		$this->assertEquals((strlen($out) > 1), TRUE);
	}


	/**
	 * @test
	 * @return void
	 */
	public function getOffsetLimitSettingsAddsValueIfFilled() {
		$flexform = array();
		$this->addContentToFlexform($flexform, 'settings.offset', '1', 'additional');
		$this->cmsLayout->_set('flexformData', $flexform);
		$this->cmsLayout->_call('getOffsetLimitSettings');
		$this->assertEquals(count($this->cmsLayout->_get('tableData')), 1);

		$this->addContentToFlexform($flexform, 'settings.limit', '1', 'additional');
		$this->cmsLayout->_set('flexformData', $flexform);
		$this->cmsLayout->_call('getOffsetLimitSettings');
		$this->assertEquals(count($this->cmsLayout->_get('tableData')), 2);

		$this->addContentToFlexform($flexform, 'settings.hidePagination', '0', 'additional');
		$this->cmsLayout->_set('flexformData', $flexform);
		$this->cmsLayout->_call('getOffsetLimitSettings');
		$this->assertEquals(count($this->cmsLayout->_get('tableData')), 2);

		$this->addContentToFlexform($flexform, 'settings.hidePagination', '1', 'additional');
		$this->cmsLayout->_set('flexformData', $flexform);
		$this->cmsLayout->_call('getOffsetLimitSettings');
		$this->assertEquals(count($this->cmsLayout->_get('tableData')), 3);
	}

	/**
	 * @test
	 * @return void
	 */
	public function getDateMenuSettingsAddsValueIfFilled() {
		$flexform = array();
		$this->addContentToFlexform($flexform, 'settings.dateField', 'field');
		$this->cmsLayout->_set('flexformData', $flexform);
		$this->cmsLayout->_call('getDateMenuSettings');
		$this->assertEquals(count($this->cmsLayout->_get('tableData')), 1);
	}


	/**
	 * @test
	 * @return void
	 */
	public function getTimeRestrictionSettingAddsValueIfFilled() {
		$flexform = array();
		$this->addContentToFlexform($flexform, 'settings.timeRestriction', 'fo');
		$this->cmsLayout->_set('flexformData', $flexform);
		$this->cmsLayout->_call('getTimeRestrictionSetting');
		$this->assertEquals(count($this->cmsLayout->_get('tableData')), 1);

		$this->addContentToFlexform($flexform, 'settings.timeRestrictionHigh', 'bar');
		$this->cmsLayout->_set('flexformData', $flexform);
		$this->cmsLayout->_call('getTimeRestrictionSetting');
		$this->assertEquals(count($this->cmsLayout->_get('tableData')), 2);
	}

	/**
	 * @test
	 * @return void
	 */
	public function getTemplateLayoutSettingsAddsValueIfFilled() {
		$flexform = array();
		$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['templateLayouts'] = array(
			array('bar', 'fo')
		);
		$this->addContentToFlexform($flexform, 'settings.templateLayout', 'fo', 'template');
		$this->cmsLayout->_set('flexformData', $flexform);
		$this->cmsLayout->_call('getTemplateLayoutSettings', 1);
		$this->assertEquals(count($this->cmsLayout->_get('tableData')), 1);
	}

	/**
	 * @test
	 * @return void
	 */
	public function getOverrideDemandSettingsAddsValueIfFilled() {
		$flexform = array();
		$this->addContentToFlexform($flexform, 'settings.disableOverrideDemand', '1', 'additional');
		$this->cmsLayout->_set('flexformData', $flexform);
		$this->cmsLayout->_call('getOverrideDemandSettings');
		$this->assertEquals(count($this->cmsLayout->_get('tableData')), 1);
	}


	/**
	 * Add content to given flexform
	 *
	 * @param array $flexform configuration
	 * @param string $key key of field
	 * @param string $value value of field
	 * @param string $sheet name of sheet
	 * @return void
	 */
	protected function addContentToFlexform(array &$flexform, $key, $value, $sheet = 'sDEF') {
		$flexform = array(
			'data' => array(
				$sheet => array(
					'lDEF' => array(
						$key => array(
							'vDEF' => $value
						)
					)
				)
			)
		);
	}

}
