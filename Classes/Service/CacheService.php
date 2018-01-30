<?php

namespace GeorgRinger\News\Service;

/*
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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Wrapper for the caching framework.
 */
class CacheService
{
    /**
     * @var \TYPO3\CMS\Core\Cache\Frontend\AbstractFrontend
     */
    protected $cacheInstance;

    /**
     * @var string
     */
    protected $cacheName;

    /**
     * @var \TYPO3\CMS\Core\Cache\CacheManager
     */
    protected $cacheManager;

    /**
     * @param $cacheName string cache name
     *
     * @deprecated since 3.0 will be removed in 4.0
     */
    public function __construct($cacheName)
    {
        GeneralUtility::logDeprecatedFunction();
        $this->cacheName = $cacheName;
        $this->cacheManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager');
    }

    /**
     * Get entry from caching framework.
     *
     * @param string $cacheIdentifier cache identifier
     *
     * @return mixed or NULL if not found
     */
    public function get($cacheIdentifier)
    {
        $entry = $this->cacheManager->getCache($this->cacheName)->get($cacheIdentifier);

        return $entry;
    }

    /**
     * Set an entry to the caching framework.
     *
     * @param string $cacheIdentifier
     * @param string $entry
     * @param array  $tags
     * @param int    $lifetime
     *
     * @return void
     */
    public function set($cacheIdentifier, $entry, array $tags = [], $lifetime = null)
    {
        $this->cacheManager->getCache($this->cacheName)->set($cacheIdentifier, $entry, $tags, $lifetime);
    }

    /**
     * Flush cache.
     *
     * @return void
     */
    public function flush()
    {
        $this->cacheManager->getCache($this->cacheName)->flush();
    }
}
