<?php

declare(strict_types=1);

/*
 * maklermodul bundle for Contao Open Source CMS
 *
 * Copyright (c) 2022 pdir / digital agentur // pdir GmbH
 *
 * @package    maklermodul-bundle
 * @link       https://www.maklermodul.de
 * @license    proprietary / pdir license - All-rights-reserved - commercial extension
 * @author     Mathias Arzberger <develop@pdir.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Namespace.
 */

namespace Pdir\MaklermodulBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class PdirMaklermodulExtension extends ConfigurableExtension
{
    /**
     * Configures the passed container according to the merged configuration.
     */
    public function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $loader->load('listener.yml');
        $loader->load('services.yml');

        $container->setParameter('pdir_maklermodul.aliasPrefix', $mergedConfig['aliasPrefix']);
        $container->setParameter('pdir_maklermodul.alias', $mergedConfig['alias']);
        $container->setParameter('pdir_maklermodul.aliasSuffix', $mergedConfig['aliasSuffix']);
        $container->setParameter('pdir_maklermodul.validAliasCharacters', $mergedConfig['validAliasCharacters']);
        $container->setParameter('pdir_maklermodul.aliasDelimiter', $mergedConfig['aliasDelimiter']);
        $container->setParameter('pdir_maklermodul.aliasLocale', $mergedConfig['aliasLocale']);
    }
}
