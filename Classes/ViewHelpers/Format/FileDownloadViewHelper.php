<?php

namespace GeorgRinger\News\ViewHelpers\Format;

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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\TypoScriptService;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * ViewHelper to render a download link of a file using $cObj->filelink()
 * # Example: Basic example
 * <code>
 * <n:format.fileDownload file="uploads/tx_news/{relatedFile.file}" configuration="{settings.relatedFiles.download}">
 *    {relatedFile.title}
 * </n:format.fileDownload>
 * </code>
 * <output>
 *  Link to download the file "uploads/tx_news/{relatedFile.file}"
 * </output>
 *
 */
class FileDownloadViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

    /**
     * Download a file
     *
     * @param string $file Path to the file
     * @param array $configuration configuration used to render the filelink cObject
     * @param bool $hideError define if an error should be displayed if file not found
     *     * @param string $class optional class
     *     * @param string $target target
     *     * @param string $alt alt text
     *     * @param string $title title text
     * @return string
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException
     */
    public function render(
        $file,
        $configuration = [],
        $hideError = false,
        $class = '',
        $target = '',
        $alt = '',
        $title = ''
    ) {
        GeneralUtility::deprecationLog('The ViewHelper "format.fileDownload" of EXT:news is deprecated! Just use the native implementation of FAL');

        if (!is_file($file)) {
            $errorMessage = sprintf('Given file "%s" for %s is not valid', htmlspecialchars($file), get_class());
            GeneralUtility::devLog($errorMessage, 'news',
                GeneralUtility::SYSLOG_SEVERITY_WARNING);

            if (!$hideError) {
                throw new \TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException(
                    'Given file is not a valid file: ' . htmlspecialchars($file));
            }
        }

        $cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);

        $fileInformation = pathinfo($file);
        $fileInformation['file'] = $file;
        $fileInformation['size'] = filesize($file);
        $cObj->data = $fileInformation;

        // set a basic configuration for cObj->filelink
        $tsConfiguration = [
            'path' => $fileInformation['dirname'] . '/',
            'ATagParams' => 'class="download-link basic-class ' . strtolower($fileInformation['extension']) . (!empty($class) ? ' ' . $class : '') . '"',
            'labelStdWrap.' => [
                'cObject.' => [
                    'value' => $this->renderChildren()
                ]
            ],

        ];

        // Fallback if no configuration given
        if (!is_array($configuration)) {
            $configuration = ['labelStdWrap.' => ['cObject' => 'TEXT']];
        } else {
            /** @var $typoscriptService TypoScriptService */
            $typoscriptService = GeneralUtility::makeInstance(TypoScriptService::class);
            $configuration = $typoscriptService->convertPlainArrayToTypoScriptArray($configuration);
        }

        // merge default configuration with optional configuration
        \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule($tsConfiguration, $configuration);

        if (!empty($target)) {
            $tsConfiguration['target'] = $target;
        }
        if (!empty($alt)) {
            $tsConfiguration['altText'] = $alt;
        }
        if (!empty($title)) {
            $tsConfiguration['title'] = $title;
        }

        // generate link
        $link = $cObj->filelink($fileInformation['basename'], $tsConfiguration);

        return $link;
    }
}
