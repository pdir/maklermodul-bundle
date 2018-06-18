<?php

namespace Pdir\MaklermodulBundle\Maklermodul\ContaoImpl\Repository;

use Pdir\MaklermodulBundle\Maklermodul\ContaoImpl\Domain\Model\IndexConfigInterface;

interface IndexConfigRepositoryInterface {
	public function findById($configId);
	public function findAll();
	public function update(IndexConfigInterface $config);
}