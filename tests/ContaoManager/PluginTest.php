<?php

/*
 * This file is part of PDF Creator Bundle.
 *
 * (c) Thomas Könrer 2021 <digitales@heimrich-hannot.de>
 * @license LGPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/heimrichhannot/contao-pdf-creator-bundle
 */
declare(strict_types=1);

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace Heimrichhannot\ContaoPdfCreatorBundle\Tests\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\DelegatingParser;
use Contao\TestCase\ContaoTestCase;
use Heimrichhannot\ContaoPdfCreatorBundle\ContaoManager\Plugin;
use Heimrichhannot\ContaoPdfCreatorBundle\HeimrichhannotContaoPdfCreatorBundle;

/**
 * Class PluginTest.
 */
class PluginTest extends ContaoTestCase
{
    /**
     * Test Contao manager plugin class instantiation.
     */
    public function testInstantiation(): void
    {
        $this->assertInstanceOf(Plugin::class, new Plugin());
    }

    /**
     * Test returns the bundles.
     */
    public function testGetBundles(): void
    {
        $plugin = new Plugin();

        /** @var array $bundles */
        $bundles = $plugin->getBundles(new DelegatingParser());

        $this->assertCount(1, $bundles);
        $this->assertInstanceOf(BundleConfig::class, $bundles[0]);
        $this->assertSame(HeimrichhannotContaoPdfCreatorBundle::class, $bundles[0]->getName());
        $this->assertSame([ContaoCoreBundle::class], $bundles[0]->getLoadAfter());
    }
}
