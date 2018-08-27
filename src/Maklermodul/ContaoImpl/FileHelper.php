<?php

/*
 * maklermodul for Contao Open Source CMS
 *
 * Copyright (C) 2018 pdir / digital agentur <develop@pdir.de>
 *
 * @package    maklermodul
 * @link       https://www.maklermodul.de
 * @license    pdir license - All-rights-reserved - commercial extension
 * @author     pdir GmbH <develop@pdir.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

/**
 * Namespace.
 */

namespace Pdir\MaklermodulBundle\Maklermodul\ContaoImpl;

class FileHelper
{
    private $fsNode;

    public function __construct($path, $isFolder = false)
    {
        $path = $this->prepareFileNameForContao($path);
        if ($isFolder) {
            $this->fsNode = new \Folder($path);
        } else {
            $this->fsNode = new \File($path);
        }
    }

    public function putContent($filePath, $content)
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' or strtoupper(substr(PHP_OS, 0, 3)) === 'WINNT') {
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
        if (strpos($fileName, TL_ROOT) === 0) {
            $fileName = str_replace(TL_ROOT, '', $fileName);
        }

        return $fileName;
    }
}
