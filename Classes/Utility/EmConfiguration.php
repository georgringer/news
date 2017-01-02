<?php

namespace GeorgRinger\News\Utility;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Utility class to get the settings from Extension Manager
 *
 */
class EmConfiguration
{

    /**
     * Parses the extension settings.
     *
     * @return \GeorgRinger\News\Domain\Model\Dto\EmConfiguration
     * @throws \Exception If the configuration is invalid.
     */
    public static function getSettings()
    {
        $configuration = self::parseSettings();
        require_once(ExtensionManagementUtility::extPath('news') . 'Classes/Domain/Model/Dto/EmConfiguration.php');
        $settings = new \GeorgRinger\News\Domain\Model\Dto\EmConfiguration($configuration);
        return $settings;
    }

    /**
     * Parse settings and return it as array
     *
     * @return array unserialized extconf settings
     */
    public static function parseSettings()
    {
        $settings = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['news']);

        if (!is_array($settings)) {
            $settings = [];
        }
        return $settings;
    }
}
