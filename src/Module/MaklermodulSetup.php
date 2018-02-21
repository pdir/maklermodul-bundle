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
namespace Pdir\MaklermodulBundle\Module;

class MaklermodulSetup extends \BackendModule
{
    /**
     * Maklermodul version
     */
    const VERSION = '2.0.0';

    /**
    * Template
    * @var string
    */
    protected $strTemplate = 'be_maklermodul_setup';

	/**
	 * API Url
	 * @var string
	 */
	static $apiUrl = 'https://pdir.de/api/maklermodul/';

    /**
     * Extension mode
     * @var boolean
     */
    const MODE = 'DEMO';

    /**
    * Generate the module
    * @throws \Exception
    */
    protected function compile()
    {
		// $className = '/vendor/pdir/maklermodul-bundle/src/Resources/contao/Classes/Helper.php';
		$strDomain = \Environment::get('httpHost');

		/* @todo empty cache folder from backend */
        $files = \Files::getInstance();
        $storageDirectoryPath = $GLOBALS['TL_CONFIG']['uploadPath'] . '/makler_modul_mplus/';

		switch (\Input::get('act')) {
			case 'download':
				$strHelperData = file_get_contents(self::$apiUrl . 'download/latest/'.$strDomain);

				$this->Template->message[] = array('Für Ihre IP/Domain wurde noch keine Lizenz gekauft.', 'error');

				if ($strHelperData != 'error')
				{
					// \File::putContent($className, $strHelperData);
					$this->Template->message[] = array('Vollversion wurde erfolgreich heruntergeladen!', 'confirm');
				}
            case 'emptyDataFolder':
                $files->rrdir($storageDirectoryPath.'data', true);
                $this->Template->message[] = array('', 'info');
                break;
            case 'emptyUploadFolder':
                $files->rrdir($storageDirectoryPath.'upload', true);
                $this->Template->message[] = array('', 'info');
                break;
            case 'emptyTmpFolder':
                $files->rrdir($storageDirectoryPath.'org', true);
                $this->Template->message[] = array('', 'info');
                break;
            default:
                $this->Template->base = $this->Environment->base;
                $this->Template->version = self::VERSION;
                $this->Template->storageDirectoryPath = $storageDirectoryPath;
		}

		$this->Template->extMode = self::MODE;
		$this->Template->extModeTxt = self::MODE=='FULL' ? 'Vollversion' : 'Demo';
		$this->Template->version = self::VERSION;
		$this->Template->hostname = gethostname();
		$this->Template->ip = \Environment::get('server');
		$this->Template->domain = $strDomain;

		// email body
		$this->Template->emailBody = $this->getEmailBody();
    }

	protected function getEmailBody()
	{
		$arrSearch = array(':IP:', ':HOST:', ':DOMAIN:', '<br>');
		$arrReplace = array($this->Template->ip, $this->Template->hostname, $this->Template->domain, '%0d%0a');
		return str_replace( $arrSearch, $arrReplace, $GLOBALS['TL_LANG']['MAKLERMODUL']['emailBody'] );
	}
}
