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
use Pdir\MaklermodulBundle\Util\Helper;

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

		foreach ($directoryIterator as $child) {
			if($this->isRelevantJson($child->getPathname())) {
				$estate = $this->loadJsonFile($child->getPathname());
				$returnValue[] = $estate;
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
		return new EstateRepository(Helper::imagePath);
	}

	public function loadJsonFile($fileNamePath) {

        $objFile = new \File(str_replace($this->storageDirectoryPath, Helper::imagePath, $fileNamePath));
		$decoded = json_decode($objFile->getContent(), true);

		if ($decoded == NULL) {
			return null;
		}

		return new Estate($decoded);
	}
}
