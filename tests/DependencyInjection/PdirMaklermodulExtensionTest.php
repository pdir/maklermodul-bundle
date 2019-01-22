<?php

declare(strict_types=1);

/*
 * maklermodul bundle for Contao Open Source CMS
 *
 * Copyright (c) 2019 pdir / digital agentur // pdir GmbH
 *
 * @package    maklermodul-bundle
 * @link       https://www.maklermodul.de
 * @license    proprietary / pdir license - All-rights-reserved - commercial extension
 * @author     Mathias Arzberger <develop@pdir.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pdir\MaklermodulBundle\Test\DependencyInjection;

use Contao\TestCase\ContaoTestCase;
use Pdir\MaklermodulBundle\DependencyInjection\PdirMaklermodulExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class PdirMaklermodulExtensionTest extends ContaoTestCase
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    public function setUp()
    {
        parent::setUp();

        $this->container = new ContainerBuilder(new ParameterBag(['kernel.debug' => false]));

        $extension = new PdirMaklermodulExtension();
        $extension->load([], $this->container);
    }

    /**
     * Tests the object instantiation.
     */
    public function testCanBeInstantiated()
    {
        $extension = new PdirMaklermodulExtension();
        $this->assertInstanceOf(PdirMaklermodulExtension::class, $extension);
    }
}
