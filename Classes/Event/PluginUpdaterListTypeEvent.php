<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Event;

final class PluginUpdaterListTypeEvent
{
    public function __construct(
        protected array $flexforms,
        protected array $row,
        protected string $listType
    ) {
    }

    public function getFlexforms(): array
    {
        return $this->flexforms;
    }

    public function getRow(): array
    {
        return $this->row;
    }

    public function getListType(): string
    {
        return $this->listType;
    }

    public function setListType(string $listType): void
    {
        $this->listType = $listType;
    }
}
