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
 * ViewHelper to download a file
 *
 * = Examples =
 *
 * <code title="Defaults">
 * <n:category.countNews settings="{settings}" category="{category}" />
 * </code>
 *
 * Output:
 * 8
 *
 * @package TYPO3
 * @subpackage tx_news2
 * @version $Id$
 */
class Tx_News2_ViewHelpers_Category_CountNewsViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * Count the news belonging to a category
	 * 
	 * @param Tx_News2_Domain_Model_Category $category
	 * @param array $settings
	 * @param boolean $countSubCategories if news of sub categories need to be counted too
	 * @return integer
	 */
	public function render(Tx_News2_Domain_Model_Category $category, array $settings = array(), $countSubCategories = TRUE) {
			// categories either from subcategories or strict by current category
		$categories = ($countSubCategories) ? Tx_News2_Service_RecursiveCategoryListService::find($category->getUid()) : $category->getUid();

		/** @var Tx_News2_Domain_Repository_NewsRepository */
		$newsRepository = t3lib_div::makeInstance('Tx_News2_Domain_Repository_NewsRepository');
		$newsRepository->setCategories($categories);
		$newsRepository->setStoragePage(Tx_News2_Service_RecursivePidListService::find($settings['startingpoint'], $settings['recursive']));

		$newsRepository->setCategorySettings('or');
		return $newsRepository->countByTest();
	}
}

?>