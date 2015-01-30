<?php

namespace GeorgRinger\News\Hooks;

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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Userfunc to render alternative label for media elements
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class ItemsProcFunc {

	/**
	 * Itemsproc function to extend the selection of templateLayouts in the plugin
	 *
	 * @param array &$config configuration array
	 * @return void
	 */
	public function user_templateLayout(array &$config) {
		/** @var \GeorgRinger\News\Utility\TemplateLayout $templateLayoutsUtility */
		$templateLayoutsUtility = GeneralUtility::makeInstance('GeorgRinger\\News\\Utility\\TemplateLayout');
		$templateLayouts = $templateLayoutsUtility->getAvailableTemplateLayouts($config['row']['pid']);
		foreach ($templateLayouts as $layout) {
			$additionalLayout = array(
				$GLOBALS['LANG']->sL($layout[0], TRUE),
				$layout[1]
			);
			array_push($config['items'], $additionalLayout);
		}
	}

	/**
	 * Modifies the select box of orderBy-options as a category menu
	 * needs different ones then a news action
	 *
	 * @param array &$config configuration array
	 * @return void
	 */
	public function user_orderBy(array &$config) {
		$newItems = '';

			// check if the record has been saved once
		if (is_array($config['row']) && !empty($config['row']['pi_flexform'])) {
			$flexformConfig = GeneralUtility::xml2array($config['row']['pi_flexform']);

				// check if there is a flexform configuration
			if (isset($flexformConfig['data']['sDEF']['lDEF']['switchableControllerActions']['vDEF'])) {
				$selectedActionList = $flexformConfig['data']['sDEF']['lDEF']['switchableControllerActions']['vDEF'];
					// check for selected action
				if (GeneralUtility::isFirstPartOfStr($selectedActionList, 'Category')) {
					$newItems = $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['orderByCategory'];
				} elseif (GeneralUtility::isFirstPartOfStr($selectedActionList, 'Tag')) {
					$this->removeNonValidOrderFields($config, 'tx_news_domain_model_tag');
					$newItems = $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['orderByTag'];
				} else {
					$newItems = $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['orderByNews'];
				}
			}
		}

			// if a override configuration is found
		if (!empty($newItems)) {
				// remove default configuration
			$config['items'] = array();
				// empty default line
			array_push($config['items'], array('', ''));

			$newItemArray = GeneralUtility::trimExplode(',', $newItems, TRUE);
			$languageKey = 'LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:flexforms_general.orderBy.';
			foreach ($newItemArray as $item) {
					// label: if empty, key (=field) is used
				$label = $GLOBALS['LANG']->sL($languageKey . $item, TRUE);
				if (empty($label)) {
					$label = htmlspecialchars($item);
				}
				array_push($config['items'], array($label, $item));
			}
		}
	}

	/**
	 * Remove not valid fields from ordering
	 *
	 * @param array $config tca items
	 * @param string $tableName table name
	 * @return void
	 */
	protected function removeNonValidOrderFields(array &$config, $tableName) {
		$allowedFields = array_keys($GLOBALS['TCA'][$tableName]['columns']);

		foreach($config['items'] as $key => $item) {
			if ($item[1] != '' && !in_array($item[1], $allowedFields)) {
				unset($config['items'][$key]);
			}
		}
	}

	/**
	 * Modifies the selectbox of available actions
	 *
	 * @param array &$config
	 * @return void
	 */
	public function user_switchableControllerActions(array &$config) {
		if (!empty($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['switchableControllerActions']['list'])) {
			$configuration = (int)$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['switchableControllerActions']['list'];
				switch ($configuration) {
					case 1:
						$this->removeActionFromList($config, 'News->list');
						break;
					case 2:
						$this->removeActionFromList($config, 'News->list;News->detail');
						break;
					default:
				}
		}

			// Add additional actions
		if (isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['switchableControllerActions']['newItems'])
				&& is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['switchableControllerActions']['newItems'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['switchableControllerActions']['newItems'] as $key => $label) {
				array_push($config['items'], array($GLOBALS['LANG']->sL($label), $key, ''));
			}
		}
	}

	/**
	 * Remove given action from switchableControllerActions
	 *
	 * @param array $config available items
	 * @param string $action action to be removed
	 * @return void
	 */
	private function removeActionFromList(array &$config, $action) {
		foreach ($config['items'] as $key => $item) {
			if ($item[1] === $action) {
				unset($config['items'][$key]);
				continue;
			}
		}
	}

	/**
	 * Generate a select box of languages to choose an overlay
	 *
	 * @return string select box
	 */
	public function user_categoryOverlay() {
		$html = '';

		$orderBy = $GLOBALS['TCA']['sys_language']['ctrl']['sortby'] ?
						$GLOBALS['TCA']['sys_language']['ctrl']['sortby'] :
						$GLOBALS['TYPO3_DB']->stripOrderBy($GLOBALS['TCA']['sys_language']['ctrl']['default_sortby']);

		$languages = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			'*',
			'sys_language',
			'1=1 ' . \TYPO3\CMS\Backend\Utility\BackendUtility::deleteClause('sys_language'),
			'',
			$orderBy
		);

			// if any language is available
		if (count($languages) > 0) {
			$html = '<select name="data[newsoverlay]" id="field_newsoverlay">
						<option value="0">' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', TRUE) . '</option>';

			foreach ($languages as $language) {
				$selected = ($GLOBALS['BE_USER']->uc['newsoverlay'] == $language['uid']) ? ' selected="selected" ' : '';
				$html .= '<option ' . $selected . 'value="' . $language['uid'] . '">' . htmlspecialchars($language['title']) . '</option>';
			}

			$html .= '</select>';
		} else {
			$html .= $GLOBALS['LANG']->sL(
						'LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:usersettings.no-languages-available', TRUE
					);
		}

		return $html;
	}

}