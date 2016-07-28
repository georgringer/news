<?php

namespace GeorgRinger\News\Service;

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
 * Provide a way to get the configuration just everywhere
 *
 * Example
 * $pluginSettingsService =
 * $this->objectManager->get('GeorgRinger\\News\\Service\\SettingsService');
 * \TYPO3\CMS\Core\Utility\GeneralUtility::print_array($pluginSettingsService->getSettings());
 *
 * If objectManager is not available:
 * http://forge.typo3.org/projects/typo3v4-mvc/wiki/
 * Dependency_Injection_%28DI%29#Creating-Prototype-Objects-through-the-Object-Manager
 *
 */
class SettingsService
{

    /**
     * @var mixed
     */
    protected $settings = null;

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
    public function injectConfigurationManager(
        \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
    ) {
        $this->configurationManager = $configurationManager;
    }

    /**
     * Returns all settings.
     *
     * @return array
     */
    public function getSettings()
    {
        if ($this->settings === null) {
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
    public function getByPath($path)
    {
        return \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getPropertyPath($this->getSettings(), $path);
    }
}
