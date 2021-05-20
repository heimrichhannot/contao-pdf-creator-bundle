<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\PdfCreatorBundle\DependencyInjection;

use HeimrichHannot\PdfCreator\AbstractPdfCreator;
use HeimrichHannot\PdfCreator\PdfCreatorFactory;
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
            ->end()
        ;
        $rootNode->children()
                ->arrayNode('configurations')
                    ->arrayPrototype()
                        ->children()
                            ->enumNode('type')
                                ->values(PdfCreatorFactory::getTypes())
                                ->info('The pdf create type (pdf library).')
                                ->end()
                            ->scalarNode('name')
                                ->info('A nice name for displaying in the backend.')
                                ->example('A4 brochure with corporate logo')
                                ->end()
                            ->scalarNode('filename')
                                ->defaultValue('%%title%%.pdf')
                                ->info('Set a file name for the generated pdf files.
                                        You can use the %title% placeholder to use the title of the content to export in the file name.')
                                ->end()
                            ->enumNode('orientation')
                                ->values([AbstractPdfCreator::ORIENTATION_PORTRAIT, AbstractPdfCreator::ORIENTATION_LANDSCAPE])
                                ->defaultValue(AbstractPdfCreator::ORIENTATION_PORTRAIT)
                                ->info('Set page orientation.')
                                ->end()
                            ->enumNode('output_mode')
                                ->values(AbstractPdfCreator::OUTPUT_MODES)
                                ->defaultValue(AbstractPdfCreator::OUTPUT_MODE_INLINE)
                                ->info('Set how to output the pdf.')
                                ->end()
                            ->scalarNode('format')
                                ->defaultValue('A4')
                                ->info('Set a page format. This could be a standardized format like A3, A4, A5 or Legal,
                                        otherwise you can specify the format in millimeter (width x height, seperated by comma, for example 180,210).')
                                ->end()
                            ->scalarNode('base_url')
                                ->defaultNull()
                                ->info('Set a default url that will override the url that is determined by the request.
                                        This can be usefull on development servers with custom url mapping.')
                                ->example('https://stage.example.org:8001/examplepath/')
                                ->end()
                            ->scalarNode('credentials')
                                ->defaultNull()
                                ->info('Set credentials for basic http authentication.')
                                ->example('user:password')
                                ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
