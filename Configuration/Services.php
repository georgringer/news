<?php
declare(strict_types=1);

use GeorgRinger\News\Backend\FormDataProvider\NewsFlexFormManipulation;
use GeorgRinger\News\Hooks\Backend\RecordListQueryHook;
use GeorgRinger\News\Hooks\BackendUtility;
use GeorgRinger\News\Hooks\ItemsProcFunc;
use GeorgRinger\News\Hooks\PageLayoutView;
use GeorgRinger\News\Updates\NewsSlugUpdater;
use GeorgRinger\News\Updates\RealurlAliasNewsSlugUpdater;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Core\DependencyInjection;

return function (ContainerConfigurator $container, ContainerBuilder $containerBuilder) {
    $containerBuilder->registerForAutoconfiguration(NewsFlexFormManipulation::class)->addTag('news.NewsFlexFormManipulation');
    $containerBuilder->registerForAutoconfiguration(RecordListQueryHook::class)->addTag('news.RecordListQueryHook');
    $containerBuilder->registerForAutoconfiguration(BackendUtility::class)->addTag('news.BackendUtility');
    $containerBuilder->registerForAutoconfiguration(ItemsProcFunc::class)->addTag('news.ItemsProcFunc');
    $containerBuilder->registerForAutoconfiguration(PageLayoutView::class)->addTag('news.PageLayoutView');
    $containerBuilder->registerForAutoconfiguration(NewsSlugUpdater::class)->addTag('news.NewsSlugUpdater');
    $containerBuilder->registerForAutoconfiguration(RealurlAliasNewsSlugUpdater::class)->addTag('news.RealurlAliasNewsSlugUpdater');

    $containerBuilder->addCompilerPass(new DependencyInjection\SingletonPass('news.NewsFlexFormManipulation'));
    $containerBuilder->addCompilerPass(new DependencyInjection\SingletonPass('news.RecordListQueryHook'));
    $containerBuilder->addCompilerPass(new DependencyInjection\SingletonPass('news.BackendUtility'));
    $containerBuilder->addCompilerPass(new DependencyInjection\SingletonPass('news.ItemsProcFunc'));
    $containerBuilder->addCompilerPass(new DependencyInjection\SingletonPass('news.PageLayoutView'));
    $containerBuilder->addCompilerPass(new DependencyInjection\SingletonPass('news.NewsSlugUpdater'));
    $containerBuilder->addCompilerPass(new DependencyInjection\SingletonPass('news.RealurlAliasNewsSlugUpdater'));
};
