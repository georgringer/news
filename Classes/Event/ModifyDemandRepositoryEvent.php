<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Event;

use GeorgRinger\News\Domain\Model\DemandInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

final class ModifyDemandRepositoryEvent
{
    private DemandInterface $demand;
    private bool $respectEnableFields;
    private QueryInterface $query;
    private array $constraints;

    public function __construct(DemandInterface $demand, bool $respectEnableFields, QueryInterface $query, array $constraints)
    {
        $this->demand = $demand;
        $this->respectEnableFields = $respectEnableFields;
        $this->query = $query;
        $this->constraints = $constraints;
    }

    public function getDemand(): DemandInterface
    {
        return $this->demand;
    }

    public function isRespectEnableFields(): bool
    {
        return $this->respectEnableFields;
    }

    public function getQuery(): QueryInterface
    {
        return $this->query;
    }

    public function getConstraints(): array
    {
        return $this->constraints;
    }

    public function setRespectEnableFields(bool $respectEnableFields): void
    {
        $this->respectEnableFields = $respectEnableFields;
    }

    public function setConstraints(array $constraints): void
    {
        $this->constraints = $constraints;
    }
}
