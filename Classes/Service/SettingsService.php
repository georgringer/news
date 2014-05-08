<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Sebastian Schreiber <me@schreibersebastian.de >
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
 * Provide a way to get the configuration just everywhere
 *
 * Example
 * $pluginSettingsService =
 * $this->objectManager->get('Tx_News_Service_SettingsService');
 * \TYPO3\CMS\Core\Utility\GeneralUtility::print_array($pluginSettingsService->getSettings());
 *
 * If objectManager is not available:
 * http://forge.typo3.org/projects/typo3v4-mvc/wiki/
 * Dependency_Injection_%28DI%29#Creating-Prototype-Objects-through-the-Object-Manager
 *
 * @package TYPO3
 * @subpackage tx_news
 */
class Tx_News_Service_SettingsService {

	/**
	 * @var mixed
	 */
	protected $settings = NULL;

	/**
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 */
	protected $configurationManager;

	/**
	 * Injects the Configuration Manager and loads the settings
	 *
	 * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager An instance of the Configuration Manager
	 * @return void
	 */
	public function injectConfigurationManager(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager) {
		$this->configurationManager = $configurationManager;
	}

	/**
	 * Returns all settings.
	 *
	 * @return array
	 */
	public function getSettings() {
		if ($this->settings === NULL) {
			$this->settings = $this->configurationManager->getConfiguration(
				\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
					'News',
					'Pi1'
			);
		}
		return $this->settings;
	}

	/**
	 * Returns the settings at path $path, which is separated by ".",
	 * e.g. "pages.uid".
	 * "pages.uid" would return $this->settings['pages']['uid'].
	 *
	 * If the path is invalid or no entry is found, false is returned.
	 *
	 * @param string $path
	 * @return mixed
	 */
	public function getByPath($path) {
		return \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getPropertyPath($this->getSettings(), $path);
	}

}
