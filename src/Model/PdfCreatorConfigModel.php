<?php

declare(strict_types=1);

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
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
            if (\in_array($key, ['title', 'name', 'id'])) {
                continue;
            }
            $model->{u($key)->camel()} = $value;
        }

        return $model;
    }
}
