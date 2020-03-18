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

namespace Pdir\MaklermodulBundle\Maklermodul\Domain\Repository;

use Contao\StringUtil;
use Pdir\MaklermodulBundle\Maklermodul\Domain\Model\Estate;
use Pdir\MaklermodulBundle\Util\Helper;

class EstateRepository
{
    private $storageDirectoryPath;

    public function __construct($storageDirectoryPath)
    {
        $this->storageDirectoryPath = $storageDirectoryPath;
        if (!is_dir($this->storageDirectoryPath)) {
            throw new \Exception('Could not open storage directory: '.$this->storageDirectoryPath);
        }
    }

    public function findByObjectId($objectId)
    {
        $fileNamePath = sprintf('%s%s.json', $this->storageDirectoryPath, $objectId);

        if (!file_exists($fileNamePath)) {
            return null;
        }

        return $this->loadJsonFile($fileNamePath);
    }

    public function findByExternalObjectNumber($id)
    {
        $alias = Estate::sanizeFileName($id);
        $fileNamePath = sprintf('%s*-%s.json', $this->storageDirectoryPath, $alias);

        $files = [];
        foreach (glob($fileNamePath) as $file) {
            $files[] = $file;
        }

        if (!$files[0] || !file_exists($files[0])) {
            return null;
        }

        return $this->loadJsonFile($files[0]);
    }

    public function findAll()
    {
        $directoryIterator = new \DirectoryIterator($this->storageDirectoryPath);
        $returnValue = [];

        foreach ($directoryIterator as $child) {
            if ($this->isRelevantJson($child->getPathname())) {
                $estate = $this->loadJsonFile($child->getPathname());
                $returnValue[] = $estate;
            }
        }

        return $returnValue;
    }

    public static function getInstance()
    {
        return new self(Helper::imagePath);
    }

    public function loadJsonFile($fileNamePath)
    {
        $objFile = new \File(str_replace($this->storageDirectoryPath, Helper::imagePath, $fileNamePath));
        $decoded = json_decode($objFile->getContent(), true);

        if (null === $decoded) {
            return null;
        }

        return new Estate($decoded);
    }

    private function isRelevantJson($filename)
    {
        if (false === strpos($filename, '.json')) {
            return false;
        }
        if ('key-index.json' === substr($filename, -14)) {
            return false;
        }
        if (false === !strpos($filename, '00index')) {
            return false;
        }

        return true;
    }
}
