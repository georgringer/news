<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\ViewHelpers;

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\MvcPropertyMappingConfigurationService;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Security\Cryptography\HashService;
use TYPO3\CMS\Extbase\Service\ExtensionService;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContext;
use TYPO3\CMS\Fluid\ViewHelpers\Form\AbstractFormViewHelper;
use TYPO3\CMS\Fluid\ViewHelpers\Form\CheckboxViewHelper;

class SearchFormViewHelper extends AbstractFormViewHelper
{
    /**
     * @var string
     */
    protected $tagName = 'form';

    /**
     * @var \TYPO3\CMS\Extbase\Security\Cryptography\HashService
     */
    protected $hashService;

    /**
     * @var \TYPO3\CMS\Extbase\Mvc\Controller\MvcPropertyMappingConfigurationService
     */
    protected $mvcPropertyMappingConfigurationService;

    /**
     * @var \TYPO3\CMS\Extbase\Service\ExtensionService
     */
    protected $extensionService;

    /**
     * We need the arguments of the formActionUri on requesthash calculation
     * therefore we will store them in here right after calling uriBuilder
     *
     * @var array
     */
    protected $formActionUriArguments;

    /**
     * @param \TYPO3\CMS\Extbase\Security\Cryptography\HashService $hashService
     */
    public function injectHashService(HashService $hashService)
    {
        $this->hashService = $hashService;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Mvc\Controller\MvcPropertyMappingConfigurationService $mvcPropertyMappingConfigurationService
     */
    public function injectMvcPropertyMappingConfigurationService(MvcPropertyMappingConfigurationService $mvcPropertyMappingConfigurationService)
    {
        $this->mvcPropertyMappingConfigurationService = $mvcPropertyMappingConfigurationService;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Service\ExtensionService $extensionService
     */
    public function injectExtensionService(ExtensionService $extensionService)
    {
        $this->extensionService = $extensionService;
    }

    /**
     * Initialize arguments.
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('action', 'string', 'Target action');
        $this->registerArgument('arguments', 'array', 'Arguments', false, []);
        $this->registerArgument('controller', 'string', 'Target controller');
        $this->registerArgument('extensionName', 'string', 'Target Extension Name (without "tx_" prefix and no underscores). If NULL the current extension name is used');
        $this->registerArgument('pluginName', 'string', 'Target plugin. If empty, the current plugin name is used');
        $this->registerArgument('pageUid', 'int', 'Target page uid');
        $this->registerArgument('object', 'mixed', 'Object to use for the form. Use in conjunction with the "property" attribute on the sub tags');
        $this->registerArgument('pageType', 'int', 'Target page type', false, 0);
        $this->registerArgument('noCache', 'bool', 'set this to disable caching for the target page. You should not need this.', false, false);
        $this->registerArgument('section', 'string', 'The anchor to be added to the action URI (only active if $actionUri is not set)', false, '');
        $this->registerArgument('format', 'string', 'The requested format (e.g. ".html") of the target page (only active if $actionUri is not set)', false, '');
        $this->registerArgument('additionalParams', 'array', 'additional action URI query parameters that won\'t be prefixed like $arguments (overrule $arguments) (only active if $actionUri is not set)', false, []);
        $this->registerArgument('absolute', 'bool', 'If set, an absolute action URI is rendered (only active if $actionUri is not set)', false, false);
        $this->registerArgument('addQueryString', 'bool', 'If set, the current query parameters will be kept in the action URI (only active if $actionUri is not set)', false, false);
        $this->registerArgument('argumentsToBeExcludedFromQueryString', 'array', 'arguments to be removed from the action URI. Only active if $addQueryString = TRUE and $actionUri is not set', false, []);
        $this->registerArgument('addQueryStringMethod', 'string', 'This argument is not evaluated anymore and will be removed in TYPO3 v12.');
        $this->registerArgument('fieldNamePrefix', 'string', 'Prefix that will be added to all field names within this form. If not set the prefix will be tx_yourExtension_plugin');
        $this->registerArgument('actionUri', 'string', 'can be used to overwrite the "action" attribute of the form tag');
        $this->registerArgument('objectName', 'string', 'name of the object that is bound to this form. If this argument is not specified, the name attribute of this form is used to determine the FormObjectName');
        $this->registerTagAttribute('enctype', 'string', 'MIME type with which the form is submitted');
        $this->registerTagAttribute('method', 'string', 'Transfer type (GET or POST)');
        $this->registerTagAttribute('name', 'string', 'Name of form');
        $this->registerTagAttribute('onreset', 'string', 'JavaScript: On reset of the form');
        $this->registerTagAttribute('onsubmit', 'string', 'JavaScript: On submit of the form');
        $this->registerTagAttribute('target', 'string', 'Target attribute of the form');
        $this->registerTagAttribute('novalidate', 'bool', 'Indicate that the form is not to be validated on submit.');
        $this->registerUniversalTagAttributes();
    }

    /**
     * Render the form.
     *
     * @return string rendered form
     */
    public function render()
    {
        $this->setFormActionUri();
        if (isset($this->arguments['method']) && strtolower($this->arguments['method']) === 'get') {
            $this->tag->addAttribute('method', 'get');
        } else {
            $this->tag->addAttribute('method', 'post');
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
    protected function setFormActionUri()
    {
        if ($this->hasArgument('actionUri')) {
            $formActionUri = $this->arguments['actionUri'];
        } else {
            if (isset($this->arguments['addQueryStringMethod'])) {
                trigger_error('Using the argument "addQueryStringMethod" in <f:form> ViewHelper has no effect anymore and will be removed in TYPO3 v12. Remove the argument in your fluid template, as it will result in a fatal error.', E_USER_DEPRECATED);
            }

            $versionInformation = GeneralUtility::makeInstance(Typo3Version::class);
            if ($versionInformation->getMajorVersion() >= 12) {
                /** @var RenderingContext $renderingContext */
                $renderingContext = $this->renderingContext;
                /** @var RequestInterface $request */
                $request = $renderingContext->getRequest();
                $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
                $uriBuilder
                    ->reset()
                    ->setRequest($request);
            } else {
                $uriBuilder = $this->renderingContext->getUriBuilder();
                $uriBuilder->reset();
            }
            $uriBuilder
                ->setTargetPageType($this->arguments['pageType'] ?? 0)
                ->setNoCache($this->arguments['noCache'] ?? false)
                ->setSection($this->arguments['section'] ?? '')
                ->setCreateAbsoluteUri($this->arguments['absolute'] ?? false)
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
    protected function addFormObjectNameToViewHelperVariableContainer()
    {
        $formObjectName = $this->getFormObjectName();
        if ($formObjectName !== null) {
            $this->renderingContext->getViewHelperVariableContainer()->add(\TYPO3\CMS\Fluid\ViewHelpers\FormViewHelper::class, 'formObjectName', $formObjectName);
        }
    }

    /**
     * Removes the form name from the ViewHelperVariableContainer.
     */
    protected function removeFormObjectNameFromViewHelperVariableContainer()
    {
        $formObjectName = $this->getFormObjectName();
        if ($formObjectName !== null) {
            $this->renderingContext->getViewHelperVariableContainer()->remove(\TYPO3\CMS\Fluid\ViewHelpers\FormViewHelper::class, 'formObjectName');
        }
    }

    /**
     * Returns the name of the object that is bound to this form.
     * If the "objectName" argument has been specified, this is returned. Otherwise the name attribute of this form.
     * If neither objectName nor name arguments have been set, NULL is returned.
     *
     * @return string specified Form name or NULL if neither $objectName nor $name arguments have been specified
     */
    protected function getFormObjectName()
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
    protected function addFormObjectToViewHelperVariableContainer()
    {
        if ($this->hasArgument('object')) {
            $viewHelperVariableContainer = $this->renderingContext->getViewHelperVariableContainer();
            $viewHelperVariableContainer->add(\TYPO3\CMS\Fluid\ViewHelpers\FormViewHelper::class, 'formObject', $this->arguments['object']);
            $viewHelperVariableContainer->add(\TYPO3\CMS\Fluid\ViewHelpers\FormViewHelper::class, 'additionalIdentityProperties', []);
        }
    }

    /**
     * Removes the form object from the ViewHelperVariableContainer.
     */
    protected function removeFormObjectFromViewHelperVariableContainer()
    {
        if ($this->hasArgument('object')) {
            $viewHelperVariableContainer = $this->renderingContext->getViewHelperVariableContainer();
            $viewHelperVariableContainer->remove(\TYPO3\CMS\Fluid\ViewHelpers\FormViewHelper::class, 'formObject');
            $viewHelperVariableContainer->remove(\TYPO3\CMS\Fluid\ViewHelpers\FormViewHelper::class, 'additionalIdentityProperties');
        }
    }

    /**
     * Adds the field name prefix to the ViewHelperVariableContainer
     */
    protected function addFieldNamePrefixToViewHelperVariableContainer()
    {
        $fieldNamePrefix = $this->getFieldNamePrefix();
        $this->renderingContext->getViewHelperVariableContainer()->add(\TYPO3\CMS\Fluid\ViewHelpers\FormViewHelper::class, 'fieldNamePrefix', $fieldNamePrefix);
    }

    /**
     * Get the field name prefix
     *
     * @return string
     */
    protected function getFieldNamePrefix()
    {
        if ($this->hasArgument('fieldNamePrefix')) {
            return $this->arguments['fieldNamePrefix'];
        }
        return $this->getDefaultFieldNamePrefix();
    }

    /**
     * Removes field name prefix from the ViewHelperVariableContainer
     */
    protected function removeFieldNamePrefixFromViewHelperVariableContainer()
    {
        $this->renderingContext->getViewHelperVariableContainer()->remove(\TYPO3\CMS\Fluid\ViewHelpers\FormViewHelper::class, 'fieldNamePrefix');
    }

    /**
     * Adds a container for form field names to the ViewHelperVariableContainer
     */
    protected function addFormFieldNamesToViewHelperVariableContainer()
    {
        $this->renderingContext->getViewHelperVariableContainer()->add(\TYPO3\CMS\Fluid\ViewHelpers\FormViewHelper::class, 'formFieldNames', []);
    }

    /**
     * Removes the container for form field names from the ViewHelperVariableContainer
     */
    protected function removeFormFieldNamesFromViewHelperVariableContainer()
    {
        $viewHelperVariableContainer = $this->renderingContext->getViewHelperVariableContainer();
        $viewHelperVariableContainer->remove(\TYPO3\CMS\Fluid\ViewHelpers\FormViewHelper::class, 'formFieldNames');
        if ($viewHelperVariableContainer->exists(\TYPO3\CMS\Fluid\ViewHelpers\FormViewHelper::class, 'renderedHiddenFields')) {
            $viewHelperVariableContainer->remove(\TYPO3\CMS\Fluid\ViewHelpers\FormViewHelper::class, 'renderedHiddenFields');
        }
    }

    /**
     * Add the URI arguments after postprocessing to the request hash as well.
     * @param array $arguments
     * @param array $results
     * @param string $currentPrefix
     * @param int $level
     */
    protected function postProcessUriArgumentsForRequestHash($arguments, &$results, $currentPrefix = '', $level = 0)
    {
        if (count($arguments)) {
            foreach ($arguments as $argumentName => $argumentValue) {
                $argumentName = (string)$argumentName;
                if (is_array($argumentValue)) {
                    $prefix = $level == 0 ? $argumentName : $currentPrefix . '[' . $argumentName . ']';
                    $this->postProcessUriArgumentsForRequestHash($argumentValue, $results, $prefix, $level + 1);
                } else {
                    $results[] = $level == 0 ? $argumentName : $currentPrefix . '[' . $argumentName . ']';
                }
            }
        }
    }

    /**
     * Retrieves the default field name prefix for this form
     *
     * @return string default field name prefix
     */
    protected function getDefaultFieldNamePrefix()
    {
        $versionInformation = GeneralUtility::makeInstance(Typo3Version::class);
        if ($versionInformation->getMajorVersion() >= 11) {
            $request = $this->renderingContext->getRequest();
        } else {
            // @extensionScannerIgnoreLine
            $request = $this->renderingContext->getControllerContext()->getRequest();
        }
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
    protected function removeCheckboxFieldNamesFromViewHelperVariableContainer()
    {
        $viewHelperVariableContainer = $this->renderingContext->getViewHelperVariableContainer();
        if ($viewHelperVariableContainer->exists(CheckboxViewHelper::class, 'checkboxFieldNames')) {
            $viewHelperVariableContainer->remove(CheckboxViewHelper::class, 'checkboxFieldNames');
        }
    }
}
