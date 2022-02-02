<?php

namespace GeorgRinger\News\ViewHelpers;

use GeorgRinger\News\Domain\Model\News;
use GeorgRinger\News\Service\SettingsService;
use TYPO3\CMS\Backend\Utility\BackendUtility;
/**
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * ViewHelper to render links from news records to detail view or page
 *
 * # Example: Basic link
 * <code>
 * <n:link newsItem="{newsItem}" settings="{settings}">
 *    {newsItem.title}
 * </n:link>
 * </code>
 * <output>
 * A link to the given news record using the news title as link text
 * </output>
 *
 * # Example: Set an additional attribute
 * # Description: Available: class, dir, id, lang, style, title, accesskey, tabindex, onclick
 * <code>
 * <n:link newsItem="{newsItem}" settings="{settings}" class="a-link-class">fo</n:link>
 * </code>
 * <output>
 * <a href="link" class="a-link-class">fo</n:link>
 * </output>
 *
 * # Example: Return the link only
 * <code>
 * <n:link newsItem="{newsItem}" settings="{settings}" uriOnly="1" />
 * </code>
 * <output>
 * The uri is returned
 * </output>
 */
class LinkViewHelper extends AbstractTagBasedViewHelper
{

    /**
     * @var string
     */
    protected $tagName = 'a';

    /**
     * @var \GeorgRinger\News\Service\SettingsService
     */
    protected $pluginSettingsService;

    /**
     * @var array
     */
    protected $detailPidDeterminationCallbacks = [
        'flexform' => 'getDetailPidFromFlexform',
        'categories' => 'getDetailPidFromCategories',
        'default' => 'getDetailPidFromDefaultDetailPid',
    ];

    /** @var ContentObjectRenderer */
    protected $cObj;

    /**
     * @param \GeorgRinger\News\Service\SettingsService $pluginSettingsService
     *
     * @return void
     */
    public function injectSettingsService(SettingsService $pluginSettingsService): void
    {
        $this->pluginSettingsService = $pluginSettingsService;
    }

    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerUniversalTagAttributes();
        $this->registerArgument('newsItem', News::class, 'news item', true);
        $this->registerArgument('settings', 'array', 'Settings', false, []);
        $this->registerArgument('uriOnly', 'bool', 'url only', false, false);
        $this->registerArgument('configuration', 'array', 'configuration', false, []);
        $this->registerArgument('content', 'string', 'content', false, '');
        $this->registerArgument('section', 'string', 'Anchor for links', false);
    }

    /**
     * Render link to news item or internal/external pages
     *
     * @return null|string link
     */
    public function render(): ?string
    {
        /** @var News $newsItem */
        $newsItem = $this->arguments['newsItem'];
        $settings = $this->arguments['settings'] ?? [];
        $uriOnly = $this->arguments['uriOnly'] ?? false;
        $configuration = $this->arguments['configuration'] ?? [];
        $content = $this->arguments['content'] ?? '';

        $tsSettings = (array)$this->pluginSettingsService->getSettings();
        ArrayUtility::mergeRecursiveWithOverrule($tsSettings, (array)$settings);
        // Options with stdWrap enabled won't override $tsSettings as intended here: override them explicit.
        if (isset($settings['useStdWrap']) && $settings['useStdWrap']) {
            foreach (GeneralUtility::trimExplode(',', $settings['useStdWrap'], true) as $stdWrapProperty) {
                if (is_array($tsSettings[$stdWrapProperty]) && array_key_exists($stdWrapProperty, $settings)) {
                    $tsSettings[$stdWrapProperty] = $settings[$stdWrapProperty];
                }
            }
        }

        $this->init();
        $linkedContent = $this->renderChildren();

        if ($newsItem === null) {
            return $linkedContent;
        }

        $newsType = (int)$newsItem->getType();
        switch ($newsType) {
            // internal news
            case 1:
                $configuration['parameter'] = $newsItem->getInternalurl();
                break;
            // external news
            case 2:
                $configuration['parameter'] = $newsItem->getExternalurl();
                break;
            // normal news record
            default:
                $configuration = $this->getLinkToNewsItem($newsItem, $tsSettings, $configuration);
        }

        $url = $this->cObj->typoLink_URL($configuration);

        // link could not be generated
        if ($url === '' || $linkedContent === $url) {
            return $linkedContent;
        }

        if ($this->hasArgument('section')) {
            $url .= '#' . $this->arguments['section'];
        }

        if ($uriOnly) {
            return $url;
        }

        if (isset($tsSettings['link']['typesOpeningInNewWindow'])) {
            if (GeneralUtility::inList($tsSettings['link']['typesOpeningInNewWindow'], $newsType)) {
                $this->tag->addAttribute('target', '_blank');
            }
        }

        if (!$this->tag->hasAttribute('target')) {
            $target = $this->getTargetConfiguration($configuration);
            if (!empty($target)) {
                $this->tag->addAttribute('target', $target);
            }
        }

        $this->tag->addAttribute('href', $url);

        if (empty($content)) {
            $content = $linkedContent;
        }
        $this->tag->setContent($content);

        return $this->tag->render();
    }

    /**
     * Generate the link configuration for the link to the news item
     *
     * @param News $newsItem
     * @param array $tsSettings
     * @param array $configuration
     * @return array
     */
    protected function getLinkToNewsItem(
        News $newsItem,
        $tsSettings,
        array $configuration = []
    ): array {
        if (!isset($configuration['parameter'])) {
            $detailPid = 0;
            // if TS is not set, prefer flexform setting
            if (!isset($tsSettings['detailPidDetermination'])) {
                $detailPidDeterminationMethods = ['flexform'];
            } else {
                $detailPidDeterminationMethods = GeneralUtility::trimExplode(
                    ',',
                    $tsSettings['detailPidDetermination'],
                    true
                );
            }

            foreach ($detailPidDeterminationMethods as $determinationMethod) {
                if ($callback = $this->detailPidDeterminationCallbacks[$determinationMethod]) {
                    if ($detailPid = call_user_func([$this, $callback], $tsSettings, $newsItem)) {
                        break;
                    }
                }
            }

            if (!$detailPid && isset($GLOBALS['TSFE'])) {
                $detailPid = $GLOBALS['TSFE']->id;
            }
            $configuration['parameter'] = $detailPid;
        }

        $configuration['additionalParams'] = (isset($configuration['additionalParams']) ? $configuration['additionalParams'] : '') . '&tx_news_pi1[news]=' . $this->getNewsId($newsItem);
        $configuration['additionalParams'] .= '&tx_news_pi1[controller]=News&tx_news_pi1[action]=detail';

        // Add date as human readable
        if (isset($tsSettings['link']['hrDate']) && $tsSettings['link']['hrDate'] == 1 || isset($tsSettings['link']['hrDate']['_typoScriptNodeValue']) && $tsSettings['link']['hrDate']['_typoScriptNodeValue'] == 1) {
            $dateTime = $newsItem->getDatetime();

            if (!is_null($dateTime)) {
                if (!empty($tsSettings['link']['hrDate']['day'])) {
                    $configuration['additionalParams'] .= '&tx_news_pi1[day]=' . $dateTime->format($tsSettings['link']['hrDate']['day']);
                }
                if (!empty($tsSettings['link']['hrDate']['month'])) {
                    $configuration['additionalParams'] .= '&tx_news_pi1[month]=' . $dateTime->format($tsSettings['link']['hrDate']['month']);
                }
                if (!empty($tsSettings['link']['hrDate']['year'])) {
                    $configuration['additionalParams'] .= '&tx_news_pi1[year]=' . $dateTime->format($tsSettings['link']['hrDate']['year']);
                }
            }
        }
        return $configuration;
    }

    /**
     * @param News $newsItem
     * @return int
     */
    protected function getNewsId(News $newsItem): int
    {
        $uid = $newsItem->getUid();
        // If a user is logged in and not in live workspace
        if (isset($GLOBALS['BE_USER']) && $GLOBALS['BE_USER']->workspace > 0) {
            $record = BackendUtility::getLiveVersionOfRecord(
                'tx_news_domain_model_news',
                $newsItem->getUid()
            );
            if ($record['uid']) {
                $uid = $record['uid'];
            }
        }

        return $uid;
    }

    /**
     * @param array $configuration
     * @return string
     */
    protected function getTargetConfiguration(array $configuration): string
    {
        $configuration['returnLast'] = 'target';

        return $this->cObj->typoLink('dummy', $configuration);
    }

    /**
     * Gets detailPid from categories of the given news item. First will be return.
     *
     * @param array $settings
     * @param News $newsItem
     * @return int
     */
    protected function getDetailPidFromCategories($settings, $newsItem): int
    {
        $detailPid = 0;
        if ($newsItem->getCategories()) {
            foreach ($newsItem->getCategories() as $category) {
                if ($detailPid = (int)$category->getSinglePid()) {
                    break;
                }
            }
        }
        return $detailPid;
    }

    /**
     * Gets detailPid from defaultDetailPid setting
     *
     * @param array $settings
     * @param News $newsItem
     * @return int
     */
    protected function getDetailPidFromDefaultDetailPid($settings, $newsItem): int
    {
        return isset($settings['defaultDetailPid']) ? (int)$settings['defaultDetailPid'] : 0;
    }

    /**
     * Gets detailPid from flexform of current plugin.
     *
     * @param array $settings
     * @param News $newsItem
     * @return int
     */
    protected function getDetailPidFromFlexform($settings, $newsItem): int
    {
        return isset($settings['detailPid']) ? (int)$settings['detailPid'] : 0;
    }

    /**
     * Initialize properties
     *
     * @return void
     */
    protected function init(): void
    {
        $this->cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
    }
}
