<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

use Heimrichhannot\PdfCreatorBundle\EventListener\Contao\LoadDataContainerListener;
use Heimrichhannot\PdfCreatorBundle\EventListener\Contao\PrintArticleAsPdfListener;
use Heimrichhannot\PdfCreatorBundle\Model\PdfCreatorConfigModel;

/*
 * Backend modules
 */
$GLOBALS['BE_MOD']['system']['pdf_creator_config'] = [
    'tables' => ['tl_pdf_creator_config'],
];

/*
 * Hooks
 */
$GLOBALS['TL_HOOKS']['loadDataContainer'][] = [LoadDataContainerListener::class, '__invoke'];
$GLOBALS['TL_HOOKS']['printArticleAsPdf'][] = [PrintArticleAsPdfListener::class, '__invoke'];

/*
 * Models
 */
$GLOBALS['TL_MODELS']['tl_pdf_creator_config'] = PdfCreatorConfigModel::class;
