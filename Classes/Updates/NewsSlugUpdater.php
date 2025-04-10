<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Updates;

use GeorgRinger\News\Service\SlugService;
use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

/**
 * Migrate empty slugs
 */
#[UpgradeWizard('newsSlug')]
class NewsSlugUpdater implements UpgradeWizardInterface
{
    public const TABLE = 'tx_news_domain_model_news';

    protected SlugService $slugService;

    public function __construct(
        SlugService $slugService
    ) {
        $this->slugService = $slugService;
    }

    public function executeUpdate(): bool
    {
        $this->slugService->performUpdates();
        return true;
    }

    public function updateNecessary(): bool
    {
        $elementCount = $this->slugService->countOfSlugUpdates();

        return $elementCount > 0;
    }

    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class,
        ];
    }

    public function getTitle(): string
    {
        return 'Updates slug field "path_segment" of EXT:news records';
    }

    /**
     * @return string Longer description of this updater
     */
    public function getDescription(): string
    {
        return 'Fills empty slug field "path_segment" of EXT:news records with urlized title.';
    }
}
