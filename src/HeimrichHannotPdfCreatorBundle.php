<?php

declare(strict_types=1);

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\PdfCreatorBundle;

use HeimrichHannot\PdfGenerationBundle\HeimrichHannotPdfGenerationBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class HeimrichHannotPdfCreatorBundle.
 */
class HeimrichHannotPdfCreatorBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new HeimrichHannotPdfGenerationBundle();
    }
}
