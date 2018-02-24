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
namespace Pdir\MaklermodulBundle\Maklermodul\Domain\Model;

interface IndexConfigInterface {
	public function getUid();

	public function getDetailViewUri(Estate $estate);
	public function getColumnConfig();
    public function getFilterColumnConfig();
	public function getPreFilterConfig();

	public function getStorageFileUri();
	public function setStorageFileUri($newUri);

	public function getImageDimensions();
}
