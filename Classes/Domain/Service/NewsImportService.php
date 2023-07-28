<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Domain\Service;

use GeorgRinger\News\Domain\Model\FileReference;
use GeorgRinger\News\Domain\Model\Link;
use GeorgRinger\News\Domain\Model\News;
use GeorgRinger\News\Domain\Repository\CategoryRepository;
use GeorgRinger\News\Domain\Repository\NewsRepository;
use GeorgRinger\News\Domain\Repository\TtContentRepository;
use GeorgRinger\News\Event\NewsImportPostHydrateEvent;
use GeorgRinger\News\Event\NewsImportPreHydrateEvent;
use GeorgRinger\News\Event\NewsPostImportEvent;
use GeorgRinger\News\Event\NewsPreImportEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\DataHandling\Model\RecordStateFactory;
use TYPO3\CMS\Core\DataHandling\SlugHelper;
use TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class NewsImportService extends AbstractImportService
{
    public const ACTION_IMPORT_L10N_OVERLAY = 1;

    /**
     * @var NewsRepository
     */
    protected $newsRepository;

    /**
     * @var TtContentRepository
     */
    protected $ttContentRepository;

    /** @var SlugHelper */
    protected $slugHelper;

    /**
     * @var array
     */
    protected $settings = [];

    /**
     * NewsImportService constructor.
     */
    public function __construct(
        PersistenceManager $persistenceManager,
        CategoryRepository $categoryRepository,
        EventDispatcherInterface $eventDispatcher,
        NewsRepository $newsRepository,
        TtContentRepository $ttContentRepository
    ) {
        parent::__construct($persistenceManager, $categoryRepository, $eventDispatcher);

        $this->newsRepository = $newsRepository;
        $this->ttContentRepository = $ttContentRepository;

        $fieldConfig = $GLOBALS['TCA']['tx_news_domain_model_news']['columns']['path_segment']['config'];
        $this->slugHelper = GeneralUtility::makeInstance(SlugHelper::class, 'tx_news_domain_model_news', 'path_segment', $fieldConfig);
    }

    protected function initializeNewsRecord(array $importItem): News
    {
        $news = null;

        $this->logger->info(sprintf(
            'Import of news from source "%s" with id "%s"',
            $importItem['import_source'],
            $importItem['import_id']
        ));

        if ($importItem['import_source'] && $importItem['import_id']) {
            $news = $this->newsRepository->findOneByImportSourceAndImportId(
                $importItem['import_source'],
                $importItem['import_id']
            );
        }

        if ($news === null) {
            $news = GeneralUtility::makeInstance(News::class);
            $this->newsRepository->add($news);
        } else {
            $this->logger->info(sprintf('News exists already with id "%s".', $news->getUid()));
            $this->newsRepository->update($news);
        }

        return $news;
    }

    protected function hydrateNewsRecord(
        News $news,
        array $importItem,
        array $importItemOverwrite
    ): News {
        if (!empty($importItemOverwrite)) {
            $importItem = array_merge($importItem, $importItemOverwrite);
        }
        $news->setPid($importItem['pid']);
        $news->setHidden((bool)($importItem['hidden'] ?? false));

        if (
            isset($importItem['starttime'])
            && $date = $this->convertTimestampToDateTime((int)$importItem['starttime'])
        ) {
            $news->setStarttime($date);
        }
        if (
            isset($importItem['endtime'])
            && $date = $this->convertTimestampToDateTime((int)$importItem['endtime'])
        ) {
            $news->setEndtime($date);
        }
        if (
            isset($importItem['crdate'])
            && $date = $this->convertTimestampToDateTime((int)$importItem['crdate'])
        ) {
            $news->setCrdate($date);
        }
        if (
            isset($importItem['tstamp'])
            && $date = $this->convertTimestampToDateTime((int)$importItem['tstamp'])
        ) {
            $news->setTstamp($date);
        }
        if (!empty($importItem['fe_group'] ?? '')) {
            $news->setFeGroup((string)$importItem['fe_group']);
        }
        $news->setSysLanguageUid($importItem['sys_language_uid'] ?? 0);
        $news->setSorting((int)($importItem['sorting'] ?? 0));

        $news->setTitle($importItem['title']);
        $news->setTeaser($importItem['teaser'] ?? '');
        $news->setBodytext($importItem['bodytext'] ?? '');

        $news->setType((string)($importItem['type'] ?? ''));
        $news->setKeywords($importItem['keywords'] ?? '');
        $news->setDescription($importItem['description'] ?? '');
        $news->setDatetime(new \DateTime(date('Y-m-d H:i:sP', $importItem['datetime'] ?? 0)));
        $news->setArchive(new \DateTime(date('Y-m-d H:i:sP', $importItem['archive'] ?? 0)));

        $contentElementUidArray = GeneralUtility::trimExplode(',', $importItem['content_elements'] ?? '', true);
        foreach ($contentElementUidArray as $contentElementUid) {
            if (is_object($contentElement = $this->ttContentRepository->findByUid($contentElementUid))) {
                $news->addContentElement($contentElement);
            }
        }

        $news->setInternalurl($importItem['internalurl'] ?? '');
        $news->setExternalurl($importItem['externalurl'] ?? '');

        $news->setAuthor($importItem['author'] ?? '');
        $news->setAuthorEmail($importItem['author_email'] ?? '');

        $news->setImportId((string)($importItem['import_id'] ?? ''));
        $news->setImportSource((string)($importItem['import_source'] ?? ''));

        if ($importItem['path_segment'] ?? false) {
            $news->setPathSegment($importItem['path_segment']);
        } elseif (($importItem['generate_path_segment'] ?? false) && !$news->getPathSegment()) {
            $news->setPathSegment($this->generateSlug($importItem, $importItem['pid']));
        }

        if (is_array($importItem['categories'] ?? false)) {
            foreach ($importItem['categories'] as $categoryUid) {
                if ($this->settings['findCategoriesByImportSource'] ?? false) {
                    $category = $this->categoryRepository->findOneByImportSourceAndImportId(
                        $this->settings['findCategoriesByImportSource'],
                        $categoryUid
                    );
                } else {
                    $category = $this->categoryRepository->findByUid($categoryUid);
                }

                if ($category) {
                    $news->addCategory($category);
                } else {
                    $this->logger->warning(sprintf('Category with ID "%s" was not found', $categoryUid));
                }
            }
        }

        // media relation
        if (is_array($importItem['media'] ?? false)) {
            foreach ($importItem['media'] as $mediaItem) {
                // get fileobject by given identifier (file UID, combined identifier or path/filename)
                try {
                    $file = $this->getResourceFactory()->retrieveFileOrFolderObject($mediaItem['image']);
                } catch (ResourceDoesNotExistException $exception) {
                    $file = null;
                }

                // no file found skip processing of this item
                if ($file === null) {
                    continue;
                }

                // file not inside a storage then search for same file based on hash (to prevent duplicates)
                if ($file->getStorage()->getUid() === 0) {
                    $existingFile = $this->findFileByHash($file->getSha1());
                    if ($existingFile !== null) {
                        $file = $existingFile;
                    }
                }

                /** @var $media FileReference */
                if (!$media = $this->getIfFalRelationIfAlreadyExists($news->getFalMedia(), $file)) {
                    // file not inside a storage copy the one form storage 0 to the import folder
                    if ($file->getStorage()->getUid() === 0) {
                        $file = $this->getResourceStorage()->copyFile($file, $this->getImportFolder());
                    }

                    $media = GeneralUtility::makeInstance(FileReference::class);
                    $media->setFileUid($file->getUid());
                    $news->addFalMedia($media);
                }

                if ($media) {
                    $media->setTitle($mediaItem['title'] ?? '');
                    $media->setAlternative($mediaItem['alt'] ?? '');
                    $media->setDescription($mediaItem['caption'] ?? '');
                    $media->setShowinpreview($mediaItem['showinpreview'] ?? '');
                    $media->setPid($importItem['pid']);
                }
            }
        }

        // related files
        if (is_array($importItem['related_files'] ?? false)) {
            foreach ($importItem['related_files'] as $fileItem) {
                // get fileObject by given identifier (file UID, combined identifier or path/filename)
                try {
                    $file = $this->getResourceFactory()->retrieveFileOrFolderObject($fileItem['file']);
                } catch (ResourceDoesNotExistException $exception) {
                    $file = null;
                }

                // no file found skip processing of this item
                if ($file === null) {
                    continue;
                }

                // file not inside a storage then search for same file based on hash (to prevent duplicates)
                if ($file->getStorage()->getUid() === 0) {
                    $existingFile = $this->findFileByHash($file->getSha1());
                    if ($existingFile !== null) {
                        $file = $existingFile;
                    }
                }

                /** @var $relatedFile FileReference */
                if (!$relatedFile = $this->getIfFalRelationIfAlreadyExists($news->getFalRelatedFiles(), $file)) {
                    // file not inside a storage copy the one form storage 0 to the import folder
                    if ($file->getStorage()->getUid() === 0) {
                        $file = $this->getResourceStorage()->copyFile($file, $this->getImportFolder());
                    }

                    $relatedFile = GeneralUtility::makeInstance(FileReference::class);
                    $relatedFile->setFileUid($file->getUid());
                    $news->addFalRelatedFile($relatedFile);
                }

                if ($relatedFile) {
                    $relatedFile->setTitle($fileItem['title'] ?? '');
                    $relatedFile->setDescription($fileItem['description'] ?? '');
                    $relatedFile->setPid($importItem['pid'] ?? '');
                }
            }
        }

        if (is_array($importItem['related_links'] ?? false)) {
            foreach ($importItem['related_links'] as $link) {
                /** @var $relatedLink Link */
                if (($relatedLink = $this->getRelatedLinkIfAlreadyExists($news, $link['uri'])) === false) {
                    $relatedLink = GeneralUtility::makeInstance(Link::class);
                    $relatedLink->setUri($link['uri']);
                    $news->addRelatedLink($relatedLink);
                }
                $relatedLink->setTitle($link['title'] ?? '');
                $relatedLink->setDescription($link['description'] ?? '');
                $relatedLink->setPid($importItem['pid']);
            }
        }
        $event = $this->eventDispatcher->dispatch(new NewsImportPostHydrateEvent($this, $importItem, $news));

        return $event->getNews();
    }

    public function import(array $importData, array $importItemOverwrite = [], array $settings = []): void
    {
        $this->settings = $settings;
        $this->logger->info(sprintf('Starting import for %s news', count($importData)));

        $preImportDataEvent = $this->eventDispatcher->dispatch(new NewsPreImportEvent($this, $importData));
        $importData = $preImportDataEvent->getImportData();

        foreach ($importData as $importItem) {
            $event = $this->eventDispatcher->dispatch(new NewsImportPreHydrateEvent($this, $importItem));
            $importItem = $event->getImportItem();

            // Store language overlay in post persist queue
            if ((int)($importItem['sys_language_uid'] ?? 0) > 0 && (string)($importItem['l10n_parent'] ?? 0) !== '0') {
                $this->postPersistQueue[$importItem['import_id']] = [
                    'action' => self::ACTION_IMPORT_L10N_OVERLAY,
                    'category' => null,
                    'importItem' => $importItem,
                ];
                continue;
            }

            $news = $this->initializeNewsRecord($importItem);

            $this->hydrateNewsRecord($news, $importItem, $importItemOverwrite);
        }

        $this->persistenceManager->persistAll();

        foreach ($this->postPersistQueue as $queueItem) {
            if ($queueItem['action'] == self::ACTION_IMPORT_L10N_OVERLAY) {
                $this->importL10nOverlay($queueItem, $importItemOverwrite);
            }
        }

        $this->eventDispatcher->dispatch(new NewsPostImportEvent($this, $importData));

        $this->persistenceManager->persistAll();
    }

    protected function importL10nOverlay(array $queueItem, array $importItemOverwrite): void
    {
        $importItem = $queueItem['importItem'];
        $parentNews = $this->newsRepository->findOneByImportSourceAndImportId(
            $importItem['import_source'],
            $importItem['l10n_parent'],
            true
        );

        if (!empty($parentNews)) {
            $news = $this->initializeNewsRecord($importItem);

            $this->hydrateNewsRecord($news, $importItem, $importItemOverwrite);

            $news->setSysLanguageUid($importItem['sys_language_uid']);
            $news->setL10nParent($parentNews['uid']);
        }
    }

    protected function generateSlug(array $fullRecord, int $pid)
    {
        $value = $this->slugHelper->generate($fullRecord, $pid);

        $state = RecordStateFactory::forName('tx_news_domain_model_news')
            ->fromArray($fullRecord, $pid, 0);
        $tcaFieldConf = $GLOBALS['TCA']['tx_news_domain_model_news']['columns']['path_segment']['config'];
        $evalCodesArray = GeneralUtility::trimExplode(',', $tcaFieldConf['eval'], true);
        if (in_array('unique', $evalCodesArray, true)) {
            $value = $this->slugHelper->buildSlugForUniqueInTable($value, $state);
        }
        if (in_array('uniqueInSite', $evalCodesArray, true)) {
            $value = $this->slugHelper->buildSlugForUniqueInSite($value, $state);
        }
        if (in_array('uniqueInPid', $evalCodesArray, true)) {
            $value = $this->slugHelper->buildSlugForUniqueInPid($value, $state);
        }

        return $value;
    }

    /**
     * Get an existing items from the references that matches the file
     *
     * @return bool|FileReference
     */
    protected function getIfFalRelationIfAlreadyExists(
        ObjectStorage $items,
        File $file
    ) {
        $result = false;
        if ($items->count() !== 0) {
            /** @var $item FileReference */
            foreach ($items as $item) {
                // only check already persisted items
                if ($item->getFileUid() === (int)$file->getUid()
                    ||
                    ($item->getUid() &&
                        $item->getOriginalResource()->getName() === $file->getName() &&
                        $item->getOriginalResource()->getSize() === (int)$file->getSize())
                ) {
                    $result = $item;
                    break;
                }
            }
        }
        return $result;
    }

    /**
     * Get an existing related link object
     *
     * @return bool|Link
     */
    protected function getRelatedLinkIfAlreadyExists(News $news, string $uri)
    {
        $result = false;
        $links = $news->getRelatedLinks();

        if ($links !== null && $links->count() !== 0) {
            foreach ($links as $link) {
                if ($link->getUri() === $uri) {
                    $result = $link;
                    break;
                }
            }
        }
        return $result;
    }
}
