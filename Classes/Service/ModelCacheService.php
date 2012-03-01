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
class Tx_News_Service_ModelCacheService implements t3lib_Singleton{

		/**
	 * @var t3lib_cache_frontend_PhpFrontend
	 */
	protected $templateCache;

		/**
	 * @param t3lib_cache_frontend_PhpFrontend $templateCache
	 * @return void
	 */
	public function setTemplateCache(t3lib_cache_frontend_PhpFrontend $templateCache) {
		$this->templateCache = $templateCache;
	}

	/**
	 * @param string $identifier
	 * @return Tx_Fluid_Core_Parser_ParsedTemplateInterface
	 */
	public function get($identifier) {
		return $this->templateCache->get($identifier);
	}

	/**
	 * @param string $identifier
	 * @return boolean
	 */
	public function has($identifier) {
		return $this->templateCache->has($identifier);
	}

	/**
	 * @param string $identifier
	 * @param Tx_Fluid_Core_Parser_ParsingState $parsingState
	 * @return void
	 */
	public function store($identifier,$templateCode) {
		$this->templateCache->set($identifier, $templateCode);
	}

	public function requireOnce($identifier) {
		$this->templateCache->requireOnce($identifier);
	}



}

?>