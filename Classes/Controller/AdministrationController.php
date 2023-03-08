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
use TYPO3\CMS\Backend\View\BackendTemplateView;

/**
 * Administration controller
 */
class AdministrationController extends NewsController
{
    /** @var string */
    protected $defaultViewObjectName = BackendTemplateView::class;

    /**
     * @var \GeorgRinger\News\Domain\Repository\AdministrationRepository
     */
    protected $administrationRepository;

    public function injectAdministrationRepository(AdministrationRepository $administrationRepository)
    {
        $this->administrationRepository = $administrationRepository;
    }

    public function indexAction(): ResponseInterface
    {
        $this->view->assignMultiple([
            'counts' => $this->administrationRepository->getTotalCounts(),
        ]);
        return $this->htmlResponse();
    }
}
