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

/**
 * Namespace.
 */

namespace Pdir\MaklermodulBundle\Maklermodul\ContaoImpl\Repository;

use Pdir\MaklermodulBundle\Maklermodul\ContaoImpl\Domain\Model\IndexConfig;
use Pdir\MaklermodulBundle\Maklermodul\ContaoImpl\Domain\Model\IndexConfigInterface;

class IndexConfigRepository extends \Frontend implements IndexConfigRepositoryInterface
{
    const CONTAO_MODULE_TYPE_LIST = 'immoListView';

    public function __construct()
    {
        $this->import('Database');
    }

    /**
     * (non-PHPdoc).
     *
     * @see \Pdir\MaklermodulBundle\Maklermodul\Domain\Repository\IndexConfigRepositoryInterface::update()
     *
     * @param \Pdir\MaklermodulBundle\Maklermodul\ContaoImpl\Domain\Model\IndexConfigInterface $config index config
     */
    public function update(IndexConfigInterface $config)
    {
        $objquery = $this->Database->prepare('UPDATE tl_module SET immo_actIndexFile=? WHERE id=?');
        $objquery->execute($config->getStorageFileUri(), $config->getUid());
    }

    /**
     * (non-PHPdoc).
     *
     * @see \Pdir\MaklermodulBundle\Maklermodul\Domain\Repository\IndexConfigRepositoryInterface::findById()
     *
     * @param int $configId contao modul id
     *
     * @return object
     */
    public function findById($configId)
    {
        $result = $this->Database->prepare('SELECT * FROM tl_module WHERE id=?')->execute($configId);

        if ($result->count() <= 0) {
            return null;
        }

        $returnValue = $result->first()->row();

        return new IndexConfig($returnValue);
    }

    /**
     * (non-PHPdoc).
     *
     * @see \Pdir\MaklermodulBundle\Maklermodul\Domain\Repository\IndexConfigRepositoryInterface::findAll()
     */
    public function findAll()
    {
        $result = $this->Database->prepare('SELECT * FROM tl_module WHERE type=?')->execute(self::CONTAO_MODULE_TYPE_LIST);

        if ($result->count() <= 0) {
            return null;
        }

        $returnValue = [];
        while ($row = $result->next()) {
            $returnValue[] = new IndexConfig($row->row());
        }

        return $returnValue;
    }
}
