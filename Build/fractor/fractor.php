<?php

declare(strict_types=1);

use a9f\Fractor\Configuration\FractorConfiguration;
use a9f\Fractor\ValueObject\Indent;
use a9f\FractorTypoScript\Configuration\TypoScriptProcessorOption;
use a9f\FractorXml\Configuration\XmlProcessorOption;
use a9f\Typo3Fractor\Set\Typo3LevelSetList;

return FractorConfiguration::configure()
    ->withPaths([
        __DIR__ . '/../../Classes',
        __DIR__ . '/../../Configuration/',
        __DIR__ . '/../../Resources',
        __DIR__ . '/../../composer.json',
    ])
    ->withSets([
        Typo3LevelSetList::UP_TO_TYPO3_12,
    ])
    ->withOptions([
        TypoScriptProcessorOption::INDENT_CHARACTER => 'auto', // this detects the indent from the file and keeps it
        TypoScriptProcessorOption::ADD_CLOSING_GLOBAL => false,
        TypoScriptProcessorOption::INCLUDE_EMPTY_LINE_BREAKS => true,
        TypoScriptProcessorOption::INDENT_CONDITIONS => true,
        XmlProcessorOption::INDENT_CHARACTER => Indent::STYLE_TAB,
        XmlProcessorOption::INDENT_SIZE => 1,
    ])
    ->withSkip([
        __DIR__ . '/../../Configuration/FlexForms/flexform_category_list.xml',
        __DIR__ . '/../../Resources/Private/Templates/News/GoogleNews.xml',
        __DIR__ . '/../../Resources/Private/Templates/News/List.xml',
    ]);
