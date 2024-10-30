<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Event\Listener;

use TYPO3\CMS\Backend\View\Event\ModifyDatabaseQueryForRecordListingEvent;
use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Database\Connection;

#[AsEventListener(
    identifier: 'ext-news/backend/modify-query-for-record-listing-news-color',
)]
final class ModifyDatabaseQueryForRecordListingEventListenerTopNewsColor
{
    public function __invoke(ModifyDatabaseQueryForRecordListingEvent $event): void
    {
        if ($event->getTable() !== 'tx_news_domain_model_news') {
            return;
        }

        $queryBuilder = $event->getQueryBuilder();
        $queryBuilder->addSelectLiteral(
            $queryBuilder->expr()->if(
                $queryBuilder->expr()->eq('istopnews', $queryBuilder->createNamedParameter(1, Connection::PARAM_INT)),
                $queryBuilder->quote('news-istopnews'),
                $queryBuilder->quote(''),
                '_CSSCLASS'
            ),
        );

        $event->setQueryBuilder($queryBuilder);
    }
}
