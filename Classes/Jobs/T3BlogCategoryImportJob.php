<?php

namespace GeorgRinger\News\Jobs;

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
 * Import job
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Ingo Renner <ingo@typo3.org>
 */
class T3BlogCategoryImportJob extends AbstractImportJob {

	/**
	 * Inject import dataprovider service
	 *
	 * @param \GeorgRinger\News\Service\Import\T3BlogCategoryDataProviderService $importDataProviderService
	 * @return void
	 */
	public function injectImportDataProviderService(\GeorgRinger\News\Service\Import\T3BlogCategoryDataProviderService $importDataProviderService) {
		$this->importDataProviderService = $importDataProviderService;
	}

	/**
	 * Inject import service
	 *
	 * @param \GeorgRinger\News\Domain\Service\CategoryImportService $importService
	 * @return void
	 */
	public function injectImportService(\GeorgRinger\News\Domain\Service\CategoryImportService $importService) {
		$this->importService = $importService;
	}
}
