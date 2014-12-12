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
 * Administration controller
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_Controller_AdministrationController extends Tx_News_Controller_NewsController {

	const SIGNAL_ADMINISTRATION_INDEX_ACTION = 'indexAction';
	const SIGNAL_ADMINISTRATION_NEWSPIDLISTING_ACTION = 'newsPidListingAction';

	/**
	 * Page uid
	 *
	 * @var integer
	 */
	protected $pageUid = 0;

	/**
	 * TsConfig configuration
	 *
	 * @var array
	 */
	protected $tsConfiguration = array();

	/**
	 * @var Tx_News_Domain_Repository_CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 */
	protected $configurationManager;

	/**
	 * Function will be called before every other action
	 *
	 * @return void
	 */
	public function initializeAction() {
		$this->pageUid = (int)\TYPO3\CMS\Core\Utility\GeneralUtility::_GET('id');
		$this->setTsConfig();
		parent::initializeAction();
	}

	/**
	 * Inject a news repository to enable DI
	 *
	 * @param Tx_News_Domain_Repository_CategoryRepository $categoryRepository
	 * @return void
	 */
	public function injectCategoryRepository(Tx_News_Domain_Repository_CategoryRepository $categoryRepository) {
		$this->categoryRepository = $categoryRepository;
	}

	/**
	 * Main action for administration
	 *
	 * @param Tx_News_Domain_Model_Dto_AdministrationDemand $demand
	 * @dontvalidate  $demand
	 * @return void
	 */
	public function indexAction(Tx_News_Domain_Model_Dto_AdministrationDemand $demand = NULL) {
		$this->redirectToPageOnStart();
		if (is_null($demand)) {
			$demand = $this->objectManager->get('Tx_News_Domain_Model_Dto_AdministrationDemand');

			// Preselect by TsConfig (e.g. tx_news.module.preselect.topNewsRestriction = 1)
			if (isset($this->tsConfiguration['preselect.'])
				&& is_array($this->tsConfiguration['preselect.'])
			) {
				unset($this->tsConfiguration['preselect.']['orderByAllowed']);

				foreach ($this->tsConfiguration['preselect.'] as $propertyName => $propertyValue) {
					\TYPO3\CMS\Extbase\Reflection\ObjectAccess::setProperty($demand, $propertyName, $propertyValue);
				}
			}
		}
		$demand = $this->createDemandObjectFromSettings($demand);

		$categories = $this->categoryRepository->findParentCategoriesByPid($this->pageUid);
		$idList = array();
		foreach ($categories as $c) {
			$idList[] = $c->getUid();
		}

		$assignedValues = array(
			'moduleToken' => $this->getToken(TRUE),
			'page' => $this->pageUid,
			'demand' => $demand,
			'news' => $this->newsRepository->findDemanded($demand, FALSE),
			'categories' => $this->categoryRepository->findTree($idList),
			'dateformat' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy']
		);

		$this->emitActionSignal('AdministrationController', self::SIGNAL_ADMINISTRATION_INDEX_ACTION, $assignedValues);
		$this->view->assignMultiple($assignedValues);
	}

	/**
	 * Shows a page tree including count of news + category records
	 *
	 * @param integer $treeLevel
	 * @return void
	 */
	public function newsPidListingAction($treeLevel = 2) {
		$tree = Tx_News_Utility_Page::pageTree($this->pageUid, $treeLevel);

		$rawTree = array();
		foreach ($tree->tree as $row) {
			$this->countRecordsOnPage($row);
			$rawTree[] = $row;
		}

		$assignedValues = array(
			'tree' => $rawTree,
			'treeLevel' => $treeLevel,
		);

		$this->emitActionSignal('AdministrationController', self::SIGNAL_ADMINISTRATION_NEWSPIDLISTING_ACTION, $assignedValues);
		$this->view->assignMultiple($assignedValues);
	}

	/**
	 * Redirect to form to create a news record
	 *
	 * @return void
	 */
	public function newNewsAction() {
		$this->redirectToCreateNewRecord('tx_news_domain_model_news');
	}

	/**
	 * Redirect to form to create a category record
	 *
	 * @return void
	 */
	public function newCategoryAction() {
		$this->redirectToCreateNewRecord('sys_category');
	}

	/**
	 * Redirect to form to create a tag record
	 *
	 * @return void
	 */
	public function newTagAction() {
		$this->redirectToCreateNewRecord('tx_news_domain_model_tag');
	}

	/**
	 * Create the demand object which define which records will get shown
	 *
	 * @param Tx_News_Domain_Model_Dto_AdministrationDemand $demand
	 * @return Tx_News_Domain_Model_Dto_NewsDemand
	 */
	protected function createDemandObjectFromSettings(Tx_News_Domain_Model_Dto_AdministrationDemand $demand) {
		$demand->setCategories($demand->getSelectedCategories());
		$demand->setOrder($demand->getSortingField() . ' ' . $demand->getSortingDirection());
		$demand->setStoragePage(Tx_News_Utility_Page::extendPidListByChildren($this->pageUid, (int)$demand->getRecursive()));
		$demand->setOrderByAllowed($this->settings['orderByAllowed']);

		// Ensure that always a storage page is set
		if ((int)$demand->getStoragePage() === 0) {
			$demand->setStoragePage('-3');
		}

		return $demand;
	}

	/**
	 * Update page record array with count of news & category records
	 *
	 * @param array $row page record
	 * @return void
	 */
	private function countRecordsOnPage(array &$row) {
		$pageUid = (int)$row['row']['uid'];

		/* @var $db \TYPO3\CMS\Core\Database\DatabaseConnection */
		$db = $GLOBALS['TYPO3_DB'];

		$row['countNews'] = $db->exec_SELECTcountRows(
			'*',
			'tx_news_domain_model_news',
			'pid=' . $pageUid . \TYPO3\CMS\Backend\Utility\BackendUtility::BEenableFields('tx_news_domain_model_news'));
		$row['countCategories'] = $db->exec_SELECTcountRows(
			'*',
			'sys_category',
			'pid=' . $pageUid . \TYPO3\CMS\Backend\Utility\BackendUtility::BEenableFields('sys_category'));

		$row['countNewsAndCategories'] = ($row['countNews'] + $row['countCategories']);
	}

	/**
	 * Redirect to tceform creating a new record
	 *
	 * @param string $table table name
	 * @return void
	 */
	private function redirectToCreateNewRecord($table) {
		$pid = $this->pageUid;
		if ($pid === 0) {
			if (isset($this->tsConfiguration['defaultPid.'])
				&& is_array($this->tsConfiguration['defaultPid.'])
				&& isset($this->tsConfiguration['defaultPid.'][$table])
			) {
				$pid = (int)$this->tsConfiguration['defaultPid.'][$table];
			}
		}

		$returnUrl = 'mod.php?M=web_NewsTxNewsM2&id=' . $this->pageUid . $this->getToken();
		$url = 'alt_doc.php?edit[' . $table . '][' . $pid . ']=new&returnUrl=' . urlencode($returnUrl);

		\TYPO3\CMS\Core\Utility\HttpUtility::redirect($url);
	}

	/**
	 * Set the TsConfig configuration for the extension
	 *
	 * @return void
	 */
	protected function setTsConfig() {
		$tsConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getPagesTSconfig($this->pageUid);
		if (isset($tsConfig['tx_news.']['module.']) && is_array($tsConfig['tx_news.']['module.'])) {
			$this->tsConfiguration = $tsConfig['tx_news.']['module.'];
		}
	}

	/**
	 * If defined in TsConfig with tx_news.module.redirectToPageOnStart = 123
	 * and the current page id is 0, a redirect to the given page will be done
	 *
	 * @return void
	 */
	protected function redirectToPageOnStart() {
		if ((int)$this->tsConfiguration['allowedPage'] > 0 && $this->pageUid !== (int)$this->tsConfiguration['allowedPage']) {
			$url = 'mod.php?M=web_NewsTxNewsM2&id=' . (int)$this->tsConfiguration['allowedPage'] . $this->getToken();
			\TYPO3\CMS\Core\Utility\HttpUtility::redirect($url);
		} elseif ($this->pageUid === 0 && (int)$this->tsConfiguration['redirectToPageOnStart'] > 0) {
			$url = 'mod.php?M=web_NewsTxNewsM2&id=' . (int)$this->tsConfiguration['redirectToPageOnStart'] . $this->getToken();
			\TYPO3\CMS\Core\Utility\HttpUtility::redirect($url);
		}
	}

	/**
	 * Get a CSRF token
	 *
	 * @param bool $tokenOnly Set it to TRUE to get only the token, otherwise including the &moduleToken= as prefix
	 * @return string
	 */
	protected function getToken($tokenOnly = FALSE) {
		$token = \TYPO3\CMS\Core\FormProtection\FormProtectionFactory::get()->generateToken('moduleCall', 'web_NewsTxNewsM2');
		if ($tokenOnly) {
			return $token;
		} else {
			return '&moduleToken=' . $token;
		}
	}
}
