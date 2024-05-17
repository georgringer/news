<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Seo;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Core\Site\Entity\SiteInterface;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Check if a news record is available
 */
class NewsAvailability
{
    /**
     * @param int $languageId
     * @param int $newsId
     * @return bool
     */
    public function check(int $languageId, int $newsId = 0): bool
    {
        // get it from current request
        if ($newsId === 0) {
            $newsId = $this->getNewsIdFromRequest();
        }
        if ($newsId === 0) {
            throw new \UnexpectedValueException('No news id provided', 1586431984);
        }

        /** @var SiteInterface $site */
        $site = $this->getRequest()->getAttribute('site');
        $allAvailableLanguagesOfSite = $site->getAllLanguages();

        $targetLanguage = $this->getLanguageFromAllLanguages($allAvailableLanguagesOfSite, $languageId);
        if (!$targetLanguage) {
            throw new \UnexpectedValueException('Target language could not be found', 1586431985);
        }
        return $this->mustBeIncluded($newsId, $targetLanguage);
    }

    protected function mustBeIncluded(int $newsId, SiteLanguage $language): bool
    {
        if ($language->getFallbackType() === 'strict') {
            // @extensionScannerIgnoreLine
            $newsRecord = $this->getNewsRecord($newsId, $language->getLanguageId());

            if (!is_array($newsRecord) || empty($newsRecord)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param SiteLanguage[] $allLanguages
     * @param int $languageId
     */
    protected function getLanguageFromAllLanguages(array $allLanguages, int $languageId): ?SiteLanguage
    {
        foreach ($allLanguages as $siteLanguage) {
            if ($siteLanguage->getLanguageId() === $languageId) {
                return $siteLanguage;
            }
        }
        return null;
    }

    protected function getNewsRecord(int $newsId, int $language)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_news_domain_model_news');
        if ($language === 0) {
            $where = [
                $queryBuilder->expr()->or(
                    $queryBuilder->expr()->eq('sys_language_uid', $queryBuilder->createNamedParameter($language, Connection::PARAM_INT)),
                    $queryBuilder->expr()->eq('sys_language_uid', $queryBuilder->createNamedParameter(-1, Connection::PARAM_INT))
                ),
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($newsId, Connection::PARAM_INT)),
            ];
        } else {
            $where = [
                $queryBuilder->expr()->or(
                    $queryBuilder->expr()->and(
                        $queryBuilder->expr()->eq('sys_language_uid', $queryBuilder->createNamedParameter(-1, Connection::PARAM_INT)),
                        $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($newsId, Connection::PARAM_INT))
                    ),
                    $queryBuilder->expr()->and(
                        $queryBuilder->expr()->eq('l10n_parent', $queryBuilder->createNamedParameter($newsId, Connection::PARAM_INT)),
                        $queryBuilder->expr()->eq('sys_language_uid', $queryBuilder->createNamedParameter($language, Connection::PARAM_INT))
                    ),
                    $queryBuilder->expr()->and(
                        $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($newsId, Connection::PARAM_INT)),
                        $queryBuilder->expr()->eq('l10n_parent', $queryBuilder->createNamedParameter(0, Connection::PARAM_INT)),
                        $queryBuilder->expr()->eq('sys_language_uid', $queryBuilder->createNamedParameter($language, Connection::PARAM_INT))
                    )
                ),
            ];
        }

        $row = $queryBuilder
            ->select('uid', 'l10n_parent', 'sys_language_uid')
            ->from('tx_news_domain_model_news')
            ->where(...$where)
            ->executeQuery()->fetchAssociative();

        return $row ?: null;
    }

    /**
     * @return ServerRequestInterface
     */
    protected function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }

    public function getNewsIdFromRequest(): int
    {
        $newsId = 0;
        /** @var PageArguments $pageArguments */
        $pageArguments = $this->getRequest()->getAttribute('routing');
        if (isset($pageArguments, $pageArguments->getRouteArguments()['tx_news_pi1']['news'])) {
            $newsId = (int)$pageArguments->getRouteArguments()['tx_news_pi1']['news'];
        } elseif (isset($this->getRequest()->getQueryParams()['tx_news_pi1']['news'])) {
            $newsId = (int)$this->getRequest()->getQueryParams()['tx_news_pi1']['news'];
        }
        return $newsId;
    }
}
