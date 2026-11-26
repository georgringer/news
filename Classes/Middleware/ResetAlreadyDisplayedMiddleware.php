<?php

declare(strict_types=1);

/*
 * This file is part of the "news" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace GeorgRinger\News\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * $GLOBALS['EXT']['news']['alreadyDisplayed'] tracks news UIDs already shown
 * on the current page (see ExcludeDisplayedNewsViewHelper and the
 * excludeAlreadyDisplayedNews demand in NewsRepository). It is a plain PHP
 * array, not request-scoped state.
 *
 * Under PHP-FPM (and any other SAPI that reuses one PHP process for several
 * requests) this array is never cleared between requests. Every news item
 * ever rendered via a list using excludeAlreadyDisplayedNews accumulates in
 * it for the lifetime of the worker process: the resulting NOT IN (...)
 * condition keeps growing, and on a site with a few thousand news records
 * this eventually exhausts the configured memory_limit with a hard-to-debug
 * "Allowed memory size exhausted" fatal error that has nothing to do with
 * the page actually being rendered at that point.
 *
 * Resetting the array at the start of every request keeps the intended
 * behaviour (no duplicate news within one page) while removing the
 * cross-request growth.
 */
class ResetAlreadyDisplayedMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $GLOBALS['EXT']['news']['alreadyDisplayed'] = [];

        return $handler->handle($request);
    }
}
