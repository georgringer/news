<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Unit\Event\Listener;

use GeorgRinger\News\Event\Listener\RebuildClassCacheOnPackageInitialization;
use GeorgRinger\News\Utility\ClassCacheManager;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Package\Event\PackageInitializationEvent;
use TYPO3\CMS\Core\Package\PackageInterface;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Test class for RebuildClassCacheOnPackageInitialization
 */
class RebuildClassCacheOnPackageInitializationTest extends BaseTestCase
{
    #[Test]
    public function classCacheIsRebuiltOnPackageInitialization(): void
    {
        $classCacheManager = $this->createMock(ClassCacheManager::class);
        $classCacheManager->expects(self::once())->method('reBuild');

        $listener = new RebuildClassCacheOnPackageInitialization($classCacheManager);
        $listener($this->buildEvent('news_seo'));
    }

    #[Test]
    public function classCacheIsRebuiltOnceOnlyWhenSeveralPackagesAreInitialized(): void
    {
        $classCacheManager = $this->createMock(ClassCacheManager::class);
        $classCacheManager->expects(self::once())->method('reBuild');

        $listener = new RebuildClassCacheOnPackageInitialization($classCacheManager);
        $listener($this->buildEvent('news_seo'));
        $listener($this->buildEvent('eventnews'));
        $listener($this->buildEvent('unrelated_extension'));
    }

    private function buildEvent(string $extensionKey): PackageInitializationEvent
    {
        // Only the first two arguments are identical in TYPO3 13.4 and 14, the
        // third one differs, so it must not be passed here.
        return new PackageInitializationEvent($extensionKey, $this->createMock(PackageInterface::class));
    }
}
