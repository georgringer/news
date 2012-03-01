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
 * Service for category related stuff
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_Cache_ClassCacheService implements t3lib_Singleton{

	/**
	 * @var t3lib_cache_frontend_PhpFrontend
	 */
	protected $cache;

	/**
	 * @param t3lib_cache_frontend_PhpFrontend $cache
	 * @return void
	 */
	public function setCache(t3lib_cache_frontend_PhpFrontend $cache) {
		$this->cache = $cache;
	}

	/**
	 * @param string $identifier
	 * @return cache entry
	 */
	public function get($identifier) {
		return $this->cache->get($identifier);
	}

	/**
	 * @param string $identifier
	 * @return boolean
	 */
	public function has($identifier) {
		return $this->cache->has($identifier);
	}

	/**
	 * @param string $identifier
	 * @param string $code
	 * @return void
	 */
	public function store($identifier, $code) {
		$this->cache->set($identifier, $code);
	}

	public function requireOnce($identifier) {
		$this->cache->requireOnce($identifier);
	}

}

?>