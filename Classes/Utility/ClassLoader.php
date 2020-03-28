<?php

namespace GeorgRinger\News\Utility;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\PhpFrontend;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class ClassLoader
 */
class ClassLoader implements \TYPO3\CMS\Core\SingletonInterface
{

    /**
     * @var PhpFrontend
     */
    protected $classCache;

    /** @var ClassCacheManager */
    protected $classCacheManager;

    /**
     * ClassLoader constructor.
     *
     * @param PhpFrontend $classCache
     */
    public function __construct(PhpFrontend $classCache = null, ClassCacheManager $classCacheManager = null)
    {
        if ($classCache === null) {
            $this->classCache = GeneralUtility::makeInstance(CacheManager::class)->getCache('news');
        } else {
            $this->classCache = $classCache;
        }
        $this->classCacheManager = $classCacheManager ?? GeneralUtility::makeInstance(ClassCacheManager::class);
    }

    /**
     * Register instance of this class as spl autoloader
     *
     */
    public static function registerAutoloader()
    {
        spl_autoload_register([GeneralUtility::makeInstance(self::class), 'loadClass'], true, true);
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

        $classCache = $this->classCache;
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
        if (GeneralUtility::isFirstPartOfStr($className, 'GeorgRinger\\News\\Domain\\') || GeneralUtility::isFirstPartOfStr($className, 'GeorgRinger\\News\\Controller\\')) {
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
