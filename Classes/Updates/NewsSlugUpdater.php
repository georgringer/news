<?php
declare(strict_types=1);

namespace GeorgRinger\News\Updates;

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

use GeorgRinger\News\Service\SlugService;
use GeorgRinger\News\Service\Transliterator\Transliterator;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\AbstractUpdate;

/**
 * Migrate empty slugs
 */
class NewsSlugUpdater extends AbstractUpdate
{

    const TABLE = 'tx_news_domain_model_news';

    /** @var SlugService */
    protected $slugService;

    public function __construct()
    {
        $this->slugService = GeneralUtility::makeInstance(SlugService::class);
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return 'Updates slug field "path_segment" of EXT:news records';
    }


    /**
     * Checks if an update is needed
     *
     * @param string &$description The description for the update
     * @return bool Whether an update is needed (TRUE) or not (FALSE)
     */
    public function checkForUpdate(&$description)
    {
        if ($this->isWizardDone()) {
            return false;
        }
        $elementCount = $this->slugService->countOfSlugUpdates();
        if ($elementCount) {
            $description = sprintf('%s news records need to be updated', $elementCount);
        }
        return (bool)$elementCount;
    }

    /**
     * Performs the database update
     *
     * @param array &$databaseQueries Queries done in this update
     * @param string &$customMessage Custom message
     * @return bool
     */
    public function performUpdate(array &$databaseQueries, &$customMessage)
    {
        $queries = $this->slugService->performUpdates();
        foreach ($queries as $query) {
            $databaseQueries[] = $query;
        }
        $this->markWizardAsDone();
        return true;
    }

}
