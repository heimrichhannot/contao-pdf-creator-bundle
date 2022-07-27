<?php

declare(strict_types=1);

/*
 * Copyright (c) 2022 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\PdfCreatorBundle\Model;

use Contao\Model;
use function Symfony\Component\String\u;

/**
 * Class PdfCreatorConfigModel.
 *
 * @property string $type
 * @property string $title
 * @property string $filename
 * @property string $orientation
 * @property string $outputMode
 * @property string $filePath
 * @property string $format
 * @property string $fonts
 * @property string $pageMargins
 * @property string $masterTemplate
 */
class PdfCreatorConfigModel extends Model
{
    protected static $strTable = 'tl_pdf_creator_config';

    /**
     * @return static
     */
    public static function createModelFromBundleConfig(string $title, array $data): self
    {
        $model = new self();
        $model->id = $title;
        $model->title = $data['name'] ?: $title;

        foreach ($data as $key => $value) {
            switch ($key) {
                case 'title':
                case 'name':
                case 'id':
                    break;

                case 'base_template':
                    $model->masterTemplate = $value;

                    break;

                case 'margins':
                    $model->pageMargins = $value;

                    break;

                case 'fonts':
                    $fonts = [];

                    foreach ($value as $fontEntry) {
                        $fonts[] = [
                            'filepath' => $fontEntry['path'],
                            'family' => $fontEntry['family'],
                            'style' => $fontEntry['style'],
                            'weight' => $fontEntry['weight'],
                        ];
                    }
                    $model->fonts = $fonts;

                    break;

                default:
                    $model->{u($key)->camel()} = $value;
            }
        }

        return $model;
    }
}
