<?php

/**
 * maklermodul for Contao Open Source CMS
 *
 * Copyright (C) 2017 pdir / digital agentur <develop@pdir.de>
 *
 * @package    maklermodul
 * @link       https://www.maklermodul.de
 * @license    pdir license - All-rights-reserved - commercial extension
 * @author     pdir GmbH <develop@pdir.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Namespace
 */
namespace Pdir\MaklermodulBundle\Maklermodul\Domain\Repository;

use Pdir\MaklermodulBundle\Maklermodul\Domain\Model\Estate;

class EstateRepository {

	private $storageDirectoryPath;

	public function __construct($storageDirectoryPath) {
        $this->storageDirectoryPath = $storageDirectoryPath;
		if (!is_dir($this->storageDirectoryPath)) {
			throw new \Exception("Could not open storage directory: " . $this->storageDirectoryPath);
		}
	}

	public function findByObjectId($objectId) {
		$fileNamePath = sprintf('%s/%s.json', $this->storageDirectoryPath, $objectId);

		if (!file_exists($fileNamePath)) {
			return null;
		}

		return $this->loadJsonFile($fileNamePath);
	}

	public function findAll() {
		$directoryIterator = new \DirectoryIterator($this->storageDirectoryPath);
		$returnValue = array();

		$count = 0;
		foreach ($directoryIterator as $child) {
			if($this->isRelevantJson($child->getPathname())) {
				$estate = $this->loadJsonFile($child->getPathname());
				$returnValue[] = $estate;
			}
		}
		return $returnValue;
	}

	public function findByStorageFile($fileNamePath) {
		$returnValue = array();

		$jsonContent = file_get_contents($fileNamePath);
		$index = json_decode($jsonContent, true);
		if(!count($index['data'])) return $this->findAll();

		foreach ($index['data'] as $obj) {
			if($this->isRelevantJson($filename)) {
				$estate = $this->loadJsonFile($filename);
				$returnValue[] = new Estate($estate);
			}
		}
		return $returnValue;
	}

	private function isRelevantJson($filename) {
		if(strpos($filename,".json")===false)
			return false;
		if(substr($filename, -14) == "key-index.json")
			return false;
		if(!strpos($filename, "00index") === false)
			return false;
		return true;
	}


	public static function getInstance() {
        $container = \System::getContainer();
        $strRootDir = $container->getParameter('kernel.project_dir') . DIRECTORY_SEPARATOR . $container->getParameter('contao.upload_path');
        $storageDirectoryPath = $strRootDir . DIRECTORY_SEPARATOR . 'maklermodul' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR;
		return new EstateRepository($storageDirectoryPath);
	}

	private function loadJsonFile($fileNamePath) {
		$jsonContent = file_get_contents($fileNamePath);
		$decoded = json_decode($jsonContent, true);

		if ($decoded == NULL) {
			return null;
		}

		return new Estate($decoded);
	}
}
