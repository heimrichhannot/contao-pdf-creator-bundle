<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\PdfCreatorBundle\EventListener\Contao;

use Heimrichhannot\PdfCreatorBundle\Generator\DcaGenerator;

class LoadDataContainerListener
{
    /**
     * @var array
     */
    protected $bundleConfig;
    /**
     * @var DcaGenerator
     */
    protected $dcaGenerator;

    /**
     * LoadDataContainerListener constructor.
     */
    public function __construct(array $bundleConfig, DcaGenerator $dcaGenerator)
    {
        $this->bundleConfig = $bundleConfig;
        $this->dcaGenerator = $dcaGenerator;
    }

    public function __invoke(string $table): void
    {
        switch ($table) {
            case 'tl_article':
                $this->prepareArticleTable($table);

                break;
        }
    }

    public function prepareArticleTable(string $table)
    {
        if (!isset($this->bundleConfig['enable_contao_article_pdf_syndication']) || true !== $this->bundleConfig['enable_contao_article_pdf_syndication']) {
            return;
        }
        $dca = &$GLOBALS['TL_DCA']['tl_article'];

        $dca['fields']['printable']['options'][] = 'pdf';

        $dca['palettes']['default'] = str_replace('printable', 'printable,pdfConfiguration', $dca['palettes']['default']);

        $dca['fields']['pdfConfiguration'] = $this->dcaGenerator->getPdfCreatorConfigSelectFieldConfig();
    }
}
