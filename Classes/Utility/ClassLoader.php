<?php
namespace GeorgRinger\News\Utility;

/**
 * (c) 2014 Sebastian Fischer <typo3@evoweb.de>
 *
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
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class ClassLoader
 */
class ClassLoader implements \TYPO3\CMS\Core\SingletonInterface
{

    /**
     * @var \TYPO3\CMS\Core\Cache\Frontend\PhpFrontend
     */
    protected $cacheInstance;

    /**
     * Register instance of this class as spl autoloader
     *
     * @return void
     */
    public static function registerAutoloader()
    {
        spl_autoload_register([new self(), 'loadClass'], true, true);
    }

    /**
     * Initialize cache
     *
     * @return \TYPO3\CMS\Core\Cache\Frontend\PhpFrontend
     */
    public function initializeCache()
    {
        if (is_null($this->cacheInstance)) {
            $cacheManager = GeneralUtility::makeInstance(CacheManager::class);
            $this->cacheInstance = $cacheManager->getCache('news');
        }
        return $this->cacheInstance;
    }

    /**
     * Loads php files containing classes or interfaces part of the
     * classes directory of an extension.
     *
     * @param string $className Name of the class/interface to load
     * @return bool
     */
    public function loadClass($className)
    {
        $className = ltrim($className, '\\');

        if (!$this->isValidClassName($className)) {
            return false;
        }

        $cacheEntryIdentifier = 'tx_news_' . strtolower(str_replace('/', '_', $this->changeClassName($className)));

        $classCache = $this->initializeCache();
        if (!empty($cacheEntryIdentifier) && !$classCache->has($cacheEntryIdentifier)) {
            require_once(ExtensionManagementUtility::extPath('news') . 'Classes/Utility/ClassCacheManager.php');

            $classCacheManager = GeneralUtility::makeInstance(ClassCacheManager::class);
            $classCacheManager->reBuild();
        }

        if (!empty($cacheEntryIdentifier) && $classCache->has($cacheEntryIdentifier)) {
            $classCache->requireOnce($cacheEntryIdentifier);
        }

        return true;
    }

    /**
     * Get extension key from namespaced classname
     *
     * @param string $className
     * @return string
     */
    protected function getExtensionKey($className)
    {
        $extensionKey = null;

        if (strpos($className, '\\') !== false) {
            $namespaceParts = GeneralUtility::trimExplode('\\', $className, 0,
                (substr($className, 0, 9) === 'TYPO3\\CMS' ? 4 : 3));
            array_pop($namespaceParts);
            $extensionKey = GeneralUtility::camelCaseToLowerCaseUnderscored(array_pop($namespaceParts));
        }

        return $extensionKey;
    }

    /**
     * Find out if a class name is valid
     *
     * @param string $className
     * @return bool
     */
    protected function isValidClassName($className)
    {
        if (GeneralUtility::isFirstPartOfStr($className, 'GeorgRinger\\News\\')) {
            $modifiedClassName = $this->changeClassName($className);
            if (isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['classes'][$modifiedClassName])) {
                return true;
            }
        }
        return false;
    }

    protected function changeClassName($className)
    {
        return str_replace('\\', '/', str_replace('GeorgRinger\\News\\', '', $className));
    }
}
