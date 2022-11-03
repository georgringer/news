<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Event;

use GeorgRinger\News\Domain\Model\News;

final class ModifyCacheTagsFromNewsEvent
{
    /**
     * @var array
     */
    private $cacheTags;

    /**
     * @var News
     */
    private $news;

    public function __construct(array $cacheTags, News $news)
    {
        $this->cacheTags = $cacheTags;
        $this->news = $news;
    }

    public function getCacheTags(): array
    {
        return $this->cacheTags;
    }

    public function setCacheTags(array $cacheTags): void
    {
        $this->cacheTags = $cacheTags;
    }

    public function getNews(): News
    {
        return $this->news;
    }
}
