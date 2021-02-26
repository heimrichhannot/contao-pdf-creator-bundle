<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\PdfCreatorBundle\EventListener\Contao;

use Contao\ModuleArticle;

class PrintArticleAsPdfListener
{
    public function __invoke(string $articleContent, ModuleArticle $module): void
    {
    }
}
