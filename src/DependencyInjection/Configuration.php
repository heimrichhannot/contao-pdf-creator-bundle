<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\PdfCreatorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('huh_pdf_creator');

        $rootNode
            ->children()
                ->booleanNode('enable_contao_article_pdf_syndication')
                    ->defaultFalse()
                    ->info('Set to true to use this bundle functionality in the contao article syndication.')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
