<?php
/***************************************************************
 *  Copyright notice
 *  (c) 2011 Georg Ringer <typo3@ringerge.org>
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
 * Base controller
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_Controller_NewsBaseController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * Initializes the view before invoking an action method.
	 * Override this method to solve assign variables common for all actions
	 * or prepare the view in another way before the action is called.
	 *
	 * @param \TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view The view to be initialized
	 * @return void
	 */
	protected function initializeView(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view) {
		$view->assign('contentObjectData', $this->configurationManager->getContentObject()->data);
		$view->assign('emConfiguration', Tx_News_Utility_EmConfiguration::getSettings());
	}


	/**
	 * Error handling if no news entry is found
	 *
	 * @param string $configuration configuration what will be done
	 * @throws InvalidArgumentException
	 * @return void
	 */
	protected function handleNoNewsFoundError($configuration) {
		if (empty($configuration)) {
			return;
		}

		$configuration = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $configuration, TRUE);

		switch ($configuration[0]) {
			case 'redirectToListView':
				$this->redirect('list');
				break;
			case 'redirectToPage':
				if (count($configuration) === 1 || count($configuration) > 3) {
					$msg = sprintf('If error handling "%s" is used, either 2 or 3 arguments, split by "," must be used', $configuration[0]);
					throw new InvalidArgumentException($msg);
				}
				$this->uriBuilder->reset();
				$this->uriBuilder->setTargetPageUid($configuration[1]);
				$this->uriBuilder->setCreateAbsoluteUri(TRUE);
				if (\TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SSL')) {
					$this->uriBuilder->setAbsoluteUriScheme('https');
				}
				$url = $this->uriBuilder->build();

				if (isset($configuration[2])) {
					$this->redirectToUri($url, 0, (int)$configuration[2]);
				} else {
					$this->redirectToUri($url);
				}

				break;
			case 'pageNotFoundHandler':
				$GLOBALS['TSFE']->pageNotFoundAndExit('No news entry found.');
				break;
			default:
				// Do nothing, it might be handled in the view.
		}
	}

}
