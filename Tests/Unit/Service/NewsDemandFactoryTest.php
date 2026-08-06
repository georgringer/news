<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Tests\Unit\Service;

use GeorgRinger\News\Domain\Model\Dto\NewsDemand;
use GeorgRinger\News\Event\CreateDemandObjectFromSettingsEvent;
use GeorgRinger\News\Service\NewsDemandFactory;
use GeorgRinger\News\Tests\Unit\Service\Fixtures\CustomNewsDemandFixture;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Test class for NewsDemandFactory
 */
class NewsDemandFactoryTest extends BaseTestCase
{
    protected function tearDown(): void
    {
        GeneralUtility::purgeInstances();
        parent::tearDown();
    }

    #[DataProvider('settingsAreMappedToTheDemandDataProvider')]
    #[Test]
    public function settingsAreMappedToTheDemand(array $settings, string $getter, mixed $expected): void
    {
        $demand = $this->buildSubject()->create($settings);

        self::assertSame($expected, $demand->{$getter}());
    }

    public static function settingsAreMappedToTheDemandDataProvider(): array
    {
        return [
            'categories are split into an array' => [
                ['categories' => '1, 2 ,3'], 'getCategories', ['1', '2', '3'],
            ],
            'empty categories result in an empty array' => [
                [], 'getCategories', [],
            ],
            'category conjunction is taken over' => [
                ['categoryConjunction' => 'or'], 'getCategoryConjunction', 'or',
            ],
            'sub categories are cast to bool' => [
                ['includeSubCategories' => '1'], 'getIncludeSubCategories', true,
            ],
            'tags are taken over' => [
                ['tags' => '4,5'], 'getTags', '4,5',
            ],
            'top news restriction is cast to int' => [
                ['topNewsRestriction' => '2'], 'getTopNewsRestriction', 2,
            ],
            'archive restriction is taken over' => [
                ['archiveRestriction' => 'active'], 'getArchiveRestriction', 'active',
            ],
            'already displayed news flag is cast to bool' => [
                ['excludeAlreadyDisplayedNews' => '1'], 'getExcludeAlreadyDisplayedNews', true,
            ],
            'hide id list is taken over' => [
                ['hideIdList' => '7,8'], 'getHideIdList', '7,8',
            ],
            'order is combined out of field and direction' => [
                ['orderBy' => 'datetime', 'orderDirection' => 'desc'], 'getOrder', 'datetime desc',
            ],
            'order stays empty without an order field' => [
                ['orderBy' => '', 'orderDirection' => 'desc'], 'getOrder', '',
            ],
            'allowed order fields are taken over' => [
                ['orderByAllowed' => 'title,datetime'], 'getOrderByAllowed', 'title,datetime',
            ],
            'top news first is cast to bool' => [
                ['topNewsFirst' => '1'], 'getTopNewsFirst', true,
            ],
            'limit is cast to int' => [
                ['limit' => '10'], 'getLimit', 10,
            ],
            'offset is cast to int' => [
                ['offset' => '5'], 'getOffset', 5,
            ],
            'search fields are read from the nested search settings' => [
                ['search' => ['fields' => 'title,teaser']], 'getSearchFields', 'title,teaser',
            ],
            'date field is taken over' => [
                ['dateField' => 'datetime'], 'getDateField', 'datetime',
            ],
            'month is cast to int' => [
                ['month' => '3'], 'getMonth', 3,
            ],
            'year is cast to int' => [
                ['year' => '2026'], 'getYear', 2026,
            ],
        ];
    }

    #[Test]
    public function storagePageIsResolvedRecursively(): void
    {
        $pageRepository = $this->createMock(PageRepository::class);
        $pageRepository->expects(self::once())
            ->method('getPageIdsRecursive')
            ->with([1, 2], 3)
            ->willReturn([1, 2, 10, 11]);

        $demand = $this->buildSubject($pageRepository)->create([
            'startingpoint' => '1,2',
            'recursive' => '3',
        ]);

        self::assertSame('1,2,10,11', $demand->getStoragePage());
    }

    #[Test]
    public function demandClassFromSettingsIsUsed(): void
    {
        $demand = $this->buildSubject()->create(['demandClass' => CustomNewsDemandFixture::class]);

        self::assertInstanceOf(CustomNewsDemandFixture::class, $demand);
    }

    #[Test]
    public function demandClassArgumentIsUsedIfSettingsDoNotDefineOne(): void
    {
        $demand = $this->buildSubject()->create([], CustomNewsDemandFixture::class);

        self::assertInstanceOf(CustomNewsDemandFixture::class, $demand);
    }

    #[Test]
    public function demandClassFromSettingsTakesPrecedenceOverTheArgument(): void
    {
        $demand = $this->buildSubject()->create(
            ['demandClass' => CustomNewsDemandFixture::class],
            NewsDemand::class
        );

        self::assertInstanceOf(CustomNewsDemandFixture::class, $demand);
    }

    #[Test]
    public function exceptionIsThrownForADemandClassNotExtendingNewsDemand(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionCode(1423157953);

        $this->buildSubject()->create(['demandClass' => \stdClass::class]);
    }

    #[Test]
    public function eventIsDispatchedWithTheDemandTheSettingsAndTheClass(): void
    {
        $settings = ['limit' => '10', 'demandClass' => CustomNewsDemandFixture::class];
        $dispatchedEvent = null;

        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcher->expects(self::once())
            ->method('dispatch')
            ->willReturnCallback(static function (CreateDemandObjectFromSettingsEvent $event) use (&$dispatchedEvent) {
                $dispatchedEvent = $event;
                return $event;
            });

        $this->buildSubject(null, $eventDispatcher)->create($settings);

        self::assertInstanceOf(CustomNewsDemandFixture::class, $dispatchedEvent->getDemand());
        self::assertSame($settings, $dispatchedEvent->getSettings());
        self::assertSame(CustomNewsDemandFixture::class, $dispatchedEvent->getClass());
    }

    #[Test]
    public function demandReplacedByAnEventListenerIsReturned(): void
    {
        $replacement = new CustomNewsDemandFixture();

        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $eventDispatcher->method('dispatch')
            ->willReturnCallback(static function (CreateDemandObjectFromSettingsEvent $event) use ($replacement) {
                $event->setDemand($replacement);
                return $event;
            });

        $demand = $this->buildSubject(null, $eventDispatcher)->create([]);

        self::assertSame($replacement, $demand);
    }

    protected function buildSubject(
        ?PageRepository $pageRepository = null,
        ?EventDispatcherInterface $eventDispatcher = null
    ): NewsDemandFactory {
        if ($pageRepository === null) {
            $pageRepository = $this->createMock(PageRepository::class);
            $pageRepository->method('getPageIdsRecursive')->willReturn([]);
        }
        GeneralUtility::addInstance(PageRepository::class, $pageRepository);

        return new NewsDemandFactory($eventDispatcher ?? $this->createMock(EventDispatcherInterface::class));
    }
}
