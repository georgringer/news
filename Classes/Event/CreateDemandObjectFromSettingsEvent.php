<?php

declare(strict_types=1);
/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Event;

use GeorgRinger\News\Domain\Model\Dto\NewsDemand;

final class CreateDemandObjectFromSettingsEvent
{
    protected NewsDemand $demand;
    protected array $settings;
    protected string $class;

    public function __construct(NewsDemand $demand, array $settings, string $class = '')
    {
        $this->demand = $demand;
        $this->settings = $settings;
        $this->class = $class;
    }

    public function getDemand(): NewsDemand
    {
        return $this->demand;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function setDemand(NewsDemand $demand): void
    {
        $this->demand = $demand;
    }

}
