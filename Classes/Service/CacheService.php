<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Georg Ringer <typo3@ringerge.org>
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
 * Wrapper for the caching framework
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_Service_CacheService {

	/**
	 * @var t3lib_cache_frontend_AbstractFrontend
	 */
	protected $cacheInstance;

	/**
	 * @var string
	 */
	protected $cacheName;

	/**
	 * @param $cacheName cache name
	 */
	public function __construct($cacheName) {
		$this->cacheName = $cacheName;

		$this->initializeCache();
	}

	/**
	 * Get entry from caching framework
	 *
	 * @param string $cacheIdentifier cache identifier
	 * @return entry or NULL if not found
	 */
	public function get($cacheIdentifier) {
		$entry = $GLOBALS['typo3CacheManager']->getCache($this->cacheName)->get($cacheIdentifier);
		return $entry;
	}

	/**
	 * Set an entry to the caching framework
	 *
	 * @param string $cacheIdentifier
	 * @param string $entry
	 * @param array $tags
	 * @param integer $lifetime
	 * @return void
	 */
	public function set($cacheIdentifier, $entry, array $tags = array(), $lifetime = NULL) {
		$GLOBALS['typo3CacheManager']->getCache($this->cacheName)->set($cacheIdentifier, $entry, $tags, $lifetime);
	}

	/**
	 * Flush cache
	 *
	 * @return void
	 */
	public function flush() {
		$GLOBALS['typo3CacheManager']->getCache($this->cacheName)->flush();
	}

	/**
	 * Initialize cache instance to be ready to use
	 *
	 * @return void
	 */
	protected function initializeCache() {
		t3lib_cache::initializeCachingFramework();
		try {
			$this->cacheInstance = $GLOBALS['typo3CacheManager']->getCache($this->cacheName);
		} catch (t3lib_cache_exception_NoSuchCache $e) {
			$this->cacheInstance = $GLOBALS['typo3CacheFactory']->create(
							$this->cacheName,
							$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$this->cacheName]['frontend'],
							$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$this->cacheName]['backend'],
							(array)$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$this->cacheName]['options']
			);
		}
	}

}

?>