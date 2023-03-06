<?php

declare(strict_types=1);

/*
 * maklermodul bundle for Contao Open Source CMS
 *
 * Copyright (c) 2023 pdir / digital agentur // pdir GmbH
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

use Contao\File;
use Pdir\MaklermodulBundle\Maklermodul\Domain\Model\Estate;
use Pdir\MaklermodulBundle\Util\Helper;

class EstateRepository
{
    private $storageDirectoryPath;

    /**
     * @throws \Exception
     */
    public function __construct($storageDirectoryPath)
    {
        $this->storageDirectoryPath = $storageDirectoryPath;

        if (!is_dir($this->storageDirectoryPath)) {
            throw new \Exception('Could not open storage directory: '.$this->storageDirectoryPath);
        }
    }

    /**
     * @throws \Exception
     */
    public function findByObjectId($objectId): ?Estate
    {
        $fileNamePath = sprintf('%s%s.json', $this->storageDirectoryPath, $objectId);

        if (null === File($fileNamePath)) {
            return null;
        }

        return $this->loadJsonFile($fileNamePath);
    }

    /**
     * @throws \Exception
     */
    public function findByExternalObjectNumber($id): ?Estate
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

    /**
     * @throws \Exception
     */
    public function findAll(): array
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

    /**
     * @throws \Exception
     */
    public static function getInstance(): EstateRepository
    {
        return new self(Helper::getImagePath());
    }

    /**
     * @throws \Exception
     */
    public function loadJsonFile($fileNamePath): ?Estate
    {
        $objFile = new File(str_replace($this->storageDirectoryPath, Helper::getImagePath(), $fileNamePath));

        $content = $objFile->getContent();

        if (!$content) {
            return null;
        }

        $decoded = json_decode($objFile->getContent(), true);

        if (null === $decoded || false === $decoded) {
            return null;
        }

        return new Estate($decoded);
    }

    private function isRelevantJson($filename): bool
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
