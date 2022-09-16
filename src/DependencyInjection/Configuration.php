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

/**
 * Namespace.
 */

namespace Pdir\MaklermodulBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('pdir_maklermodul');

        if (method_exists($treeBuilder, 'getRootNode'))
        {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root('pdir_maklermodul');
        }

        $rootNode
            ->children()
            ->scalarNode('aliasPrefix')
            ->defaultValue(null)
            ->end()
            ->scalarNode('alias')
            ->cannotBeEmpty()
            ->defaultValue('freitexte/objekttitel')
            ->end()
            ->scalarNode('aliasSuffix')
            ->defaultValue('verwaltung_techn/objektnr_extern')
            ->end()
            ->scalarNode('validAliasCharacters')
            ->defaultValue('0-9a-z')
            ->end()
            ->scalarNode('aliasDelimiter')
            ->defaultValue('-')
            ->end()
            ->scalarNode('aliasLocale')
            ->defaultValue('en')
            ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
