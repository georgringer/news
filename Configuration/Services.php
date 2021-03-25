<?php
declare(strict_types=1);

use GeorgRinger\News\Backend\FormDataProvider\NewsFlexFormManipulation;
use GeorgRinger\News\Hooks\Backend\RecordListQueryHook;
use GeorgRinger\News\Hooks\BackendUtility;
use GeorgRinger\News\Hooks\ItemsProcFunc;
use GeorgRinger\News\Hooks\PageLayoutView;
use TYPO3\CMS\Core\DependencyInjection;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container, ContainerBuilder $containerBuilder) {
    $containerBuilder->registerForAutoconfiguration(NewsFlexFormManipulation::class)->addTag('news.NewsFlexFormManipulation');
    $containerBuilder->registerForAutoconfiguration(RecordListQueryHook::class)->addTag('news.RecordListQueryHook');
    $containerBuilder->registerForAutoconfiguration(BackendUtility::class)->addTag('news.BackendUtility');
    $containerBuilder->registerForAutoconfiguration(ItemsProcFunc::class)->addTag('news.ItemsProcFunc');
    $containerBuilder->registerForAutoconfiguration(PageLayoutView::class)->addTag('news.PageLayoutView');

    $containerBuilder->addCompilerPass(new DependencyInjection\SingletonPass('news.NewsFlexFormManipulation'));
    $containerBuilder->addCompilerPass(new DependencyInjection\SingletonPass('news.RecordListQueryHook'));
    $containerBuilder->addCompilerPass(new DependencyInjection\SingletonPass('news.BackendUtility'));
    $containerBuilder->addCompilerPass(new DependencyInjection\SingletonPass('news.ItemsProcFunc'));
    $containerBuilder->addCompilerPass(new DependencyInjection\SingletonPass('news.PageLayoutView'));
};
