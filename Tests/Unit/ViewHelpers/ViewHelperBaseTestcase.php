<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Georg Ringer <typo3@ringerge.org>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Base view helper test case
 *
 * @package TYPO3
 * @subpackage tx_news2
 */
abstract class Tx_News2_ViewHelpers_ViewHelperBaseTestcase extends Tx_Extbase_Tests_Unit_BaseTestCase {

	/**
	 * @var Tx_Fluid_Core_ViewHelper_ViewHelperVariableContainer
	 */
	protected $viewHelperVariableContainer;

	/**
	 * @var Tx_Fluid_Core_ViewHelper_TemplateVariableContainer
	 */
	protected $templateVariableContainer;

	/**
	 * @var Tx_Extbase_MVC_Web_Routing_UriBuilder
	 */
	protected $uriBuilder;

	/**
	 * @var Tx_Extbase_MVC_Controller_ControllerContext
	 */
	protected $controllerContext;

	/**
	 * @var Tx_Fluid_Core_ViewHelper_TagBuilder
	 */
	protected $tagBuilder;

	/**
	 * @var Tx_Fluid_Core_ViewHelper_Arguments
	 */
	protected $arguments;

	/**
	 * @var Tx_Extbase_MVC_Web_Request
	 */
	protected $request;

	/**
	 * @var Tx_Fluid_Core_Rendering_RenderingContext
	 */
	protected $renderingContext;

	/**
	 * @return void
	 */
	public function setUp() {
		$this->viewHelperVariableContainer = $this->getMock('Tx_Fluid_Core_ViewHelper_ViewHelperVariableContainer');
		$this->templateVariableContainer = $this->getMock('Tx_Fluid_Core_ViewHelper_TemplateVariableContainer');
		$this->uriBuilder = $this->getMock('Tx_Extbase_MVC_Web_Routing_UriBuilder');
		$this->uriBuilder->expects($this->any())->method('reset')->will($this->returnValue($this->uriBuilder));
		$this->uriBuilder->expects($this->any())->method('setArguments')->will($this->returnValue($this->uriBuilder));
		$this->uriBuilder->expects($this->any())->method('setSection')->will($this->returnValue($this->uriBuilder));
		$this->uriBuilder->expects($this->any())->method('setFormat')->will($this->returnValue($this->uriBuilder));
		$this->uriBuilder->expects($this->any())->method('setCreateAbsoluteUri')->will($this->returnValue($this->uriBuilder));
		$this->uriBuilder->expects($this->any())->method('setAddQueryString')->will($this->returnValue($this->uriBuilder));
		$this->uriBuilder->expects($this->any())->method('setArgumentsToBeExcludedFromQueryString')->will($this->returnValue($this->uriBuilder));
		$this->uriBuilder->expects($this->any())->method('setLinkAccessRestrictedPages')->will($this->returnValue($this->uriBuilder));
		$this->uriBuilder->expects($this->any())->method('setTargetPageUid')->will($this->returnValue($this->uriBuilder));
		$this->uriBuilder->expects($this->any())->method('setTargetPageType')->will($this->returnValue($this->uriBuilder));
		$this->uriBuilder->expects($this->any())->method('setNoCache')->will($this->returnValue($this->uriBuilder));
		$this->uriBuilder->expects($this->any())->method('setUseCacheHash')->will($this->returnValue($this->uriBuilder));
		$this->request = $this->getMock('Tx_Extbase_MVC_Web_Request');
		$this->controllerContext = $this->getMock('Tx_Extbase_MVC_Controller_ControllerContext', array(), array(), '', FALSE);
		$this->controllerContext->expects($this->any())->method('getUriBuilder')->will($this->returnValue($this->uriBuilder));
		$this->controllerContext->expects($this->any())->method('getRequest')->will($this->returnValue($this->request));
		$this->tagBuilder = $this->getMock('Tx_Fluid_Core_ViewHelper_TagBuilder');
		$this->arguments = $this->getMock('Tx_Fluid_Core_ViewHelper_Arguments', array(), array(), '', FALSE);
		$this->renderingContext = $this->getMock('Tx_Fluid_Core_Rendering_RenderingContext');
	}

	/**
	 * Inject dependencies
	 *
	 * @param Tx_Fluid_Core_ViewHelper_AbstractViewHelper $viewHelper
	 * @return void
	 */
	protected function injectDependenciesIntoViewHelper(Tx_Fluid_Core_ViewHelper_AbstractViewHelper $viewHelper) {
		$viewHelper->setViewHelperVariableContainer($this->viewHelperVariableContainer);
		$viewHelper->setTemplateVariableContainer($this->templateVariableContainer);
		$viewHelper->setControllerContext($this->controllerContext);
		$viewHelper->setRenderingContext($this->renderingContext);
		$viewHelper->setArguments($this->arguments);
		if ($viewHelper instanceof Tx_Fluid_Core_ViewHelper_AbstractTagBasedViewHelper) {
			$viewHelper->_set('tag', $this->tagBuilder);
		}
	}
}
?>