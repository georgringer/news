<?php

namespace GeorgRinger\NewsExtendNewsdefault\Domain\Model;

class NewsDefault extends \GeorgRinger\News\Domain\Model\NewsDefault
{
    protected ?\TYPO3\CMS\Extbase\Persistence\ObjectStorage $extendDefaultItems = null;

    public function initializeObject(): void
    {
        $this->extendDefaultItems ??= new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    public function getExtendDefaultItems(): ?\TYPO3\CMS\Extbase\Persistence\ObjectStorage
    {
        return $this->extendDefaultItems;
    }
}
