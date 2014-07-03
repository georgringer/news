<?php
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
class Tx_News_ViewHelpers_Widget_PaginateViewHelper extends \TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetViewHelper {

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
	 * Render everything
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryResultInterface $objects
	 * @param string $as
	 * @param mixed $configuration
	 * @param array $initial
	 * @internal param array $initial
	 * @return string
	 */
	public function render(\TYPO3\CMS\Extbase\Persistence\QueryResultInterface $objects, $as, $configuration = array('itemsPerPage' => 10, 'insertAbove' => FALSE, 'insertBelow' => TRUE), $initial = array()) {
		return $this->initiateSubRequest();
	}
}
