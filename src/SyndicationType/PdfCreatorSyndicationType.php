<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\PdfCreatorBundle\SyndicationType;

use Contao\FrontendTemplate;
use Heimrichhannot\PdfCreatorBundle\Generator\PdfGenerator;
use Heimrichhannot\PdfCreatorBundle\Generator\PdfGeneratorContext;
use HeimrichHannot\SyndicationTypeBundle\SyndicationContext\SyndicationContext;
use HeimrichHannot\SyndicationTypeBundle\SyndicationLink\SyndicationLink;
use HeimrichHannot\SyndicationTypeBundle\SyndicationLink\SyndicationLinkFactory;
use HeimrichHannot\SyndicationTypeBundle\SyndicationType\AbstractExportSyndicationType;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\TranslatorInterface;

class PdfCreatorSyndicationType extends AbstractExportSyndicationType
{
    const PARAM = 'pdf';

    /**
     * @var SyndicationLinkFactory
     */
    protected $linkFactory;
    /**
     * @var TranslatorInterface
     */
    protected $translator;
    /**
     * @var RequestStack
     */
    protected $requestStack;
    /**
     * @var PdfGenerator
     */
    protected $pdfGenerator;

    public function __construct(SyndicationLinkFactory $linkFactory, TranslatorInterface $translator, RequestStack $requestStack, PdfGenerator $pdfGenerator)
    {
        $this->linkFactory = $linkFactory;
        $this->translator = $translator;
        $this->requestStack = $requestStack;
        $this->pdfGenerator = $pdfGenerator;
    }

    public static function getType(): string
    {
        return 'pdf_creator';
    }

    public function getPalette(): string
    {
        return 'synPdfCreatorConfig,synPdfCreatorTemplate';
    }

    public function generate(SyndicationContext $context): SyndicationLink
    {
        return $this->linkFactory->create(
            ['application pdf'],
            $this->appendGetParameterToUrl($context->getUrl(), static::PARAM, $context->getData()['id']),
            $this->translator->trans('huh.pdf_creator.syndication_type.title'),
            [
                'class' => 'pdf',
                'title' => $this->translator->trans('huh.pdf_creator.syndication_type.title'),
            ],
            $this
        );
    }

    public function shouldExport(SyndicationContext $context): bool
    {
        return $context->getData()['id'] == $this->requestStack->getMasterRequest()->get(static::PARAM);
    }

    public function export(SyndicationContext $context): void
    {
        $template = new FrontendTemplate($context->getConfiguration()['synPdfCreatorTemplate']);
        $template->setData($context->getData());
        $template->isSyndicationExportTemplate = true;

        if (!isset($context->getData()['title'])) {
            $template->title = $context->getTitle();
        }

        if (!isset($context->getData()['content'])) {
            $template->content = $context->getContent();
        }

        if (!isset($context->getData()['url'])) {
            $template->url = $context->getUrl();
        }

        $this->pdfGenerator->generate(
            $template->getResponse()->getContent(),
            $context->getConfiguration()['synPdfCreatorConfig'],
            new PdfGeneratorContext($context->getTitle())
        );
    }
}
