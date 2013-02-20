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

/**
 * This ViewHelper renders a Pagination of objects.
 *
 * = Examples =
 *
 * <code title="required arguments">
 * <f:widget.paginate objects="{blogs}" as="paginatedBlogs">
 *   // use {paginatedBlogs} as you used {blogs} before, most certainly inside
 *   // a <f:for> loop.
 * </f:widget.paginate>
 * </code>
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_ViewHelpers_Widget_PaginateViewHelper extends Tx_Fluid_Core_Widget_AbstractWidgetViewHelper {

	/**
	 * @var Tx_News_ViewHelpers_Widget_Controller_PaginateController
	 */
	protected $controller;

	/**
	 * Inject controller
	 *
	 * @param Tx_News_ViewHelpers_Widget_Controller_PaginateController $controller
	 * @return void
	 */
	public function injectController(Tx_News_ViewHelpers_Widget_Controller_PaginateController $controller) {
		$this->controller = $controller;
	}

	/**
	 * Override the default initialize functionality
	 * This function can be used to e.g. override the itemsPerPage by using
	 * $this->arguments['configuration']['itemsPerPage'] = 3;
	 *
	 * @return void
	 */
	public function initialize() {
		parent::initialize();
	}

	/**
	 * Render everything
	 *
	 * @param Tx_Extbase_Persistence_QueryResultInterface $objects
	 * @param string $as
	 * @param mixed $configuration
	 * @return string
	 */
	public function render(Tx_Extbase_Persistence_QueryResultInterface $objects, $as, $configuration = array('itemsPerPage' => 10, 'insertAbove' => FALSE, 'insertBelow' => TRUE)) {
		return $this->initiateSubRequest();
	}
}

?>