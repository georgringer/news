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
 * @version $Id$
 */
class tx_news2_itemsProcFunc {

	/**
	 * Set DAM as an additional option
	 * 
	 * @param  $config configuration of TCA field
	 * @param t3lib_TCEforms $parentObject
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

			foreach($languages as $language) {
				$selected = ($GLOBALS['BE_USER']->uc['news2overlay'] == $language['uid']) ? ' selected="selected" ' : '';
				$html .= '<option ' . $selected . 'value="' . $language['uid'] . '">' . htmlspecialchars($language['title']) . '</option>';
			}

			$html .= '</select>';
		} else {
			$html .= $GLOBALS['LANG']->sL('LLL:EXT:news2/Resources/Private/Language/locallang.xml:usersettings.no-languages-available', TRUE);
		}

		return $html;
	}


}

?>