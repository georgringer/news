<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Event;

use GeorgRinger\News\Domain\Model\DemandInterface;

final class ModifyCacheTagsFromDemandEvent
{
    /**
     * @var array
     */
    private $cacheTags;

    /**
     * @var DemandInterface
     */
    private $demand;

    public function __construct(array $cacheTags, DemandInterface $demand)
    {
        $this->cacheTags = $cacheTags;
        $this->demand = $demand;
    }

    public function getCacheTags(): array
    {
        return $this->cacheTags;
    }

    public function setCacheTags(array $cacheTags): void
    {
        $this->cacheTags = $cacheTags;
    }

    public function getDemand(): DemandInterface
    {
        return $this->demand;
    }
}
