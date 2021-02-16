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
use HeimrichHannot\SyndicationTypeBundle\SyndicationType\AbstractSyndicationType;
use HeimrichHannot\SyndicationTypeBundle\SyndicationType\ExportSyndicationTypeInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

class PdfCreatorSyndicationType extends AbstractSyndicationType implements ExportSyndicationTypeInterface
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
        return 'syndicationPdfCreatorConfig,syndicationPdfCreatorTemplate';
    }

    public function generate(SyndicationContext $context): SyndicationLink
    {
        return $this->linkFactory->create(
            ['application pdf'],
            $this->appendGetParameterToUrl($context->getUrl(), static::PARAM, $context->getData()['id']),
            $this->translator->trans('huh.syndication_type.types.pdf.title'),
            [
                'class' => 'pdf',
                'title' => $this->translator->trans('huh.syndication_type.types.pdf.title'),
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
        $template = new FrontendTemplate($context->getConfiguration()['syndicationPdfCreatorTemplate']);
        $template->setData($context->getData());

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
            $context->getConfiguration()['syndicationPdfCreatorConfig'],
            new PdfGeneratorContext($context->getTitle())
        );
    }
}
