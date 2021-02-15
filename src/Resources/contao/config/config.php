<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

use Heimrichhannot\PdfCreatorBundle\Model\PdfCreatorConfigModel;

/*
 * Backend modules
 */
$GLOBALS['BE_MOD']['system']['pdf_creator_config'] = [
    'tables' => ['tl_pdf_creator_config'],
];

/*
 * Models
 */
$GLOBALS['TL_MODELS']['tl_pdf_creator_config'] = PdfCreatorConfigModel::class;
