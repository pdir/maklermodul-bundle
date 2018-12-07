<?php

/*
 * maklermodul bundle for Contao Open Source CMS
 *
 * Copyright (c) 2018 pdir / digital agentur // pdir GmbH
 *
 * @package    maklermodul-bundle
 * @link       https://www.maklermodul.de
 * @license    proprietary / pdir license - All-rights-reserved - commercial extension
 * @author     Mathias Arzberger <develop@pdir.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pdir\MaklermodulBundle\Tests;

use Pdir\MaklermodulBundle\DependencyInjection\PdirMaklermodulExtension;
use Pdir\MaklermodulBundle\PdirMaklermodulBundle;
use PHPUnit\Framework\TestCase;

class PdirMaklermodulBundleTest extends TestCase
{
    public function testCanBeInstantiated()
    {
        $bundle = new PdirMaklermodulBundle();
        $this->assertInstanceOf(PdirMaklermodulBundle::class, $bundle);
    }

    public function testGetContainerExtension()
    {
        $bundle = new PdirMaklermodulBundle();
        $this->assertInstanceOf(PdirMaklermodulExtension::class, $bundle->getContainerExtension());
    }
}
