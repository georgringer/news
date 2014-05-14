<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Frans Saris <franssaris@gmail.com>
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
 * TemplateLayout utility class unit tests
 */
class Tx_News_Tests_Unit_Utility_TemplateLayoutTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function templatesFoundInTypo3ConfVars() {

		$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['templateLayouts'] = array(
			0 => array(
				0 => 'Layout 1',
				1 => 'layout1'
			),
			1 => array(
				0 => 'Layout 2',
				1 => 'layout2'
			),
		);

		$templateLayoutUtility = $this->getAccessibleMock('Tx_News_Utility_TemplateLayout', array('getTemplateLayoutsFromTsConfig'));
		$templateLayoutUtility->expects($this->once())->method('getTemplateLayoutsFromTsConfig')->will($this->returnValue(array()));
		$templateLayouts = $templateLayoutUtility->_call('getAvailableTemplateLayouts', 1);
		$this->assertSame($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['templateLayouts'], $templateLayouts);
	}

	/**
	 * @test
	 */
	public function templatesFoundInPageTsConfig() {
		$tsConfigArray = array(
			'layout1' => 'Layout 1',
			'layout2' => 'Layout 2',
		);
		$result = array(
			0 => array(
				0 => 'Layout 1',
				1 => 'layout1'
			),
			1 => array(
				0 => 'Layout 2',
				1 => 'layout2'
			),
		);

		// clear TYPO3_CONF_VARS
		unset($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['templateLayouts']);

		$templateLayoutUtility = $this->getAccessibleMock('Tx_News_Utility_TemplateLayout', array('getTemplateLayoutsFromTsConfig'));
		$templateLayoutUtility->expects($this->once())->method('getTemplateLayoutsFromTsConfig')->will($this->returnValue($tsConfigArray));
		$templateLayouts = $templateLayoutUtility->_call('getAvailableTemplateLayouts', 1);
		$this->assertSame($result, $templateLayouts);
	}

	/**
	 * @test
	 */
	public function templatesFoundInCombinedResources() {
		$tsConfigArray = array(
			'layout1' => 'Layout 1',
			'layout2' => 'Layout 2',
		);
		$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['templateLayouts'] = array(
			0 => array(
				0 => 'Layout 4',
				1 => 'layout4'
			),
		);
		$result = array(
			0 => array(
				0 => 'Layout 4',
				1 => 'layout4'
			),
			1 => array(
				0 => 'Layout 1',
				1 => 'layout1'
			),
			2 => array(
				0 => 'Layout 2',
				1 => 'layout2'
			),
		);

		$templateLayoutUtility = $this->getAccessibleMock('Tx_News_Utility_TemplateLayout', array('getTemplateLayoutsFromTsConfig'));
		$templateLayoutUtility->expects($this->once())->method('getTemplateLayoutsFromTsConfig')->will($this->returnValue($tsConfigArray));
		$templateLayouts = $templateLayoutUtility->_call('getAvailableTemplateLayouts', 1);
		$this->assertSame($result, $templateLayouts);
	}
}