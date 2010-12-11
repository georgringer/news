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
 * Controller of news categories
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @version $Id$
 */
class Tx_News2_Controller_CategoryController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * @var Tx_News2_Domain_Model_CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * Initializes the current action
	 */
	public function initializeAction() {
		$this->categoryRepository = t3lib_div::makeInstance('Tx_News2_Domain_Repository_CategoryRepository');

		$this->categoryRepository->setOrder($this->settings['orderBy'] . ' ' . $this->settings['orderAscDesc']);
		$this->categoryRepository->setParentCategories($this->settings['category']);
		$this->categoryRepository->setStoragePage(Tx_News2_Service_RecursivePidListService::find($this->settings['startingpoint'], $this->settings['recursive']));
		$this->categoryRepository->setLimit($this->settings['limit']);
		$this->categoryRepository->setOffset($this->settings['offset']);
	}

	/**
	 * Output a list view of categories
	 */
	public function listAction() {
		$categories = $this->categoryRepository->findByIdList();
		$this->view->assign('categories', $categories);
	}


}