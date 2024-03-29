<?php

/*
 * Copyright (c) 2023 Heimrich & Hannot GmbH
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
        $treeBuilder = new TreeBuilder('huh_pdf_creator');
        $treeBuilder->getRootNode()
            ->children()
                ->booleanNode('enable_contao_article_pdf_syndication')
                    ->defaultFalse()
                    ->info('Set to true to use this bundle functionality in the contao article syndication.')
                ->end()
                ->arrayNode('allowed_paths')
                    ->info('Set the paths allowed for file opening (e.g. image loading). Paths are be relativ to the project dir. Currently only used for dompdf (chroot).')
                    ->scalarPrototype()->end()
                    ->defaultValue(['web', 'public', 'files', 'assets'])
                ->end()
                ->append($this->pdfCreatorConfigurations())
            ->end()
        ;

        return $treeBuilder;
    }

    private function pdfCreatorConfigurations()
    {
        $treeBuilder = new TreeBuilder('configurations');

        $node = $treeBuilder->getRootNode()
            ->info('PDF creator configurations')
            ->useAttributeAsKey('title')
            ->arrayPrototype()
                ->info("The title of the configuration. Should be a unique alias/name containing just 'a-z0-9-_' like 'news_export','default','brand_a_themed'.")
                ->children()
                    ->enumNode('type')
                        ->values(PdfCreatorFactory::getTypes())
                        ->info('The pdf creator type (pdf library).')
                    ->end()
                    ->scalarNode('name')
                        ->info('A nice name for displaying in the backend.')
                        ->example('A4 brochure with corporate logo')
                    ->end()
                    ->scalarNode('filename')
                        ->defaultValue('%%title%%.pdf')
                        ->info('Set a file name for the generated pdf files. You can use the %title% placeholder to use the title of the content to export in the file name.')
                    ->end()
                    ->scalarNode('file_path')
                        ->info(
                            'The path to the folder where the generated files should be stored. '.
                            'Only used if output_mode is AbstractPdfCreator::OUTPUT_MODE_FILE. '.
                            'Path must be relative to the project path.'
                        )
                        ->example('files/export/pdf')
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
                    ->info('Set a page format. This could be a standardized format like A3, A4, A5 or Legal, otherwise you can specify the format in millimeter (width x height, seperated by comma, for example 180,210).')
                    ->end()
                    ->arrayNode('margins')
                        ->children()
                            ->scalarNode('top')->info('Relative path from project to font file.')->end()
                            ->scalarNode('left')->info('Name of the font family')->end()
                            ->scalarNode('bottom')->info('Font style')->end()
                            ->scalarNode('right')->info('Font weight')->end()
                            ->enumNode('unit')->defaultValue('mm')->values(['mm'])->end()
                        ->end()
                    ->end()
                    ->arrayNode('fonts')
                        ->arrayPrototype()
                            ->children()
                                ->scalarNode('path')->info('Relative path from project to font file.')->end()
                                ->scalarNode('family')->info('Name of the font family')->end()
                                ->scalarNode('style')->info('Font style')->end()
                                ->scalarNode('weight')->info('Font weight')->end()
                            ->end()
                        ->end()
                    ->end()
                    ->scalarNode('base_template')
                        ->info('Set a pdf template (also known as master template), which will be the base template for the generated pdf files. Must be a path relative to the contao web root.')
                        ->example('files/media/news/news_base_template.pdf')
                    ->end()
                    ->scalarNode('base_url')
                        ->defaultNull()
                        ->info('Set a default url that will override the url that is determined by the request. This can be usefull on development servers with custom url mapping.')
                        ->example('https://stage.example.org:8001/examplepath/')
                    ->end()
                    ->scalarNode('credentials')
                        ->defaultNull()
                        ->info('Set credentials for basic http authentication.')
                        ->example('user:password')
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }
}
