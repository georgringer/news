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
 * Userfunc to render alternative label for media elements
 *
 * @package TYPO3
 * @subpackage tx_news2
 */
class tx_News2_Hooks_ItemsProcFunc {

	/**
	 * Set DAM as an additional option. Changes are done in $config
	 *
	 * @param  $config configuration of TCA field
	 * @param t3lib_TCEforms $parentObject parent object
	 * @return void
	 */
	public function user_MediaType(array &$config, t3lib_TCEforms $parentObject) {
			// if dam is loaded
		if (t3lib_extMgm::isLoaded('dam')) {
			$ll = 'LLL:EXT:news2/Resources/Private/Language/locallang_db.xml:';

				// additional entry
			$damEntry = array(
				$GLOBALS['LANG']->sL($ll . 'tx_news2_domain_model_media.type.I.3'),
				'3',
				t3lib_extMgm::extRelPath('news2').'Resources/Public/Icons/media_type_dam.gif'
			);

				// add entry to type list
			array_push($config['items'], $damEntry);
		}
	}

	/**
	 * Itemsproc function to extend the selection of templateLayouts in the plugin
	 *
	 * @param array &$config configuration array
	 * @param t3lib_TCEforms $parentObject parent object
	 * @return void
	 */
	public function user_templateLayout(array &$config, t3lib_TCEforms $parentObject) {
			// check if the layouts are extended
		if (isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['news2']['templateLayouts'])
				&& is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['news2']['templateLayouts'])) {

				// add every item
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXT']['news2']['templateLayouts'] as $layouts) {
				$additionalLayout = array(
					$GLOBALS['LANG']->sL($layouts[0], TRUE),
					$layouts[1]
				);
				array_push($config['items'], $additionalLayout);
			}
		}
	}

	/**
	 * Modifies the selectbox of orderby-options as a categorymenu
	 * needs different ones then a news action
	 *
	 * @param array &$config configuration array
	 * @param t3lib_TCEforms $parentObject parent object
	 * @return void
	 */
	public function user_orderBy(array &$config, t3lib_TCEforms $parentObject) {
		$defaultItems = 'tstmp,datetime,crdate,title';
		$newItems = '';

			// check if the record has been saved once
		if (is_array($config['row']) && !empty($config['row']['pi_flexform'])) {
			$flexformConfig = t3lib_div::xml2array($config['row']['pi_flexform']);

				// check if there is a flexform configuration
			if (isset($flexformConfig['data']['sDEF']['lDEF']['switchableControllerActions']['vDEF'])) {
				$selectedActionList = $flexformConfig['data']['sDEF']['lDEF']['switchableControllerActions']['vDEF'];

					// check for selected action
				if (t3lib_div::isFirstPartOfStr($selectedActionList, 'Category')) {
					$newItems = $GLOBALS['TYPO3_CONF_VARS']['EXT']['news2']['orderByCategory'];
				} else {
					$newItems = $GLOBALS['TYPO3_CONF_VARS']['EXT']['news2']['orderByNews'];
				}
			}
		}

			// if a override configuration is found
		if (!empty($newItems)) {
				// remove default configuration
			$config['items'] = array();
				// empty default line
			array_push($config['items'], array('', ''));

			$newItemArray = t3lib_div::trimExplode(',', $newItems, TRUE);
			$languageKey = 'LLL:EXT:news2/Resources/Private/Language/locallang_be.xml:flexforms_general.orderBy.';
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
	 * Modifies the selectbox of available actions
	 *
	 * @param array &$config
	 * @param t3lib_TCEforms $parentObject
	 * @return void
	 */
	public function user_switchableControllerActions(array &$config, t3lib_TCEforms $parentObject) {
			// remove the detail action from the listAction
		if (isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['news2']['switchableControllerActions']['listActionOnly'])) {
			$config['items'][0][1] = 'News->list;';
		}

			// add additional actions
		if (isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['news2']['switchableControllerActions']['newItems'])
				&& is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['news2']['switchableControllerActions']['newItems'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXT']['news2']['switchableControllerActions']['newItems'] as $key => $label) {
				array_push($config['items'], array($GLOBALS['LANG']->sL($label), $key, ''));
			}
		}
	}

	/**
	 * Generate a select box of languages to choose an overlay
	 *
	 * @param array $config
	 * @param SC_mod_user_setup_index $parentObject
	 * @return string select box
	 */
	public function user_categoryOverlay(array $config, SC_mod_user_setup_index $parentObject) {
		$html = '';

		$orderBy = $GLOBALS['TCA']['sys_language']['ctrl']['sortby'] ? $GLOBALS['TCA']['sys_language']['ctrl']['sortby'] : $GLOBALS['TYPO3_DB']->stripOrderBy($GLOBALS['TCA']['sys_language']['ctrl']['default_sortby']);
		$languages = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			'*',
			'sys_language',
			'1=1 ' . t3lib_BEfunc::deleteClause('sys_language'),
			'',
			$orderBy
		);

			// if any language is available
		if (count($languages) > 0) {
			$html = '<select name="data[news2overlay]">
						<option value="0">' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_general.xml:LGL.default_value', TRUE) . '</option>';

			foreach ($languages as $language) {
				$selected = ($GLOBALS['BE_USER']->uc['news2overlay'] == $language['uid']) ? ' selected="selected" ' : '';
				$html .= '<option ' . $selected . 'value="' . $language['uid'] . '">' . htmlspecialchars($language['title']) . '</option>';
			}

			$html .= '</select>';
		} else {
			$html .= $GLOBALS['LANG']->sL(
						'LLL:EXT:news2/Resources/Private/Language/locallang_be.xml:usersettings.no-languages-available', TRUE
					);
		}

		return $html;
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/news2/Classes/Hooks/ItemsProcFunc.php']) {
	include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/news2/Classes/Hooks/ItemsProcFunc.php']);
}

?>