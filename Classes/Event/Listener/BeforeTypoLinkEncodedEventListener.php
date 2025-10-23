<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Event\Listener;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\LinkHandling\Event\BeforeTypoLinkEncodedEvent;

/**
 * Replace links derived from internal/external url if used in record links
 */
#[AsEventListener(
    identifier: 'ext-news/modify-typolink-for-alternative-types',
)]
class BeforeTypoLinkEncodedEventListener
{
    public function __invoke(BeforeTypoLinkEncodedEvent $event): void
    {
        $parameters = $event->getParameters();
        if (!str_starts_with($parameters['url'], 't3://record?identifier=tx_news')) {
            return;
        }

        preg_match('/[?&]uid=(\d+)/', $parameters['url'], $matches);
        if (!isset($matches[1])) {
            return;
        }

        $row = BackendUtility::getRecord('tx_news_domain_model_news', $matches[1]);
        if (!$row) {
            return;
        }

        if ($row['type'] === '1' && !empty($row['internalurl'])) {
            $parameters['url'] = $row['internalurl'];
        } elseif ($row['type'] === '2' && !empty($row['externalurl'])) {
            $parameters['url'] = $row['externalurl'];
        }

        $event->setParameters($parameters);
    }
}
