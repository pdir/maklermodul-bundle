<?php

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

namespace Pdir\MaklermodulBundle\Maklermodul\ContaoImpl\Repository;

use Pdir\MaklermodulBundle\Maklermodul\ContaoImpl\Domain\Model\IndexConfigInterface;

interface IndexConfigRepositoryInterface
{
    public function findById($configId);

    public function findAll();

    public function update(IndexConfigInterface $config);
}
