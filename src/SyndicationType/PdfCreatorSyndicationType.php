<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\PdfCreatorBundle\SyndicationType;

use Heimrichhannot\PdfCreatorBundle\Generator\PdfGenerator;
use HeimrichHannot\SyndicationTypeBundle\SyndicationLink\SyndicationLink;
use HeimrichHannot\SyndicationTypeBundle\SyndicationLink\SyndicationLinkContext;
use HeimrichHannot\SyndicationTypeBundle\SyndicationLink\SyndicationLinkFactory;
use HeimrichHannot\SyndicationTypeBundle\SyndicationType\AbstractSyndicationType;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

class PdfCreatorSyndicationType extends AbstractSyndicationType
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

    public function generate(SyndicationLinkContext $context): SyndicationLink
    {
        if ($context->getConfiguration()['id'] == $this->requestStack->getMasterRequest()->get(static::PARAM)) {
//            $this->pdfGenerator->generate($context->);
        }

        $this->linkFactory->create(
            ['application pdf'],
            $this->appendGetParameterToUrl($context->getUrl(), static::PARAM, $context->getConfiguration()['id']),
            $this->translator->trans('huh.syndication_type.types.pdf.title'),
            [
                'class' => 'pdf',
                'title' => $this->translator->trans('huh.syndication_type.types.pdf.title'),
            ],
            $this
        );
    }
}
