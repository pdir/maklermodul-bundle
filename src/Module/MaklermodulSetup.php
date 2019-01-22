<?php

/*
 * maklermodul bundle for Contao Open Source CMS
 *
 * Copyright (c) 2019 pdir / digital agentur // pdir GmbH
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

namespace Pdir\MaklermodulBundle\Module;

use Pdir\MaklermodulBundle\Util\Helper;

class MaklermodulSetup extends \BackendModule
{
    /**
     * Extension mode.
     *
     * @var bool
     */
    const MODE = 'DEMO';

    /**
     * API Url.
     *
     * @var string
     */
    public static $apiUrl = 'https://pdir.de/api/maklermodul/';

    /**
     * Debug message Array.
     *
     * @var array
     */
    public $debugMessages;

    /**
     * Template.
     *
     * @var string
     */
    protected $strTemplate = 'be_maklermodul_setup';

    /**
     * Storage directory path.
     *
     * @var string
     */
    protected $storageDirectoryPath;

    /**
     * Demo data filename.
     *
     * @var string
     */
    protected $demoDataFilename = 'data/demo-data.zip';

    public function debug($message)
    {
        if (!is_array($message)) {
            $this->debugMessages[] = [$message, 'info'];

            return;
        }

        if (!defined('CRONJOB') or CRONJOB === false) {
            $this->debugMessages[] = $message;
        }
    }

    public function getDebugMessages()
    {
        return $this->debugMessages;
    }

    /**
     * Generate the module.
     *
     * @throws \Exception
     */
    protected function compile()
    {
        $this->storageDirectoryPath = \Config::get('uploadPath').'/maklermodul/';

        // $className = '/vendor/pdir/maklermodul-bundle/src/Resources/contao/Classes/Helper.php';
        $strDomain = \Environment::get('httpHost');

        /* @todo empty cache folder from backend */
        $files = \Files::getInstance();

        switch (\Input::get('act')) {
            case 'emptyDataFolder':
                $files->rrdir($this->storageDirectoryPath.'data', true);
                $this->debugMessages[] = [$GLOBALS['TL_LANG']['MOD']['maklerSetup']['message']['emptyFolder'], 'info'];
                break;
            case 'emptyUploadFolder':
                $files->rrdir($this->storageDirectoryPath.'upload', true);
                $this->debugMessages[] = [$GLOBALS['TL_LANG']['MOD']['maklerSetup']['message']['emptyFolder'], 'info'];
                break;
            case 'emptyTmpFolder':
                $files->rrdir($this->storageDirectoryPath.'org', true);
                $this->debugMessages[] = [$GLOBALS['TL_LANG']['MOD']['maklerSetup']['message']['emptyFolder'], 'info'];
                break;
            case 'downloadDemoData':
                $this->downloadDemoData();
                $this->debugMessages[] = ['Demo Daten wurden heruntergeladen.', 'info'];
                break;
            default:
                $this->Template->base = $this->Environment->base;
                $this->Template->version = Helper::VERSION;
                $this->Template->storageDirectoryPath = $this->storageDirectoryPath;
        }

        $this->Template->extMode = static::MODE;
        $this->Template->extModeTxt = 'FULL' === static::MODE ? 'Vollversion' : 'Demo';
        $this->Template->version = Helper::VERSION;
        $this->Template->hostname = gethostname();
        $this->Template->ip = \Environment::get('server');
        $this->Template->domain = $strDomain;

        $this->Template->params = $this->configParameters; // for debug
        $this->Template->debugMessages = $this->getDebugMessages();

        // email body
        $this->Template->emailBody = $this->getEmailBody();
    }

    protected function getEmailBody()
    {
        $arrSearch = [':IP:', ':HOST:', ':DOMAIN:', '<br>'];
        $arrReplace = [$this->Template->ip, $this->Template->hostname, $this->Template->domain, '%0d%0a'];

        return str_replace($arrSearch, $arrReplace, $GLOBALS['TL_LANG']['MAKLERMODUL']['emailBody']);
    }

    protected function downloadDemoData()
    {
        $strFile = $this->storageDirectoryPath.$this->demoDataFilename;

        try {
            \File::putContent($strFile, file_get_contents('https://pdir.de/api/data/maklermodul/demo-data.zip'));
        } catch (\Exception $e) {
            \Message::addError($e->getMessage());
        }

        $this->unzipDemoData();
    }

    protected function unzipDemoData()
    {
        $objArchive = new \ZipReader($this->storageDirectoryPath.$this->demoDataFilename);

        // Extract all files
        while ($objArchive->next()) {
            // Extract the files
            try {
                \File::putContent($this->storageDirectoryPath.'data/'.$objArchive->file_name, $objArchive->unzip());
            } catch (\Exception $e) {
                \Message::addError($e->getMessage());
            }
        }

        // Regenerating Symlinks
        $this->import('Automator');
        $this->Automator->generateSymlinks();
    }
}
