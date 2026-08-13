<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\ViewHelpers;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Service\ExtensionService;
use TYPO3\CMS\Fluid\ViewHelpers\Form\AbstractFormViewHelper;
use TYPO3\CMS\Fluid\ViewHelpers\Form\CheckboxViewHelper;
use TYPO3\CMS\Fluid\ViewHelpers\FormViewHelper;

class SearchFormViewHelper extends AbstractFormViewHelper
{
    /** @var string */
    protected $tagName = 'form';

    protected ExtensionService $extensionService;

    /**
     * We need the arguments of the formActionUri on request hash calculation
     * therefore we will store them in here right after calling uriBuilder
     */
    protected array $formActionUriArguments = [];

    public function injectExtensionService(ExtensionService $extensionService): void
    {
        $this->extensionService = $extensionService;
    }

    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('action', 'string', 'Target action');
        $this->registerArgument('arguments', 'array', 'Arguments (do not use reserved keywords "action", "controller" or "format" if not referring to these internal variables specifically)', false, []);
        $this->registerArgument('controller', 'string', 'Target controller');
        $this->registerArgument('extensionName', 'string', 'Target Extension Name (without `tx_` prefix and no underscores). If NULL the current extension name is used');
        $this->registerArgument('pluginName', 'string', 'Target plugin. If empty, the current plugin name is used');
        $this->registerArgument('pageUid', 'int', 'Target page uid');
        $this->registerArgument('object', 'mixed', 'Object to use for the form. Use in conjunction with the "property" attribute on the sub tags');
        $this->registerArgument('pageType', 'int', 'Target page type', false, 0);
        $this->registerArgument('noCache', 'bool', 'set this to disable caching for the target page. You should not need this.', false, false);
        $this->registerArgument('section', 'string', 'The anchor to be added to the action URI (only active if $actionUri is not set)', false, '');
        $this->registerArgument('format', 'string', 'The requested format (e.g. ".html") of the target page (only active if $actionUri is not set)', false, '');
        $this->registerArgument('additionalParams', 'array', 'additional action URI query parameters that won\'t be prefixed like $arguments (overrule $arguments) (only active if $actionUri is not set)', false, []);
        $this->registerArgument('absolute', 'bool', 'If set, an absolute action URI is rendered (only active if $actionUri is not set)', false, false);
        $this->registerArgument('addQueryString', 'string', 'If set, the current query parameters will be kept in the URL. If set to "untrusted", then ALL query parameters will be added. Be aware, that this might lead to problems when the generated link is cached.', false, false);
        $this->registerArgument('argumentsToBeExcludedFromQueryString', 'array', 'arguments to be removed from the action URI. Only active if $addQueryString = TRUE and $actionUri is not set', false, []);
        $this->registerArgument('fieldNamePrefix', 'string', 'Prefix that will be added to all field names within this form. If not set the prefix will be tx_yourExtension_plugin');
        $this->registerArgument('actionUri', 'string', 'can be used to overwrite the "action" attribute of the form tag');
        $this->registerArgument('objectName', 'string', 'name of the object that is bound to this form. If this argument is not specified, the name attribute of this form is used to determine the FormObjectName');
        $this->registerArgument('method', 'string', 'Transfer type (get or post)', false, 'post');
        $this->registerArgument('name', 'string', 'Name of form');
        $this->registerArgument('novalidate', 'bool', 'Indicate that the form is not to be validated on submit.');
    }

    public function render(): string
    {
        $this->setFormActionUri();

        // Force 'method="get"' or 'method="post"', defaulting to "post".
        if (isset($this->arguments['method']) && strtolower($this->arguments['method']) === 'get') {
            $this->tag->addAttribute('method', 'get');
        } else {
            $this->tag->addAttribute('method', 'post');
        }

        if (!empty($this->arguments['name'])) {
            $this->tag->addAttribute('name', $this->arguments['name']);
        }

        if (isset($this->arguments['novalidate']) && $this->arguments['novalidate'] === true) {
            $this->tag->addAttribute('novalidate', 'novalidate');
        }

        $this->addFormObjectNameToViewHelperVariableContainer();
        $this->addFormObjectToViewHelperVariableContainer();
        $this->addFieldNamePrefixToViewHelperVariableContainer();
        $this->addFormFieldNamesToViewHelperVariableContainer();

        $this->tag->setContent($this->renderChildren());
        $this->removeFieldNamePrefixFromViewHelperVariableContainer();
        $this->removeFormObjectFromViewHelperVariableContainer();
        $this->removeFormObjectNameFromViewHelperVariableContainer();
        $this->removeFormFieldNamesFromViewHelperVariableContainer();
        $this->removeCheckboxFieldNamesFromViewHelperVariableContainer();
        return $this->tag->render();
    }

    /**
     * Sets the "action" attribute of the form tag
     */
    protected function setFormActionUri(): void
    {
        if ($this->hasArgument('actionUri')) {
            $formActionUri = $this->arguments['actionUri'];
        } else {
            /** @var RequestInterface $request */
            $request = $this->renderingContext->getAttribute(ServerRequestInterface::class);
            $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
            $uriBuilder
                ->reset()
                ->setRequest($request)
                ->setTargetPageType((int)($this->arguments['pageType'] ?? 0))
                ->setNoCache((bool)($this->arguments['noCache'] ?? false))
                ->setSection($this->arguments['section'] ?? '')
                ->setCreateAbsoluteUri((bool)($this->arguments['absolute'] ?? false))
                ->setArguments(isset($this->arguments['additionalParams']) ? (array)$this->arguments['additionalParams'] : [])
                ->setAddQueryString($this->arguments['addQueryString'] ?? false)
                ->setArgumentsToBeExcludedFromQueryString(isset($this->arguments['argumentsToBeExcludedFromQueryString']) ? (array)$this->arguments['argumentsToBeExcludedFromQueryString'] : [])
                ->setFormat($this->arguments['format'] ?? '')
            ;

            $pageUid = (int)($this->arguments['pageUid'] ?? 0);
            if ($pageUid > 0) {
                $uriBuilder->setTargetPageUid($pageUid);
            }

            $formActionUri = $uriBuilder->uriFor(
                $this->arguments['action'] ?? null,
                $this->arguments['arguments'] ?? [],
                $this->arguments['controller'] ?? null,
                $this->arguments['extensionName'] ?? null,
                $this->arguments['pluginName'] ?? null
            );
            $this->formActionUriArguments = $uriBuilder->getArguments();
        }
        $this->tag->addAttribute('action', $formActionUri);
    }

    /**
     * Adds the form object name to the ViewHelperVariableContainer if "objectName" argument or "name" attribute is specified.
     */
    protected function addFormObjectNameToViewHelperVariableContainer(): void
    {
        $formObjectName = $this->getFormObjectName();
        if ($formObjectName !== null) {
            $this->renderingContext->getViewHelperVariableContainer()->add(FormViewHelper::class, 'formObjectName', $formObjectName);
        }
    }

    /**
     * Removes the form name from the ViewHelperVariableContainer.
     */
    protected function removeFormObjectNameFromViewHelperVariableContainer(): void
    {
        $formObjectName = $this->getFormObjectName();
        if ($formObjectName !== null) {
            $this->renderingContext->getViewHelperVariableContainer()->remove(FormViewHelper::class, 'formObjectName');
        }
    }

    /**
     * Returns the name of the object that is bound to this form.
     * If the "objectName" argument has been specified, this is returned. Otherwise the name attribute of this form.
     * If neither objectName nor name arguments have been set, NULL is returned.
     *
     * @return string specified Form name or NULL if neither $objectName nor $name arguments have been specified
     */
    protected function getFormObjectName(): ?string
    {
        $formObjectName = null;
        if ($this->hasArgument('objectName')) {
            $formObjectName = $this->arguments['objectName'];
        } elseif ($this->hasArgument('name')) {
            $formObjectName = $this->arguments['name'];
        }
        return $formObjectName;
    }

    /**
     * Adds the object that is bound to this form to the ViewHelperVariableContainer if the formObject attribute is specified.
     */
    protected function addFormObjectToViewHelperVariableContainer(): void
    {
        if ($this->hasArgument('object')) {
            $viewHelperVariableContainer = $this->renderingContext->getViewHelperVariableContainer();
            $viewHelperVariableContainer->add(FormViewHelper::class, 'formObject', $this->arguments['object']);
            $viewHelperVariableContainer->add(FormViewHelper::class, 'additionalIdentityProperties', []);
        }
    }

    /**
     * Removes the form object from the ViewHelperVariableContainer.
     */
    protected function removeFormObjectFromViewHelperVariableContainer(): void
    {
        if ($this->hasArgument('object')) {
            $viewHelperVariableContainer = $this->renderingContext->getViewHelperVariableContainer();
            $viewHelperVariableContainer->remove(FormViewHelper::class, 'formObject');
            $viewHelperVariableContainer->remove(FormViewHelper::class, 'additionalIdentityProperties');
        }
    }

    /**
     * Adds the field name prefix to the ViewHelperVariableContainer.
     */
    protected function addFieldNamePrefixToViewHelperVariableContainer(): void
    {
        $fieldNamePrefix = $this->getFieldNamePrefix();
        $this->renderingContext->getViewHelperVariableContainer()->add(FormViewHelper::class, 'fieldNamePrefix', $fieldNamePrefix);
    }

    protected function getFieldNamePrefix(): string
    {
        if ($this->hasArgument('fieldNamePrefix')) {
            return $this->arguments['fieldNamePrefix'];
        }
        return $this->getDefaultFieldNamePrefix();
    }

    /**
     * Removes field name prefix from the ViewHelperVariableContainer.
     */
    protected function removeFieldNamePrefixFromViewHelperVariableContainer(): void
    {
        $this->renderingContext->getViewHelperVariableContainer()->remove(FormViewHelper::class, 'fieldNamePrefix');
    }

    /**
     * Adds a container for form field names to the ViewHelperVariableContainer.
     */
    protected function addFormFieldNamesToViewHelperVariableContainer(): void
    {
        $this->renderingContext->getViewHelperVariableContainer()->add(FormViewHelper::class, 'formFieldNames', []);
    }

    /**
     * Removes the container for form field names from the ViewHelperVariableContainer.
     */
    protected function removeFormFieldNamesFromViewHelperVariableContainer(): void
    {
        $viewHelperVariableContainer = $this->renderingContext->getViewHelperVariableContainer();
        $viewHelperVariableContainer->remove(FormViewHelper::class, 'formFieldNames');
        if ($viewHelperVariableContainer->exists(FormViewHelper::class, 'renderedHiddenFields')) {
            $viewHelperVariableContainer->remove(FormViewHelper::class, 'renderedHiddenFields');
        }
    }

    /**
     * Retrieves the default field name prefix for this form
     */
    protected function getDefaultFieldNamePrefix(): string
    {
        /** @var RequestInterface $request */
        $request = $this->renderingContext->getAttribute(ServerRequestInterface::class);
        if ($this->hasArgument('extensionName')) {
            $extensionName = $this->arguments['extensionName'];
        } else {
            $extensionName = $request->getControllerExtensionName();
        }
        if ($this->hasArgument('pluginName')) {
            $pluginName = $this->arguments['pluginName'];
        } else {
            $pluginName = $request->getPluginName();
        }
        if ($extensionName !== null && $pluginName != null) {
            return $this->extensionService->getPluginNamespace($extensionName, $pluginName);
        }
        return '';
    }

    /**
     * Remove Checkbox field names from ViewHelper variable container, to start from scratch when a new form starts.
     */
    protected function removeCheckboxFieldNamesFromViewHelperVariableContainer(): void
    {
        $viewHelperVariableContainer = $this->renderingContext->getViewHelperVariableContainer();
        if ($viewHelperVariableContainer->exists(CheckboxViewHelper::class, 'checkboxFieldNames')) {
            $viewHelperVariableContainer->remove(CheckboxViewHelper::class, 'checkboxFieldNames');
        }
    }
}
