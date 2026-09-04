<?php

namespace GeorgRinger\NewsExtendNews\Domain\Model;

class News extends \GeorgRinger\News\Domain\Model\News
{
    protected ?\TYPO3\CMS\Extbase\Persistence\ObjectStorage $extendNewsItems = null;

    public function initializeObject(): void
    {
        $this->extendNewsItems ??= new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    public function getExtendNewsItems(): ?\TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->extendNewsItems;
    }
}
