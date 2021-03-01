<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\PdfCreatorBundle\DataContainer;

use Contao\Controller;
use Contao\DataContainer;
use Contao\DC_Table;
use Contao\Image;
use Contao\Input;
use Contao\Message;
use Contao\Model\Collection;
use Contao\StringUtil;
use HeimrichHannot\PdfCreator\Concrete\TcpdfCreator;
use HeimrichHannot\PdfCreator\Exception\MissingDependenciesException;
use HeimrichHannot\PdfCreator\PdfCreatorFactory;
use Heimrichhannot\PdfCreatorBundle\Exception\PdfCreatorConfigurationNotFoundException;
use Heimrichhannot\PdfCreatorBundle\Model\PdfCreatorConfigModel;
use HeimrichHannot\UtilsBundle\Container\ContainerUtil;
use HeimrichHannot\UtilsBundle\Model\ModelUtil;

class PdfCreatorConfigContainer
{
    /**
     * @var ContainerUtil
     */
    protected $containerUtil;
    /**
     * @var ModelUtil
     */
    protected $modelUtil;

    /**
     * PdfCreatorConfigContainer constructor.
     */
    public function __construct(ContainerUtil $containerUtil, ModelUtil $modelUtil)
    {
        $this->containerUtil = $containerUtil;
        $this->modelUtil = $modelUtil;
    }

    public function onLabelCallback($row, $label, $dc, $args): array
    {
        Controller::loadLanguageFile('tl_pdf_creator_config');
        $label .= ' <span style="color:#b3b3b3; padding-left:3px; display: inline;">['
            .($GLOBALS['TL_LANG']['tl_pdf_creator_config']['type'][$row['type']] ?: $row['type'])
            .', '.$row['format']
            .', '.($GLOBALS['TL_LANG']['tl_pdf_creator_config']['orientation'][$row['orientation']] ?: $row['orientation'])
            .', '.($GLOBALS['TL_LANG']['tl_pdf_creator_config']['outputMode'][$row['outputMode']] ?: $row['outputMode'])
            .']</span>';

        return [$label];
    }

    public function onLoadCallback($dc): void
    {
        if (!$dc || !$this->containerUtil->isBackend() || 'edit' != Input::get('act')) {
            return;
        }
        /** @var PdfCreatorConfigModel|null $config */
        $config = $this->modelUtil->findModelInstanceByPk(PdfCreatorConfigModel::getTable(), $dc->id);

        if (!$config) {
            return;
        }

        try {
            $type = PdfCreatorFactory::createInstance($config->type);
            $type::isUsable(true);
        } catch (MissingDependenciesException $e) {
            $message = $GLOBALS['TL_LANG']['ERR']['huhPdfCreatorMissingDependencies'] ?: 'Missing dependencies: %s';

            if (!empty($e->getDependencies())) {
                $message = sprintf($message, implode(',', $e->getDependencies()));
            } else {
                $message = sprintf($message, ($GLOBALS['TL_LANG']['ERR']['huhPdfCreatorMissingDependencies'] ?: 'Information not available'));
            }

            Message::addError($message);
        }

        $dca = &$GLOBALS['TL_DCA'][PdfCreatorConfigModel::getTable()];

        switch ($config->type) {
            case TcpdfCreator::getType():
                if (!class_exists('setasign\Fpdi\Tcpdf\Fpdi')) {
                    $dca['palettes']['default'] = str_replace(',masterTemplate', '', $dca['palettes']['default']);
                    $missingFpdiMessage = ($GLOBALS['TL_LANG']['INFO']['huhPdfCreatorMissingDependencyForMasterTemplate'] ?:
                        'To use pdf master templates, you need to install %s');
                    $missingFpdiMessage = sprintf($missingFpdiMessage, '"setasign/fpdi": "^2.3"');
                    Message::addInfo($missingFpdiMessage);
                }
        }
    }

    public function onTypeOptionsCallback($dc): array
    {
        return PdfCreatorFactory::getTypes();
    }

    /**
     * @param DC_Table $dc
     *
     * @throws PdfCreatorConfigurationNotFoundException
     */
    public function onOutputModeOptionsCallback($dc): array
    {
        if (!$dc || !($configuration = PdfCreatorConfigModel::findByPk($dc->id))) {
            return [];
        }

        $type = PdfCreatorFactory::createInstance($configuration->type);

        if (!$type) {
            return [];
        }

        return $type->getSupportedOutputModes();
    }

    /**
     * @param DataContainer $dc
     */
    public function onPdfCreatorConfigWizardCallback($dc): string
    {
        Controller::loadLanguageFile('tl_pdf_creator_config');

        return (!$dc || $dc->value < 1) ? '' : ' <a href="contao?do=pdf_creator_config&amp;act=edit&amp;id='.$dc->value.'&amp;popup=1&amp;nb=1&amp;rt='.REQUEST_TOKEN.'" title="'.sprintf(StringUtil::specialchars($GLOBALS['TL_LANG']['tl_pdf_creator_config']['edit'][1]), $dc->value).'" onclick="Backend.openModalIframe({\'title\':\''.StringUtil::specialchars(str_replace("'", "\\'", sprintf($GLOBALS['TL_LANG']['tl_pdf_creator_config']['edit'][1], $dc->value))).'\',\'url\':this.href});return false">'.Image::getHtml('alias.svg', $GLOBALS['TL_LANG']['tl_pdf_creator_config']['edit'][0]).'</a>';
    }

    public function getPdfCreatorConfigOptions(): array
    {
        $options = [];
        /** @var PdfCreatorConfigModel[]|Collection $configurations */
        $configurations = PdfCreatorConfigModel::findAll();

        if (!$configurations) {
            return $options;
        }

        foreach ($configurations as $configuration) {
            $label = $this->onLabelCallback($configuration->row(), $configuration->title, null, null)[0] ?: $configuration->title;
            $options[$configuration->id] = $label;
        }

        return $options;
    }
}
