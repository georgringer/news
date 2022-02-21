<?php

namespace GeorgRinger\News\Service;

use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
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
 */
class SettingsService
{

    /**
     * @var mixed
     */
    protected $settings;

    /**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     */
    protected $configurationManager;

    /**
     * Injects the Configuration Manager and loads the settings
     *
     * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager An instance of the Configuration Manager
     *
     * @return void
     */
    public function injectConfigurationManager(
        ConfigurationManagerInterface $configurationManager
    ): void {
        $this->configurationManager = $configurationManager;
    }

    /**
     * Returns all settings.
     *
     * @return array
     */
    public function getSettings(): array
    {
        if ($this->settings === null) {
            $this->settings = $this->configurationManager->getConfiguration(
                ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
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
        return ObjectAccess::getPropertyPath($this->getSettings(), $path);
    }
}
