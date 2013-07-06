<?php
/***************************************************************
 *  Copyright notice
 *  (c) 2013 Georg Ringer <typo3@ringerge.org>
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
 * Tests for Tx_News_ViewHelpers_Widget_Controller_PaginateController
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Georg Ringer <typo3@ringerge.org>
 */
class Tx_News_Tests_Unit_ViewHelpers_Widget_Controller_PaginateControllerTest extends Tx_Extbase_Tests_Unit_BaseTestCase {

	/**
	 * @test
	 */
	public function initializationIsCorrect() {
		$controller = $this->getAccessibleMock('Tx_News_ViewHelpers_Widget_Controller_PaginateController', array('dummy'));
		$objects = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14);
		$configuration = array(
			'pagesBefore' => '10',
			'pagesAfter' => '3x',
			'forcedNumberOfLinks' => '9fo',
			'lessPages' => 0,
			'templatePath' => 'fo/bar',
			'itemsPerPage' => '3',
		);
		$widgetConfiguration = array('fo' => 'bar');
		$controller->_set('configuration', $configuration);
		$controller->_set(
			'widgetConfiguration',
			array(
				'configuration' => $widgetConfiguration,
				'objects' => $objects
			));

		$controller->initializeAction();
		$this->assertEquals($controller->_get('objects'), $objects);
		$this->assertEquals($controller->_get('configuration'), t3lib_div::array_merge_recursive_overrule($configuration, $widgetConfiguration, TRUE));
		$this->assertEquals($controller->_get('numberOfPages'), 5);
		$this->assertEquals($controller->_get('pagesBefore'), 10);
		$this->assertEquals($controller->_get('pagesAfter'), 3);
		$this->assertEquals($controller->_get('lessPages'), FALSE);
		$this->assertEquals($controller->_get('forcedNumberOfLinks'), 9);
		$this->assertEquals($controller->_get('templatePath'), PATH_site . 'fo/bar');
	}

}

?>