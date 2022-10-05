<?php

declare(strict_types=1);

namespace GeorgRinger\News\Seo;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use GeorgRinger\News\Domain\Model\News;
use TYPO3\CMS\Core\PageTitle\AbstractPageTitleProvider;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Generate page title based on properties of the news model
 */
class NewsTitleProvider extends AbstractPageTitleProvider
{
    private const DEFAULT_PROPERTIES = 'title';
    private const DEFAULT_GLUE = '" "';

    /**
     * @param News $news
     * @param array $configuration
     */
    public function setTitleByNews(News $news, array $configuration = []): void
    {
        $title = '';
        $fields = GeneralUtility::trimExplode(',', $configuration['properties'] ?? self::DEFAULT_PROPERTIES, true);

        foreach ($fields as $field) {
            $getter = 'get' . ucfirst($field);
            $value = $news->$getter();
            if ($value) {
                $title = $value;
                break;
            }
        }
        if ($title) {
            $this->title = $title;
        }
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}
