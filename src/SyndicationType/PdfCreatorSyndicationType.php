<?php

/*
 * Copyright (c) 2022 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\PdfCreatorBundle\SyndicationType;

use Contao\Config;
use Contao\Environment;
use Contao\StringUtil;
use HeimrichHannot\EncoreBundle\Asset\EntrypointCollectionFactory;
use HeimrichHannot\EncoreBundle\Asset\TemplateAssetGenerator;
use Heimrichhannot\PdfCreatorBundle\Exception\PdfCreatorConfigurationNotFoundException;
use Heimrichhannot\PdfCreatorBundle\Generator\PdfGenerator;
use Heimrichhannot\PdfCreatorBundle\Generator\PdfGeneratorContext;
use HeimrichHannot\SyndicationTypeBundle\SyndicationContext\SyndicationContext;
use HeimrichHannot\SyndicationTypeBundle\SyndicationLink\SyndicationLink;
use HeimrichHannot\SyndicationTypeBundle\SyndicationLink\SyndicationLinkFactory;
use HeimrichHannot\SyndicationTypeBundle\SyndicationType\AbstractExportSyndicationType;
use HeimrichHannot\TwigSupportBundle\Template\TwigFrontendTemplate;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class PdfCreatorSyndicationType extends AbstractExportSyndicationType implements ServiceSubscriberInterface
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
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(
        ContainerInterface     $container,
        SyndicationLinkFactory $linkFactory,
        TranslatorInterface    $translator,
        RequestStack           $requestStack,
        PdfGenerator           $pdfGenerator
    ) {
        $this->linkFactory = $linkFactory;
        $this->translator = $translator;
        $this->requestStack = $requestStack;
        $this->pdfGenerator = $pdfGenerator;
        $this->container = $container;
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
        $template = new TwigFrontendTemplate($context->getConfiguration()['synPdfCreatorTemplate']);

        $configuration = $this->pdfGenerator->getConfiguration($context->getConfiguration()['synPdfCreatorConfig']);

        if (!$configuration) {
            throw new PdfCreatorConfigurationNotFoundException((int) $configuration);
        }

        $data = $context->getData();
        $data['isRTL'] = 'rtl' === $GLOBALS['TL_LANG']['MSC']['textDirection'];
        $data['language'] = $GLOBALS['TL_LANGUAGE'];
        $data['charset'] = Config::get('characterSet');

        if ($configuration->baseUrl) {
            $data['base'] = $configuration->baseUrl;
        } else {
            $data['base'] = Environment::get('base');
        }
        $template->setData($data);
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

        if ($this->container->has('HeimrichHannot\EncoreBundle\Asset\EntrypointCollectionFactory')) {
            $useEncore = (bool) $context->getConfiguration()['synPdfCreatorUseCustomEncoreEntries'] ?? false;

            if ($useEncore && !empty(($entrypoints = array_filter(StringUtil::deserialize($context->getConfiguration()['synPdfCreatorCustomEncoreEntries'], true))))) {
                $collection = $this->container->get(EntrypointCollectionFactory::class)->createCollection($entrypoints);
                $template->stylesheets = $this->container->get(TemplateAssetGenerator::class)->linkTags($collection);
                $template->headJavaScript = $this->container->get(TemplateAssetGenerator::class)->headScriptTags($collection);
                $template->javaScript = $this->container->get(TemplateAssetGenerator::class)->scriptTags($collection);
            }
        }

        $this->pdfGenerator->generate(
            $template->getResponse()->getContent(),
            $context->getConfiguration()['synPdfCreatorConfig'],
            new PdfGeneratorContext($context->getTitle())
        );
    }

    public static function getSubscribedServices()
    {
        return [
            '?HeimrichHannot\EncoreBundle\Asset\EntrypointCollectionFactory',
            '?HeimrichHannot\EncoreBundle\Asset\TemplateAssetGenerator',
        ];
    }
}