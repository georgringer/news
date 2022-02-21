<?php

namespace GeorgRinger\News\Utility;

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\PhpFrontend;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Class ClassLoader
 */
class ClassLoader implements SingletonInterface
{

    /**
     * @var PhpFrontend
     */
    protected $classCache;

    /** @var ClassCacheManager */
    protected $classCacheManager;

    /** @var bool */
    protected $isValidInstance = false;

    /**
     * ClassLoader constructor.
     *
     * @param PhpFrontend $classCache
     */
    public function __construct(PhpFrontend $classCache = null, ClassCacheManager $classCacheManager = null)
    {
        $versionInformation = GeneralUtility::makeInstance(Typo3Version::class);
        if ($versionInformation->getMajorVersion() === 10) {
            // Use DI
            // something might fail, e.g loading checks in Install Tool
            if ($classCacheManager !== null) {
                $this->classCacheManager = $classCacheManager;
                $this->isValidInstance = true;
            }
        } else {
            $this->classCacheManager = GeneralUtility::makeInstance(ClassCacheManager::class);
            $this->isValidInstance = true;
        }

        if ($this->isValidInstance) {
            if ($classCache === null) {
                $this->classCache = GeneralUtility::makeInstance(CacheManager::class)->getCache('news');
            } else {
                $this->classCache = $classCache;
            }
        }
    }

    /**
     * Register instance of this class as spl autoloader
     *
     * @return void
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
     * @return bool
     */
    public function loadClass($className): bool
    {
        if (!$this->isValidInstance) {
            return false;
        }

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
     *
     * @return null|string
     */
    protected function getExtensionKey($className): ?string
    {
        $extensionKey = null;

        if (strpos($className, '\\') !== false) {
            $namespaceParts = GeneralUtility::trimExplode(
                '\\',
                $className,
                0,
                (substr($className, 0, 9) === 'TYPO3\\CMS' ? 4 : 3)
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
     * @return bool
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
        return $partStr !== '' && strpos($str, $partStr) === 0;
    }

    protected function changeClassName(string $className): string
    {
        return str_replace('\\', '/', str_replace('GeorgRinger\\News\\', '', $className));
    }
}
