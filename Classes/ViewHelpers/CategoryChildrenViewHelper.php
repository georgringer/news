<?php

namespace GeorgRinger\News\ViewHelpers;

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
 * ViewHelper to get children of a category
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class CategoryChildrenViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @var \GeorgRinger\News\Domain\Repository\CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * Inject a news repository to enable DI
	 *
	 * @param \GeorgRinger\News\Domain\Repository\CategoryRepository $categoryRepository
	 * @return void
	 */
	public function injectCategoryRepository(\GeorgRinger\News\Domain\Repository\CategoryRepository $categoryRepository) {
		$this->categoryRepository = $categoryRepository;
	}

	/**
	 * Render the viewhelper
	 *
	 * @param integer $category category uid
	 * @param string $as name of the new object
	 * @return string rendered content
	 */
	public function render($category, $as) {
		$this->templateVariableContainer->add($as, $this->categoryRepository->findChildren($category));
		$output = $this->renderChildren();
		$this->templateVariableContainer->remove($as);
		return $output;
	}
}
