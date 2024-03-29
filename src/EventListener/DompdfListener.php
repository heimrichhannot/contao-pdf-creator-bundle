<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\PdfCreatorBundle\EventListener;

use Dompdf\Dompdf;
use Dompdf\Options;
use HeimrichHannot\PdfCreator\Concrete\DompdfCreator;
use Heimrichhannot\PdfCreatorBundle\Event\BeforeCreateLibraryInstanceEvent;
use Heimrichhannot\PdfCreatorBundle\Event\BeforeOutputPdfCallbackEvent;
use Heimrichhannot\PdfCreatorBundle\Exception\InvalidPdfGeneratorConfigurationException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Add additional configuration for dompdf library.
 *
 * Class DompdfSubscriber
 */
class DompdfListener implements EventSubscriberInterface
{
    protected KernelInterface $kernel;
    private ParameterBagInterface $parameterBag;

    /**
     * PdfCreatorSubscriber constructor.
     */
    public function __construct(KernelInterface $kernel, ParameterBagInterface $parameterBag)
    {
        $this->kernel = $kernel;
        $this->parameterBag = $parameterBag;
    }

    public function addDompdfLogging(BeforeCreateLibraryInstanceEvent $event): void
    {
        if ($this->kernel->isDebug() && DompdfCreator::getType() === $event->getBeforeCreateLibraryInstanceCallback()->getType()) {
            /** @var Options $options */
            $options = $event->getBeforeCreateLibraryInstanceCallback()->getConstructorParameters()['options'];
            $options->setLogOutputFile($this->kernel->getLogDir().'/huh_pdf_creator_dompdf.log');
        }
    }

    public function addDompdfHttpContext(BeforeOutputPdfCallbackEvent $event): void
    {
        if (DompdfCreator::getType() !== $event->getBeforeOutputPdfCallback()->getType()) {
            return;
        }
        $configuration = $event->getConfiguration();
        /** @var Dompdf $instance */
        $instance = $event->getBeforeOutputPdfCallback()->getLibraryInstance();

        if ($configuration->baseUrl) {
            if (false === filter_var($configuration->baseUrl, FILTER_VALIDATE_URL)) {
                throw new InvalidPdfGeneratorConfigurationException("Configuration with title '".$configuration->title."' and id '".$configuration->id."' has an invalid base_url (".$configuration->baseUrl.').');
            }

            $path = parse_url($configuration->baseUrl);
            $instance->setProtocol($path['scheme'].'://');
            $instance->setBaseHost($path['host']);
            $instance->setBasePath($path['path'] ?? '/');
        }

        if ($this->parameterBag->has('huh_pdf_creator')) {
            $config = $this->parameterBag->get('huh_pdf_creator');
            if (isset($config['allowed_paths'])) {
                $chroot = $instance->getOptions()->getChroot();
                foreach ($config['allowed_paths'] as $path) {
                    $chroot[] = $this->kernel->getProjectDir().'/'.ltrim($path, '/');
                }
                $instance->getOptions()->setChroot($chroot);
            }
        }

        if ($configuration->credentials) {
            $auth = base64_encode($configuration->credentials);
            $context = stream_context_create([
                'http' => [
                    'header' => "Authorization: Basic $auth",
                ],
            ]);
            $instance->setHttpContext($context);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeCreateLibraryInstanceEvent::class => [
                ['addDompdfLogging', 10],
            ],
            BeforeOutputPdfCallbackEvent::class => [
                ['addDompdfHttpContext', 10],
            ],
        ];
    }
}
