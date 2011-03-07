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
 * Hook into tceforms which is used to show different
 * rendering of media element
 *
 * @package TYPO3
 * @subpackage tx_news2
 */
class tx_News2_Hooks_Tceforms {

	/**
	 * Change field configuration depending on the type field
	 *
	 * @param type $table current table
	 * @param type $field current field
	 * @param array $row record row
	 * @param array $configuration configuration
	 * @return void
	 */
	public function getSingleField_beforeRender($table, $field, array $row, array &$configuration) {
		if ($table === 'tx_news2_domain_model_media' && $field === 'content') {

			$type = $row['type'];
			if (!isset($type) || !isset($configuration['fieldConf']['variants'][$type])) {
				$type = (int)$configuration['fieldConf']['variants']['default'];
			}
			$configuration['fieldConf'] = $configuration['fieldConf']['variants'][$type];
			$configuration['label'] = $GLOBALS['LANG']->sL($configuration['fieldConf']['label'], TRUE);
			$configuration['fieldConf']['config']['form_type'] = $configuration['fieldConf']['config']['type'];
		}
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/news2/Classes/Hooks/Tceforms.php']) {
	require_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/news2/Classes/Hooks/Tceforms.php']);
}

?>