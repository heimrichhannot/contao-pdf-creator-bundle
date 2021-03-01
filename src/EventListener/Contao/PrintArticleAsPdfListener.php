<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\PdfCreatorBundle\EventListener\Contao;

use Contao\CoreBundle\Exception\PageNotFoundException;
use Contao\ModuleArticle;
use HeimrichHannot\PdfCreator\Exception\MissingDependenciesException;
use Heimrichhannot\PdfCreatorBundle\Exception\InvalidPdfGeneratorConfigurationException;
use Heimrichhannot\PdfCreatorBundle\Exception\PdfCreatorConfigurationNotFoundException;
use Heimrichhannot\PdfCreatorBundle\Exception\PdfCreatorNotFoundException;
use Heimrichhannot\PdfCreatorBundle\Generator\PdfGenerator;
use Heimrichhannot\PdfCreatorBundle\Generator\PdfGeneratorContext;

class PrintArticleAsPdfListener
{
    /**
     * @var array
     */
    protected $bundleConfig;
    /**
     * @var PdfGenerator
     */
    protected $pdfGenerator;

    public function __construct(array $bundleConfig, PdfGenerator $pdfGenerator)
    {
        $this->bundleConfig = $bundleConfig;
        $this->pdfGenerator = $pdfGenerator;
    }

    public function __invoke(string $articleContent, ModuleArticle $module): void
    {
        if (!isset($this->bundleConfig['enable_contao_article_pdf_syndication']) || true !== $this->bundleConfig['enable_contao_article_pdf_syndication']) {
            return;
        }
        $context = new PdfGeneratorContext($module->title);

        try {
            $this->pdfGenerator->generate($articleContent, $module->pdfConfiguration, $context);
        } catch (InvalidPdfGeneratorConfigurationException | PdfCreatorConfigurationNotFoundException | PdfCreatorNotFoundException | MissingDependenciesException $e) {
            throw new PageNotFoundException('Pdf files could not be generated due invalid configuration.');
        }
    }
}
