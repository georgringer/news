<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Unit\Utility;

use GeorgRinger\News\Domain\Model\News;
use GeorgRinger\News\Utility\ClassCacheManager;
use GeorgRinger\News\Utility\ClassLoader;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Cache\Frontend\PhpFrontend;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class ClassLoaderTest extends UnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['classes']['Domain/Model/News'] = ['news_extended'];
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['classes']);
        parent::tearDown();
    }

    #[Test]
    public function loadClassRequiresExistingCacheEntryWithoutRebuild(): void
    {
        $classCacheManager = $this->createMock(ClassCacheManager::class);
        $classCacheManager->expects(self::never())->method('reBuild');
        GeneralUtility::addInstance(ClassCacheManager::class, $classCacheManager);

        $classCache = $this->createMock(PhpFrontend::class);
        $classCache->method('has')->with('tx_news_domain_model_news')->willReturn(true);
        $classCache->expects(self::once())->method('requireOnce')->with('tx_news_domain_model_news')->willReturn(true);

        $subject = new ClassLoader($classCache);

        self::assertTrue($subject->loadClass(News::class));
    }

    #[Test]
    public function loadClassRebuildsCacheEntryRemovedByConcurrentFlush(): void
    {
        $classCacheManager = $this->createMock(ClassCacheManager::class);
        $classCacheManager->expects(self::once())->method('reBuild');
        GeneralUtility::addInstance(ClassCacheManager::class, $classCacheManager);

        $classCache = $this->createMock(PhpFrontend::class);
        $classCache->method('has')->with('tx_news_domain_model_news')->willReturn(true);
        $classCache->expects(self::exactly(2))
            ->method('requireOnce')
            ->with('tx_news_domain_model_news')
            ->willReturnOnConsecutiveCalls(false, true);

        $subject = new ClassLoader($classCache);

        self::assertTrue($subject->loadClass(News::class));
    }
}
