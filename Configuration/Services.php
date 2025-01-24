<?php

declare(strict_types=1);

use GeorgRinger\News\Hooks\ItemsProcFunc;
use GeorgRinger\News\Hooks\PluginPreviewRenderer;
use GeorgRinger\News\Updates\NewsSlugUpdater;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Core\DependencyInjection\SingletonPass;

return function (ContainerConfigurator $container, ContainerBuilder $containerBuilder): void {
    $containerBuilder->registerForAutoconfiguration(ItemsProcFunc::class)->addTag('news.ItemsProcFunc');
    $containerBuilder->registerForAutoconfiguration(PluginPreviewRenderer::class)->addTag('news.PageLayoutView');
    $containerBuilder->registerForAutoconfiguration(NewsSlugUpdater::class)->addTag('news.NewsSlugUpdater');

    $containerBuilder->addCompilerPass(new SingletonPass('news.NewsFlexFormManipulation'));
    $containerBuilder->addCompilerPass(new SingletonPass('news.RecordListQueryHook'));
    $containerBuilder->addCompilerPass(new SingletonPass('news.ItemsProcFunc'));
    $containerBuilder->addCompilerPass(new SingletonPass('news.PageLayoutView'));
    $containerBuilder->addCompilerPass(new SingletonPass('news.NewsSlugUpdater'));
};
