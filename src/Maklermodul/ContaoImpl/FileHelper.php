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

namespace Pdir\MaklermodulBundle\Maklermodul\ContaoImpl;

use Contao\File;
use Contao\Folder;

class FileHelper
{
    private $fsNode;

    /**
     * @throws \Exception
     */
    public function __construct($path, $isFolder = false)
    {
        $path = $this->prepareFileNameForContao($path);

        if ($isFolder) {
            $this->fsNode = new Folder($path);
        } else {
            $this->fsNode = new File($path);
        }
    }

    public function putContent($filePath, $content)
    {
        if ('WIN' === strtoupper(substr(PHP_OS, 0, 3)) || 'WINNT' === strtoupper(substr(PHP_OS, 0, 3))) {
            return file_put_contents($filePath, $content);
        }
        $filePath = $this->prepareFileNameForContao($filePath);

        return $this->fsNode->putContent($filePath, $content);
    }

    public function delete()
    {
        return $this->fsNode->delete();
    }

    private function prepareFileNameForContao($fileName)
    {
        if (0 === strpos($fileName, TL_ROOT)) {
            $fileName = str_replace(TL_ROOT, '', $fileName);
        }

        return $fileName;
    }
}
