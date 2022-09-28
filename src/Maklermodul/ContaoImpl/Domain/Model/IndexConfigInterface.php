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

namespace Pdir\MaklermodulBundle\Maklermodul\ContaoImpl\Domain\Model;

use Pdir\MaklermodulBundle\Maklermodul\Domain\Model\Estate;

interface IndexConfigInterface
{
    public function getUid();

    public function getDetailViewUri(Estate $estate);

    public function getColumnConfig();

    public function getFilterColumnConfig();

    public function getStorageFileUri();

    public function setStorageFileUri($newUri);
}
