<?php

declare(strict_types=1);

namespace GeorgRinger\News\Hooks;

use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Page\PageRenderer;

/**
 * Render preview of EXT:news_content_elements
 */
class NewsContentElementPreview
{
    public function __construct(
        protected IconFactory $iconFactory,
        protected PageRenderer $pageRenderer,
    ) {}

    public function run(array $params): string
    {
        $this->pageRenderer->loadJavaScriptModule('@georgringer/news/NewsContentElementPreview.js');
        $languageService = $this->getLanguageService();

        $labels = [];
        foreach (['title', 'lead', 'line1', 'line2', 'line3', 'line4', 'line5', 'line6', 'link'] as $key) {
            $identifier = 'newsContentElement.preview.modal.' . $key;
            $labels[$identifier] = $languageService->sL('LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:' . $identifier);
        }
        $this->pageRenderer->addInlineLanguageLabelArray($labels);

        return sprintf(
            '<button type="button" class="btn btn-info" data-news-content-element-preview="1">%s %s</button>',
            $this->iconFactory->getIcon('ext-news-addon', Icon::SIZE_SMALL),
            $labels['newsContentElement.preview.modal.title'],
        );
    }

    private function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
