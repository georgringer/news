<?php

namespace GeorgRinger\News\Domain\Service;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use GeorgRinger\News\Domain\Model\File;
use GeorgRinger\News\Domain\Model\FileReference;
use GeorgRinger\News\Domain\Model\Link;
use GeorgRinger\News\Domain\Model\Media;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * News Import Service
 *
 * @package TYPO3
 * @subpackage tx_news
 * @author Nikolas Hagelstein <nikolas.hagelstein@gmail.com>
 */
class NewsImportService extends AbstractImportService {

	const ACTION_IMPORT_L10N_OVERLAY = 1;


	/**
	 * @var \GeorgRinger\News\Domain\Repository\NewsRepository
	 */
	protected $newsRepository;

	/**
	 * @var \GeorgRinger\News\Domain\Repository\TtContentRepository
	 */
	protected $ttContentRepository;

	/**
	 * @var \GeorgRinger\News\Domain\Repository\CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
	 */
	protected $signalSlotDispatcher;

	/**
	 * @var array
	 */
	protected $settings = array();

	public function __construct() {
		/** @var \TYPO3\CMS\Core\Log\Logger $logger */
		$logger = GeneralUtility::makeInstance('TYPO3\CMS\Core\Log\LogManager')->getLogger(__CLASS__);
		$this->logger = $logger;

		parent::__construct();
	}

	/**
	 * Inject the news repository
	 *
	 * @param \GeorgRinger\News\Domain\Repository\NewsRepository $newsRepository
	 * @return void
	 */
	public function injectNewsRepository(\GeorgRinger\News\Domain\Repository\NewsRepository $newsRepository) {
		$this->newsRepository = $newsRepository;
	}

	/**
	 * Inject the category repository
	 *
	 * @param \GeorgRinger\News\Domain\Repository\CategoryRepository $categoryRepository
	 * @return void
	 */
	public function injectCategoryRepository(\GeorgRinger\News\Domain\Repository\CategoryRepository $categoryRepository) {
		$this->categoryRepository = $categoryRepository;
	}


	/**
	 * Inject the ttcontent repository
	 *
	 * @param \GeorgRinger\News\Domain\Repository\TtContentRepository $ttContentRepository
	 * @return void
	 */
	public function injectTtContentRepository(\GeorgRinger\News\Domain\Repository\TtContentRepository $ttContentRepository) {
		$this->ttContentRepository = $ttContentRepository;
	}

	/**
	 * Inject SignalSlotDispatcher
	 *
	 * @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher
	 * @return void
	 */
	public function injectSignalSlotDispatcher(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher) {
		$this->signalSlotDispatcher = $signalSlotDispatcher;
	}

	/**
	 * @param array $importItem
	 * @return null|\GeorgRinger\News\Domain\Model\News
	 */
	protected function initializeNewsRecord(array $importItem) {
		$news = NULL;

		$this->logger->info(sprintf('Import of news from source "%s" with id "%s"', $importItem['import_source'], $importItem['import_id']));

		if ($importItem['import_source'] && $importItem['import_id']) {
			$news = $this->newsRepository->findOneByImportSourceAndImportId($importItem['import_source'],
				$importItem['import_id']);
		}

		if ($news === NULL) {
			$news = $this->objectManager->get('GeorgRinger\\News\\Domain\\Model\\News');
			$this->newsRepository->add($news);
		} else {
			$this->logger->info(sprintf('News exists already with id "%s".', $news->getUid()));
			$this->newsRepository->update($news);
		}

		return $news;
	}

	/**
	 * @param \GeorgRinger\News\Domain\Model\News $news
	 * @param array $importItem
	 * @param array $importItemOverwrite
	 * @return \GeorgRinger\News\Domain\Model\News
	 */
	protected function hydrateNewsRecord(\GeorgRinger\News\Domain\Model\News $news, array $importItem, array $importItemOverwrite) {

		if (!empty($importItemOverwrite)) {
			$importItem = array_merge($importItem, $importItemOverwrite);
		}

		$news->setPid($importItem['pid']);
		$news->setHidden($importItem['hidden']);
		$news->setStarttime($importItem['starttime']);
		$news->setEndtime($importItem['endtime']);
		$news->setFeGroup($importItem['fe_group']);
		$news->setTstamp($importItem['tstamp']);
		$news->setCrdate($importItem['crdate']);
		$news->setSysLanguageUid($importItem['sys_language_uid']);
		$news->setSorting((int)$importItem['sorting']);

		$news->setTitle($importItem['title']);
		$news->setTeaser($importItem['teaser']);
		$news->setBodytext($importItem['bodytext']);

		$news->setType($importItem['type']);
		$news->setKeywords($importItem['keywords']);
		$news->setDatetime(new \DateTime(date('Y-m-d H:i:sP', $importItem['datetime'])));
		$news->setArchive(new \DateTime(date('Y-m-d H:i:sP', $importItem['archive'])));

		$contentElementUidArray = \TYPO3\CMS\Extbase\Utility\ArrayUtility::trimExplode(',', $importItem['content_elements'], TRUE);
		foreach ($contentElementUidArray as $contentElementUid) {
			if (is_object($contentElement = $this->ttContentRepository->findByUid($contentElementUid))) {
				$news->addContentElement($contentElement);
			}
		}

		$news->setInternalurl($importItem['internalurl']);
		$news->setExternalurl($importItem['externalurl']);

		$news->setType($importItem['type']);
		$news->setKeywords($importItem['keywords']);

		$news->setAuthor($importItem['author']);
		$news->setAuthorEmail($importItem['author_email']);

		$news->setImportid($importItem['import_id']);
		$news->setImportSource($importItem['import_source']);

		if (is_array($importItem['categories'])) {
			foreach ($importItem['categories'] as $categoryUid) {
				if ($this->settings['findCategoriesByImportSource']) {
					$category = $this->categoryRepository->findOneByImportSourceAndImportId(
						$this->settings['findCategoriesByImportSource'], $categoryUid);
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

		/** @var $basicFileFunctions \TYPO3\CMS\Core\Utility\File\BasicFileUtility */
		$basicFileFunctions = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Utility\\File\\BasicFileUtility');

		// media relation
		if (is_array($importItem['media'])) {

			foreach ($importItem['media'] as $mediaItem) {

				// multi media
				if ((int)$mediaItem['type'] === Media::MEDIA_TYPE_MULTIMEDIA) {

					if (($media = $this->getMultiMediaIfAlreadyExists($news, $mediaItem['multimedia'])) === FALSE) {
						/** @var Media $media */
						$media = $this->objectManager->get('GeorgRinger\\News\\Domain\\Model\\Media');
						$media->setMultimedia($mediaItem['multimedia']);
						$news->addMedia($media);
					}

					if (isset($mediaItem['caption'])) {
						$media->setDescription($mediaItem['caption']);
					}
					if (isset($mediaItem['copyright'])) {
						$media->setCopyright($mediaItem['copyright']);
					}
					if (isset($mediaItem['showinpreview'])) {
						$media->setShowinpreview($mediaItem['showinpreview']);
					}
					$media->setType($mediaItem['type']);
					$media->setPid($importItem['pid']);

					// Images FAL enabled
				} elseif($this->emSettings->getUseFal() > 0) {

					// get fileobject by given identifier (file UID, combined identifier or path/filename)
					try {
						$file = $this->getResourceFactory()->retrieveFileOrFolderObject($mediaItem['image']);
					} catch (\TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException $exception) {
						$file = FALSE;
					}

					// no file found skip processing of this item
					if ($file === FALSE) {
						continue;
					}

					// file not inside a storage then search for same file based on hash (to prevent duplicates)
					if ($file->getStorage()->getUid() === 0) {
						$existingFile = $this->findFileByHash($file->getSha1());
						if ($existingFile !== NULL) {
							$file = $existingFile;
						}
					}

					/** @var $media FileReference */
					if (!$media = $this->getIfFalRelationIfAlreadyExists($news->getFalMedia(), $file)) {

						// file not inside a storage copy the one form storage 0 to the import folder
						if ($file->getStorage()->getUid() === 0) {
							$file = $this->getResourceStorage()->copyFile($file, $this->getImportFolder());
						}

						$media = $this->objectManager->get('GeorgRinger\\News\\Domain\\Model\\FileReference');
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
				} else {

					if (!$media = $this->getMediaIfAlreadyExists($news, $mediaItem['image'])) {

						$uniqueName = $basicFileFunctions->getUniqueName($mediaItem['image'],
							PATH_site . self::UPLOAD_PATH);

						copy(
							PATH_site . $mediaItem['image'],
							$uniqueName
						);

						$media = $this->objectManager->get('GeorgRinger\\News\\Domain\\Model\\Media');
						$news->addMedia($media);

						$media->setImage(basename($uniqueName));
					}

					$media->setTitle($mediaItem['title']);
					$media->setAlt($mediaItem['alt']);
					$media->setCaption($mediaItem['caption']);
					$media->setType($mediaItem['type']);
					$media->setShowinpreview($mediaItem['showinpreview']);
					$media->setPid($importItem['pid']);
				}
			}
		}

		// related files
		if (is_array($importItem['related_files'])) {

			// FAL enabled
			if ($this->emSettings->getUseFal() > 0) {

				foreach ($importItem['related_files'] as $fileItem) {

					// get fileObject by given identifier (file UID, combined identifier or path/filename)
					try {
						$file = $this->getResourceFactory()->retrieveFileOrFolderObject($fileItem['file']);
					} catch(\TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException $exception) {
						$file = FALSE;
					}

					// no file found skip processing of this item
					if ($file === FALSE) {
						continue;
					}

					// file not inside a storage then search for same file based on hash (to prevent duplicates)
					if ($file->getStorage()->getUid() === 0) {
						$existingFile = $this->findFileByHash($file->getSha1());
						if ($existingFile !== NULL) {
							$file = $existingFile;
						}
					}

					/** @var $relatedFile FileReference */
					if (!$relatedFile = $this->getIfFalRelationIfAlreadyExists($news->getFalRelatedFiles(), $file)) {

						// file not inside a storage copy the one form storage 0 to the import folder
						if ($file->getStorage()->getUid() === 0) {
							$file = $this->getResourceStorage()->copyFile($file, $this->getImportFolder());
						}

						$relatedFile = $this->objectManager->get('GeorgRinger\\News\\Domain\\Model\\FileReference');
						$relatedFile->setFileUid($file->getUid());
						$news->addFalRelatedFile($relatedFile);
					}

					if ($relatedFile) {
						$relatedFile->setTitle($fileItem['title']);
						$relatedFile->setDescription($fileItem['description']);
						$relatedFile->setPid($importItem['pid']);
					}
				}

			} else {

				foreach ($importItem['related_files'] as $file) {
					if (!$relatedFile = $this->getRelatedFileIfAlreadyExists($news, $file['file'])) {

						$uniqueName = $basicFileFunctions->getUniqueName($file['file'],
							PATH_site . self::UPLOAD_PATH);

						copy(
							PATH_site . $file['file'],
							$uniqueName
						);

						$relatedFile = $this->objectManager->get('GeorgRinger\\News\\Domain\\Model\\File');
						$news->addRelatedFile($relatedFile);

						$relatedFile->setFile(basename($uniqueName));
					}
					$relatedFile->setTitle($file['title']);
					$relatedFile->setDescription($file['description']);
					$relatedFile->setPid($importItem['pid']);
				}
			}
		}

		if (is_array($importItem['related_links'])) {
			foreach ($importItem['related_links'] as $link) {
				/** @var $relatedLink Link */
				if (($relatedLink = $this->getRelatedLinkIfAlreadyExists($news, $link['uri'])) === FALSE) {
					$relatedLink = $this->objectManager->get('GeorgRinger\\News\\Domain\\Model\\Link');
					$relatedLink->setUri($link['uri']);
					$news->addRelatedLink($relatedLink);
				}
				$relatedLink->setTitle($link['title']);
				$relatedLink->setDescription($link['description']);
				$relatedLink->setPid($importItem['pid']);
			}
		}

		$arguments = array('importItem' => $importItem, 'news' => $news);
		$this->emitSignal('postHydrate', $arguments);

		return $news;
	}

	/**
	 * Import
	 *
	 * @param array $importData
	 * @param array $importItemOverwrite
	 * @param array $settings
	 * @return void
	 */
	public function import(array $importData, array $importItemOverwrite = array(), $settings = array()) {
		$this->settings = $settings;
		$this->logger->info(sprintf('Starting import for %s news', count($importData)));

		foreach ($importData as $importItem) {

			// Store language overlay in post persist queue
			if ((int)$importItem['sys_language_uid'] > 0 && (string)$importItem['l10n_parent'] !== '0') {
				$this->postPersistQueue[$importItem['import_id']] = array(
					'action' => self::ACTION_IMPORT_L10N_OVERLAY,
					'category' => NULL,
					'importItem' => $importItem
				);
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
	 * @return void
	 */
	protected function importL10nOverlay(array $queueItem, array $importItemOverwrite) {
		$importItem = $queueItem['importItem'];
		$parentNews = $this->newsRepository->findOneByImportSourceAndImportId(
			$importItem['import_source'],
			$importItem['l10n_parent']
		);

		if ($parentNews !== NULL) {
			$news = $this->initializeNewsRecord($importItem);

			$this->hydrateNewsRecord($news, $importItem, $importItemOverwrite);

			$news->setSysLanguageUid($importItem['sys_language_uid']);
			$news->setL10nParent($parentNews->getUid());
		}

	}

	/**
	 * Get media file if it exists
	 *
	 * @param \GeorgRinger\News\Domain\Model\News $news
	 * @param string $mediaFile
	 * @return Boolean|\GeorgRinger\News\Domain\Model\Media
	 */
	protected function getMediaIfAlreadyExists(\GeorgRinger\News\Domain\Model\News $news, $mediaFile) {
		$result = FALSE;
		$mediaItems = $news->getMedia();

		if (isset($mediaItems) && $mediaItems->count() !== 0) {
			foreach ($mediaItems as $mediaItem) {
				$pathInfoItem = pathinfo($mediaItem->getImage());
				$pathInfoMediaFile = pathInfo($mediaFile);
				if (GeneralUtility::isFirstPartOfStr($pathInfoItem['filename'], $pathInfoMediaFile['filename'])  &&
					$this->filesAreEqual(PATH_site . $mediaFile, PATH_site . self::UPLOAD_PATH . $mediaItem->getImage())) {
					$result = $mediaItem;
					break;
				}
			}
		}
		return $result;
	}

	/**
	 * Get multimedia object if it exists
	 *
	 * @param \GeorgRinger\News\Domain\Model\News $news
	 * @param string $url
	 * @return Boolean|\GeorgRinger\News\Domain\Model\Media
	 */
	protected function getMultiMediaIfAlreadyExists(\GeorgRinger\News\Domain\Model\News $news, $url) {
		$result = FALSE;
		$mediaItems = $news->getMedia();

		if (isset($mediaItem) && $mediaItems->count() !== 0) {
			foreach ($mediaItems as $mediaItem) {
				if ($mediaItem->getMultimedia() === $url) {
					$result = $mediaItem;
					break;
				}
			}
		}
		return $result;
	}

	/**
	 * Get related file if it exists
	 *
	 * @param \GeorgRinger\News\Domain\Model\News $news
	 * @param string $relatedFile
	 * @return Boolean|File
	 */
	protected function getRelatedFileIfAlreadyExists(\GeorgRinger\News\Domain\Model\News $news, $relatedFile) {
		$result = FALSE;
		$relatedItems = $news->getRelatedFiles();

		if ($relatedItems->count() !== 0) {
			foreach ($relatedItems as $relatedItem) {
				if ($relatedItem->getFile() == basename($relatedFile) &&
					$this->filesAreEqual(
						PATH_site . $relatedFile,
						PATH_site . self::UPLOAD_PATH . $relatedItem->getFile()
					)) {
					$result = $relatedItem;
					break;
				}
			}
		}
		return $result;
	}

	/**
	 * Get an existing items from the references that matches the file
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\GeorgRinger\News\Domain\Model\FileReference> $items
	 * @param \TYPO3\CMS\Core\Resource\File $file
	 * @return bool|FileReference
	 */
	protected function getIfFalRelationIfAlreadyExists(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $items, \TYPO3\CMS\Core\Resource\File $file) {
		$result = FALSE;
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
	 * @param \GeorgRinger\News\Domain\Model\News $news
	 * @param string $uri
	 * @return bool|Link
	 */
	protected function getRelatedLinkIfAlreadyExists(\GeorgRinger\News\Domain\Model\News $news, $uri) {
		$result = FALSE;
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

	/**
	 * Emits signal
	 *
	 * @param string $signalName name of the signal slot
	 * @param array $signalArguments arguments for the signal slot
	 */
	protected function emitSignal($signalName, array $signalArguments) {
		$this->signalSlotDispatcher->dispatch('GeorgRinger\\News\\Domain\\Service\\NewsImportService', $signalName, $signalArguments);
	}
}
