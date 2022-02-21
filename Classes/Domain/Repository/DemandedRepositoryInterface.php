<?php

namespace GeorgRinger\News\Domain\Repository;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Domain\Model\DemandInterface;

/**
 * Demand domain model interface
 */
interface DemandedRepositoryInterface
{
    public function findDemanded(DemandInterface $demand, $respectEnableFields = true);

    public function countDemanded(DemandInterface $demand);
}
