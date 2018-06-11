<?php

namespace Pdir\MaklermodulBundle\Maklermodul\ContaoImpl\Domain\Model;

use Pdir\MaklermodulBundle\Maklermodul\Domain\Model\Estate;

interface IndexConfigInterface {
	public function getUid();
	
	public function getDetailViewUri(Estate $estate);
	public function getColumnConfig();
    public function getFilterColumnConfig();
	
	public function getStorageFileUri();
	public function setStorageFileUri($newUri);
	
	public function getImageDimensions();
}