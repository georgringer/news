<?php
declare(strict_types=1);
namespace GeorgRinger\News\Backend\FieldInformation;

use TYPO3\CMS\Backend\Form\AbstractNode;
use TYPO3\CMS\Lang\LanguageService;

class StaticText extends AbstractNode
{
    /**
     * Handler for single nodes
     *
     * @return array
     */
    public function render(): array
    {
        $languageService = $this->getLanguageService();

        $labels = [];
        foreach ((array)$this->data['renderData']['fieldInformationOptions']['labels'] as $labelConfiguration) {
            $label = htmlspecialchars($languageService->sL($labelConfiguration['label']));
            if (!empty($labelConfiguration['italic'])) {
                $label = '<em>' . $label . '</em>';
            }
            if (!empty($labelConfiguration['bold'])) {
                $label = '<strong>' . $label . '</strong>';
            }
            $labels[] = $label;
        }

        return [
            'requireJsModules' => [
                'TYPO3/CMS/News/TagSuggestWizard',
            ],
            'html' => '<div class="form-control-wrap news-taggable">'
                . implode('<br />', $labels)
                . '</div>',
        ];
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
