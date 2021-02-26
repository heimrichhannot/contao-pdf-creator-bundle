<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\PdfCreatorBundle\EventListener\Contao;

class LoadDataContainerListener
{
    /**
     * @var array
     */
    protected $bundleConfig;

    /**
     * LoadDataContainerListener constructor.
     */
    public function __construct(array $bundleConfig)
    {
        $this->bundleConfig = $bundleConfig;
    }

    public function __invoke(string $table): void
    {
    }

    public function prepareArticleTable(string $table)
    {
        if (isset($this->bundleConfig['enable_contao_article_pdf_syndication']) && true === $this->bundleConfig['enable_contao_article_pdf_syndication']) {
        }
    }
}
