<?php

declare(strict_types=1);

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\PdfCreatorBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class HeimrichhannotContaoPdfCreatorExtension.
 */
class HeimrichHannotPdfCreatorExtension extends Extension
{
    public function getAlias(): string
    {
        return 'huh_pdf_creator';
    }

    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../../config')
        );

        $loader->load('services.yaml');

        $configuration = new Configuration();
        $bundleConfig = $this->processConfiguration($configuration, $configs);
        $container->setParameter('huh_pdf_creator', $bundleConfig);
    }
}
