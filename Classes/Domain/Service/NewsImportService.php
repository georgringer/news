<?php

namespace GeorgRinger\News\Domain\Service;

use GeorgRinger\News\Domain\Model\Dto\EmConfiguration;
use GeorgRinger\News\Domain\Model\FileReference;
use GeorgRinger\News\Domain\Model\Link;
use GeorgRinger\News\Domain\Model\News;
use GeorgRinger\News\Domain\Repository\CategoryRepository;
use GeorgRinger\News\Domain\Repository\NewsRepository;
use GeorgRinger\News\Domain\Repository\TtContentRepository;
use GeorgRinger\News\Event\NewsImportPostHydrateEvent;
use GeorgRinger\News\Event\NewsImportPreHydrateEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
class NewsImportService extends AbstractImportService
{
    const ACTION_IMPORT_L10N_OVERLAY = 1;

    /**
     * @var NewsRepository
     */
    protected $newsRepository;

    /**
     * @var TtContentRepository
     */
    protected $ttContentRepository;

    /**
     * @var array
     */
    protected $settings = [];

    /**
     * NewsImportService constructor.
     * @param PersistenceManager $persistenceManager
     * @param EmConfiguration $emSettings
     * @param ObjectManager $objectManager
     * @param CategoryRepository $categoryRepository
     * @param EventDispatcherInterface $eventDispatcher
     * @param NewsRepository $newsRepository
     * @param TtContentRepository $ttContentRepository
     */
    public function __construct(
        PersistenceManager $persistenceManager,
        ObjectManager $objectManager,
        CategoryRepository $categoryRepository,
        EventDispatcherInterface $eventDispatcher,
        NewsRepository $newsRepository,
        TtContentRepository $ttContentRepository
    ) {
        parent::__construct($persistenceManager, $objectManager, $categoryRepository, $eventDispatcher);
        $this->newsRepository = $newsRepository;
        $this->ttContentRepository = $ttContentRepository;
    }

    /**
     * @param array $importItem
     *
     * @return array|object
     */
    protected function initializeNewsRecord(array $importItem)
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

    /**
     * @param News $news
     * @param array $importItem
     * @param array $importItemOverwrite
     * @return News
     */
    protected function hydrateNewsRecord(
        News $news,
        array $importItem,
        array $importItemOverwrite
    ): News {
        if (!empty($importItemOverwrite)) {
            $importItem = array_merge($importItem, $importItemOverwrite);
        }
        $news->setPid($importItem['pid']);
        $news->setHidden($importItem['hidden']);
        if ($importItem['starttime']) {
            $news->setStarttime($importItem['starttime']);
        }
        if ($importItem['endtime']) {
            $news->setStarttime($importItem['endtime']);
        }
        if (!empty($importItem['fe_group'])) {
            $news->setFeGroup((string)$importItem['fe_group']);
        }
        $news->setTstamp($importItem['tstamp']);
        $news->setCrdate($importItem['crdate']);
        $news->setSysLanguageUid($importItem['sys_language_uid']);
        $news->setSorting((int)$importItem['sorting']);

        $news->setTitle($importItem['title']);
        $news->setTeaser($importItem['teaser']);
        $news->setBodytext($importItem['bodytext']);

        $news->setType((string)$importItem['type']);
        $news->setKeywords($importItem['keywords']);
        $news->setDescription($importItem['description']);
        $news->setDatetime(new \DateTime(date('Y-m-d H:i:sP', $importItem['datetime'])));
        $news->setArchive(new \DateTime(date('Y-m-d H:i:sP', $importItem['archive'])));

        $contentElementUidArray = GeneralUtility::trimExplode(',', $importItem['content_elements'], true);
        foreach ($contentElementUidArray as $contentElementUid) {
            if (is_object($contentElement = $this->ttContentRepository->findByUid($contentElementUid))) {
                $news->addContentElement($contentElement);
            }
        }

        $news->setInternalurl($importItem['internalurl']);
        $news->setExternalurl($importItem['externalurl']);

        $news->setAuthor($importItem['author']);
        $news->setAuthorEmail($importItem['author_email']);

        $news->setImportId($importItem['import_id']);
        $news->setImportSource($importItem['import_source']);

        $news->setPathSegment($importItem['path_segment']);

        if (is_array($importItem['categories'])) {
            foreach ($importItem['categories'] as $categoryUid) {
                if ($this->settings['findCategoriesByImportSource']) {
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
        if (is_array($importItem['media'])) {
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
                    $media->setTitle($mediaItem['title']);
                    $media->setAlternative($mediaItem['alt']);
                    $media->setDescription($mediaItem['caption']);
                    $media->setShowinpreview($mediaItem['showinpreview']);
                    $media->setPid($importItem['pid']);
                }
            }
        }

        // related files
        if (is_array($importItem['related_files'])) {
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
                    $relatedFile->setTitle($fileItem['title']);
                    $relatedFile->setDescription($fileItem['description']);
                    $relatedFile->setPid($importItem['pid']);
                }
            }
        }

        if (is_array($importItem['related_links'])) {
            foreach ($importItem['related_links'] as $link) {
                /** @var $relatedLink Link */
                if (($relatedLink = $this->getRelatedLinkIfAlreadyExists($news, $link['uri'])) === false) {
                    $relatedLink = GeneralUtility::makeInstance(Link::class);
                    $relatedLink->setUri($link['uri']);
                    $news->addRelatedLink($relatedLink);
                }
                $relatedLink->setTitle($link['title']);
                $relatedLink->setDescription($link['description']);
                $relatedLink->setPid($importItem['pid']);
            }
        }
        $event = $this->eventDispatcher->dispatch(new NewsImportPostHydrateEvent($this, $importItem, $news));

        return $event->getNews();
    }

    /**
     * Import
     *
     * @param array $importData
     * @param array $importItemOverwrite
     * @param array $settings
     *
     * @return void
     */
    public function import(array $importData, array $importItemOverwrite = [], $settings = []): void
    {
        $this->settings = $settings;
        $this->logger->info(sprintf('Starting import for %s news', count($importData)));

        foreach ($importData as $importItem) {
            $event = $this->eventDispatcher->dispatch(new NewsImportPreHydrateEvent($this, $importItem));
            $importItem = $event->getImportItem();

            // Store language overlay in post persist queue
            if ((int)$importItem['sys_language_uid'] > 0 && (string)$importItem['l10n_parent'] !== '0') {
                $this->postPersistQueue[$importItem['import_id']] = [
                    'action' => self::ACTION_IMPORT_L10N_OVERLAY,
                    'category' => null,
                    'importItem' => $importItem
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

        $this->persistenceManager->persistAll();
    }

    /**
     * @param array $queueItem
     * @param array $importItemOverwrite
     *
     * @return void
     */
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

    /**
     * Get an existing items from the references that matches the file
     *
     * @param ObjectStorage $items
     * @param \TYPO3\CMS\Core\Resource\File $file
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
     * @param News $news
     * @param string $uri
     * @return bool|Link
     */
    protected function getRelatedLinkIfAlreadyExists(News $news, $uri)
    {
        $result = false;
        $links = $news->getRelatedLinks();

        if (!empty($links) && $links->count() !== 0) {
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
