<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Utility;

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\PhpFrontend;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class ClassLoader
 */
class ClassLoader implements SingletonInterface
{
    protected PhpFrontend $classCache;
    protected object $classCacheManager;

    public function __construct(?PhpFrontend $classCache = null)
    {
        $this->classCacheManager = GeneralUtility::makeInstance(ClassCacheManager::class);

        if ($classCache === null) {
            $this->classCache = GeneralUtility::makeInstance(CacheManager::class)->getCache('news');
        } else {
            $this->classCache = $classCache;
        }
    }

    /**
     * Register instance of this class as spl autoloader
     */
    public static function registerAutoloader(): void
    {
        spl_autoload_register([GeneralUtility::makeInstance(self::class), 'loadClass'], true, true);
    }

    /**
     * Loads php files containing classes or interfaces part of the
     * classes directory of an extension.
     *
     * @param string $className Name of the class/interface to load
     */
    public function loadClass($className): bool
    {
        $className = ltrim($className, '\\');

        if (!$this->isValidClassName($className)) {
            return false;
        }
        $cacheEntryIdentifier = 'tx_news_' . strtolower(str_replace('/', '_', $this->changeClassName($className)));

        if (!$this->classCache->has($cacheEntryIdentifier)) {
            $this->classCacheManager->reBuild();
        }

        $this->classCache->requireOnce($cacheEntryIdentifier);
        return true;
    }

    /**
     * Get extension key from namespaced classname
     *
     * @param string $className
     */
    protected function getExtensionKey($className): ?string
    {
        $extensionKey = null;

        if (str_contains($className, '\\')) {
            $namespaceParts = GeneralUtility::trimExplode(
                '\\',
                $className,
                0,
                str_starts_with($className, 'TYPO3\\CMS') ? 4 : 3
            );
            array_pop($namespaceParts);
            $extensionKey = GeneralUtility::camelCaseToLowerCaseUnderscored(array_pop($namespaceParts));
        }

        return $extensionKey;
    }

    /**
     * Find out if a class name is valid
     *
     * @param string $className
     */
    protected function isValidClassName($className): bool
    {
        if ($this->isFirstPartOfStr($className, 'GeorgRinger\\News\\Domain\\') || $this->isFirstPartOfStr($className, 'GeorgRinger\\News\\Controller\\')) {
            $modifiedClassName = $this->changeClassName($className);
            if (isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['classes'][$modifiedClassName])) {
                return true;
            }
        }
        return false;
    }

    protected function isFirstPartOfStr(string $str, string $partStr): bool
    {
        return $partStr !== '' && str_starts_with($str, $partStr);
    }

    protected function changeClassName(string $className): string
    {
        return str_replace('\\', '/', str_replace('GeorgRinger\\News\\', '', $className));
    }
}
