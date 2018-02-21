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
namespace Pdir\MaklermodulBundle\Maklermodul\ContaoImpl\Domain\Model;

use Pdir\MaklermodulBundle\Maklermodul\Domain\Model\EnviromentInterface;

class Enviroment implements EnviromentInterface {

	static $instance;

	public function pathToUri($path) {
		return str_replace(TL_ROOT . '/', '', $path);
	}

	public function jsPathToUriFunction() {
		return 'function pathToUri(path) { return path; }';
	}

	public static function getInstance() {
		if (self::$instance == null) {
			self::$instance = new Enviroment();
		}

		return self::$instance;
	}
}
