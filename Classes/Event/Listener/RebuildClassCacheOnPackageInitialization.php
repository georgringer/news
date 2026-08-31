<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Event\Listener;

use GeorgRinger\News\Utility\ClassCacheManager;
use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Package\Event\PackageInitializationEvent;

/**
 * An extension being set up may add partials to
 * $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['classes'], which a class cache
 * file written before that install does not contain yet. ClassLoader only
 * rebuilds a cache entry that is missing, not one that is outdated, so the
 * rebuild is triggered here instead.
 */
#[AsEventListener(
    identifier: 'ext-news/rebuild-class-cache-on-package-initialization',
)]
class RebuildClassCacheOnPackageInitialization
{
    private bool $classCacheRebuilt = false;

    public function __construct(
        private readonly ClassCacheManager $classCacheManager
    ) {}

    public function __invoke(PackageInitializationEvent $event): void
    {
        // The event is dispatched per package, while every ext_localconf.php has
        // already been loaded when the first one arrives. One rebuild therefore
        // covers all packages of the same run.
        if ($this->classCacheRebuilt) {
            return;
        }
        $this->classCacheRebuilt = true;

        $this->classCacheManager->reBuild();
    }
}
