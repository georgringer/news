<?php

namespace GeorgRinger\News\ViewHelpers;

/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use GeorgRinger\News\Domain\Model\News;
use TYPO3\CMS\Core\Imaging\ImageManipulation\CropVariantCollection;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Rendering\RendererRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class RenderMediaViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    protected $escapingInterceptorEnabled = false;

    /**
     * @var string
     */
    protected $mediaTag = '/\\[media\\]/';

    protected $replaceMediaTag = '/(?:<p>\s*)?\\[media\\](?:\s*<\/p>)?/';

    /**
     * @var string
     */
    protected $imgClass = '';

    /**
     * @var string
     */
    protected $videoClass = '';

    /**
     * @var string
     */
    protected $audioClass = '';

    /**
     * Initialize all arguments. You need to override this method and call
     * $this->registerArgument(...) inside this method, to register all your arguments.
     *
     * @api
     */
    public function initializeArguments()
    {
        $this->registerArgument('news', 'object', 'the news post', true);
        $this->registerArgument('imgClass', 'string', 'add css class to images');
        $this->registerArgument('videoClass', 'string', 'wrap videos in a div with this class');
        $this->registerArgument('audioClass', 'string', 'wrap audio files in a div with this class');
        $this->registerArgument('fileIndex', 'int', 'index of image to start with', false, 0);
    }

    /**
     * @param \TYPO3\CMS\Core\Resource\FileInterface $image
     * @return string
     */
    private function renderImage(\TYPO3\CMS\Core\Resource\FileInterface $image): string
    {
        if ($this->objectManager === null) {
            $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        }
        $imageService = $this->objectManager->get(ImageService::class);

        $cropString = '';
        if ($image->hasProperty('crop')) {
            $cropString = $image->getProperty('crop');
        }
        $cropVariantCollection = CropVariantCollection::create((string)$cropString);
        $cropVariant = $this->arguments['cropVariant'] ?: 'default';
        $cropArea = $cropVariantCollection->getCropArea($cropVariant);
        $processingInstructions = [
            'width' => null,
            'height' => null,
            'crop' => $cropArea->isEmpty() ? null : $cropArea->makeAbsoluteBasedOnFile($image)
        ];

        $processedImage = $imageService->applyProcessingInstructions($image, $processingInstructions);
        $imageUri = $imageService->getImageUri($processedImage);

        $alt = trim($image->getProperty('alternative'));
        $title = trim($image->getProperty('title'));
        $description = trim($image->getProperty('description'));

        $imageAttributes = [
            'src' => $imageUri,
            'alt' => $alt ?: ($title ?: ''),
            'title' => $title
        ];

        if (!empty($this->imgClass)) {
            $imageAttributes['class'] = $this->imgClass;
        }

        $imageAttributes = array_reduce(
            array_keys($imageAttributes),
            function ($carry, $key) use ($imageAttributes) {
                return $carry . ' ' . $key . '="' . htmlspecialchars($imageAttributes[$key]) . '"';
            },
            ''
        );

        if (!empty($description)) {
            $description = '<figcaption>' . htmlspecialchars($description) . '</figcaption>';
        }

        return '<figure>' . '<img ' . $imageAttributes . ' />' . $description . '</figure>';
    }

    /**
     * Replace the [media] tags with the output of the according media render output
     *
     * @param string $content
     * @param array $files
     * @return string
     */
    private function renderMedia($content, array $files): string
    {
        $fileIndex = $this->arguments['fileIndex'];
        preg_match_all($this->mediaTag, $content, $matches);
        foreach ($matches[0] as $_) {
            /** @var \GeorgRinger\News\Domain\Model\FileReference $file */
            $file = null;
            /** @var \TYPO3\CMS\Core\Resource\FileReference $media */
            $media = null;

            // check if a file is present for current media tag
            if (count($files) <= $fileIndex) {
                break;
            }

            $file = $files[$fileIndex++];
            if ($file === null) {
                break;
            }

            $media = $file->getOriginalResource();
            $fileRenderer = RendererRegistry::getInstance()->getRenderer($media);

            // if a renderer is configured for the file type use this renderer
            if ($fileRenderer !== null) {
                $media_tag = $fileRenderer->render($media, 0, 0);

                // check if media tag needs to be wrapped in div, depends on type of media file
                $wrapClass= '';
                if ($media->getType() === File::FILETYPE_VIDEO) {
                    $wrapClass = $this->videoClass;
                } elseif ($media->getType() === File::FILETYPE_AUDIO) {
                    $wrapClass = $this->audioClass;
                }

                if (!empty($wrapClass)) {
                    $media_tag = '<div class="' . $wrapClass . '">' . $media_tag . '</div>';
                }
            }
            // fallback to image rendering
            else {
                $media_tag = $this->renderImage($media);
            }

            // replace one tag in content with render output
            $content = preg_replace($this->replaceMediaTag, $media_tag, $content, 1);
        }

        return $content;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        /** @var News $news */
        $news = $this->arguments['news'];

        $this->imgClass = htmlspecialchars($this->arguments['imgClass']);
        $this->videoClass = htmlspecialchars($this->arguments['videoClass']);
        $this->audioClass = htmlspecialchars($this->arguments['audioClass']);

        $mediaFiles = (array)$news->getMediaNonPreviews();

        $content = $this->renderChildren();
        $content = $this->renderMedia($content, $mediaFiles);
        return $content;
    }
}
