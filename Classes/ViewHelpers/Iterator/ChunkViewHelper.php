<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\ViewHelpers\Iterator;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\Variables\VariableProviderInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Exception;

/**
 * Creates chunks from an input Array/Traversable with option to allocate items to a fixed number of chunks
 *
 * thanks to Claus Due and the EXT:vhs where this has been copied from
 */
class ChunkViewHelper extends AbstractViewHelper
{
    /** @var bool */
    protected $escapeChildren = false;

    /** @var bool */
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        $this->registerArgument('subject', 'mixed', 'The subject Traversable/Array instance to shift');
        $this->registerArgument('count', 'integer', 'Number of items/chunk or if fixed then number of chunks', true);
        $this->registerArgument('as', 'string', 'Template variable name to assign; if not specified the ViewHelper returns the variable instead.');
        $this->registerArgument(
            'fixed',
            'boolean',
            'If true, creates $count chunks instead of $count values per chunk',
            false,
            false
        );
        $this->registerArgument(
            'preserveKeys',
            'boolean',
            'If set to true, the original array keys will be preserved',
            false,
            false
        );
    }

    /**
     * @return array|mixed
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        $count = (int)$arguments['count'];
        $fixed = (bool)($arguments['fixed'] ?? false);
        $preserveKeys = (bool)($arguments['preserveKeys'] ?? false);
        $subject = static::arrayFromArrayOrTraversableOrCSVStatic(
            empty($arguments['as']) ? ($arguments['subject'] ?? $renderChildrenClosure()) : $arguments['subject'],
            $preserveKeys
        );
        $output = [];
        if ($count <= 0) {
            return $output;
        }
        if ($fixed) {
            $subjectSize = count($subject);
            if ($subjectSize > 0) {
                $chunkSize = (int)ceil($subjectSize / $count);

                $output = array_chunk($subject, $chunkSize, $preserveKeys);
            }
            // Fill the resulting array with empty items to get the desired element count
            $elementCount = count($output);
            if ($elementCount < $count) {
                $output += array_fill($elementCount, $count - $elementCount, null);
            }
        } else {
            $output = array_chunk($subject, $count, $preserveKeys);
        }

        return static::renderChildrenWithVariableOrReturnInputStatic(
            $output,
            $arguments['as'],
            $renderingContext,
            $renderChildrenClosure
        );
    }

    /**
     * @param bool $useKeys
     * @throws Exception
     */
    protected static function arrayFromArrayOrTraversableOrCSVStatic(mixed $candidate, $useKeys = true): array
    {
        if ($candidate instanceof \Traversable) {
            return iterator_to_array($candidate, $useKeys);
        }
        if ($candidate instanceof QueryResultInterface) {
            return $candidate->toArray();
        }
        if (is_string($candidate)) {
            return GeneralUtility::trimExplode(',', $candidate, true);
        }
        if (is_array($candidate)) {
            return $candidate;
        }
        throw new Exception('Unsupported input type; cannot convert to array!', 1588049231);
    }

    /**
     * @param string $as
     * @return mixed
     */
    protected static function renderChildrenWithVariableOrReturnInputStatic(
        mixed $variable,
        $as,
        RenderingContextInterface $renderingContext,
        \Closure $renderChildrenClosure
    ) {
        if (empty($as) === true) {
            return $variable;
        }
        $variables = [$as => $variable];

        return static::renderChildrenWithVariablesStatic(
            $variables,
            $renderingContext->getVariableProvider(),
            $renderChildrenClosure
        );
    }

    /**
     * Renders tag content of ViewHelper and inserts variables
     * in $variables into $variableContainer while keeping backups
     * of each existing variable, restoring it after rendering.
     * Returns the output of the renderChildren() method on $viewHelper.
     *
     * @param VariableProviderInterface $templateVariableContainer
     * @param \Closure $renderChildrenClosure
     * @return mixed
     */
    protected static function renderChildrenWithVariablesStatic(
        array $variables,
        $templateVariableContainer,
        $renderChildrenClosure
    ) {
        $backups = static::backupVariables($variables, $templateVariableContainer);
        $content = $renderChildrenClosure();
        static::restoreVariables($variables, $backups, $templateVariableContainer);
        return $content;
    }

    private static function backupVariables(array $variables, VariableProviderInterface $templateVariableContainer): array
    {
        $backups = [];
        foreach ($variables as $variableName => $variableValue) {
            if ($templateVariableContainer->exists($variableName)) {
                $backups[$variableName] = $templateVariableContainer->get($variableName);
                $templateVariableContainer->remove($variableName);
            }
            $templateVariableContainer->add($variableName, $variableValue);
        }
        return $backups;
    }

    private static function restoreVariables(array $variables, array $backups, VariableProviderInterface $templateVariableContainer): void
    {
        foreach ($variables as $variableName => $variableValue) {
            $templateVariableContainer->remove($variableName);
            if (isset($backups[$variableName]) === true) {
                $templateVariableContainer->add($variableName, $variableValue);
            }
        }
    }
}
