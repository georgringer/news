<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Service;

use GeorgRinger\News\Domain\Model\Dto\NewsDemand;
use GeorgRinger\News\Event\CreateDemandObjectFromSettingsEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Creates demand objects out of a settings array, e.g. the TypoScript settings
 * of a news plugin.
 *
 * Use this service whenever news records need to be fetched outside of the
 * NewsController, for example in a custom controller, a ViewHelper, a
 * DataProcessor or an API endpoint.
 */
class NewsDemandFactory
{
    public function __construct(
        protected readonly EventDispatcherInterface $eventDispatcher
    ) {}

    /**
     * Create the demand object which defines which records will get shown
     *
     * A non-empty $settings['demandClass'] takes precedence over $class, so the inferred return
     * type only holds as long as a caller does not use both at once.
     *
     * @template T of NewsDemand
     * @param array $settings settings of the plugin, e.g. plugin.tx_news.settings
     * @param class-string<T> $class optional class which must be an instance of \GeorgRinger\News\Domain\Model\Dto\NewsDemand
     * @return T
     */
    public function create(array $settings, string $class = NewsDemand::class): NewsDemand
    {
        $class = isset($settings['demandClass']) && !empty($settings['demandClass']) ? $settings['demandClass'] : $class;

        if (!is_a($class, NewsDemand::class, true)) {
            throw new \UnexpectedValueException(
                sprintf(
                    'The demand object must be an instance of %s, but %s given!',
                    NewsDemand::class,
                    $class
                ),
                1423157953
            );
        }
        /* @var $demand NewsDemand */
        $demand = GeneralUtility::makeInstance($class, $settings);

        $demand->setCategories(GeneralUtility::trimExplode(',', $settings['categories'] ?? '', true));
        $demand->setCategoryConjunction((string)($settings['categoryConjunction'] ?? ''));
        $demand->setIncludeSubCategories((bool)($settings['includeSubCategories'] ?? false));
        $demand->setTags((string)($settings['tags'] ?? ''));

        $demand->setTopNewsRestriction((int)($settings['topNewsRestriction'] ?? 0));
        $demand->setTimeRestriction($settings['timeRestriction'] ?? '');
        $demand->setTimeRestrictionHigh($settings['timeRestrictionHigh'] ?? '');
        $demand->setArchiveRestriction((string)($settings['archiveRestriction'] ?? ''));
        $demand->setExcludeAlreadyDisplayedNews((bool)($settings['excludeAlreadyDisplayedNews'] ?? false));
        $demand->setHideIdList((string)($settings['hideIdList'] ?? ''));

        if ($settings['orderBy'] ?? '') {
            $demand->setOrder($settings['orderBy'] . ' ' . $settings['orderDirection']);
        }
        $demand->setOrderByAllowed((string)($settings['orderByAllowed'] ?? ''));

        $demand->setTopNewsFirst((bool)($settings['topNewsFirst'] ?? false));

        $demand->setLimit((int)($settings['limit'] ?? 0));
        $demand->setOffset((int)($settings['offset'] ?? 0));

        $demand->setSearchFields((string)($settings['search']['fields'] ?? ''));
        $demand->setDateField((string)($settings['dateField'] ?? ''));
        $demand->setMonth((int)($settings['month'] ?? 0));
        $demand->setYear((int)($settings['year'] ?? 0));

        $pageRepository = GeneralUtility::makeInstance(PageRepository::class);
        $idList = $pageRepository->getPageIdsRecursive(GeneralUtility::intExplode(',', (string)($settings['startingpoint'] ?? '')), (int)($settings['recursive'] ?? 0));
        $demand->setStoragePage(implode(',', $idList));

        $event = new CreateDemandObjectFromSettingsEvent($demand, $settings, $class);
        $this->eventDispatcher->dispatch($event);

        return $event->getDemand();
    }
}
