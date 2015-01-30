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


/**
 * AutoConfiguration-Hook for RealURL
 *
 * @author WOLTER, Christoph <typo3@chrinet.de>
 * @package TYPO3
 * @subpackage tx_news
 */
class RealUrlAutoConfiguration {

	/**
	 * Generates additional RealURL configuration and merges it with provided configuration
	 *
	 * @param       array $params Default configuration
	 * @return      array Updated configuration
	 */
	public function addNewsConfig($params) {

		// Check for proper unique key
		$postVar = (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('tt_news') ? 'tx_news' : 'news');

		return array_merge_recursive($params['config'], array(
				'postVarSets' => array(
					'_DEFAULT' => array(
						$postVar => array(
							array(
								'GETvar' => 'tx_news_pi1[news]',
								'lookUpTable' => array(
									'table' => 'tx_news_domain_model_news',
									'id_field' => 'uid',
									'alias_field' => 'title',
									'useUniqueCache' => 1,
									'useUniqueCache_conf' => array(
										'strtolower' => 1,
										'spaceCharacter' => '-',
									),
								),
							),
						),
					)
				)
			)
		);
	}
}