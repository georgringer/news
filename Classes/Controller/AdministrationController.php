<?php

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Controller;

use GeorgRinger\News\Domain\Repository\AdministrationRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Information\Typo3Version;

class AdministrationController extends NewsController
{
    /** @var AdministrationRepository */
    protected $administrationRepository;

    /** @var ModuleTemplateFactory */
    protected $moduleTemplateFactory;

    public function injectAdministrationRepository(AdministrationRepository $administrationRepository)
    {
        $this->administrationRepository = $administrationRepository;
    }

    public function injectModuleTemplateFactory(ModuleTemplateFactory $moduleTemplateFactory)
    {
        $this->moduleTemplateFactory = $moduleTemplateFactory;
    }

    public function indexAction(): ResponseInterface
    {
        $this->view->assignMultiple([
            'counts' => $this->administrationRepository->getTotalCounts(),
        ]);

        if ((new Typo3Version())->getMajorVersion() >= 13) {
            $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
            return $moduleTemplate->renderResponse('Administration/IndexV13');
        }

        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->setContent($this->view->render());
        return $this->htmlResponse($moduleTemplate->renderContent());
    }
}
