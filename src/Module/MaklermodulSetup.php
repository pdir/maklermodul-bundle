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

use Contao\BackendModule;
use Contao\Database;
use Contao\Environment;
use Contao\Config;
use Contao\Controller;
use Contao\Message;
use Pdir\MaklermodulBundle\Model\MaklerModel;
use Pdir\MaklermodulBundle\Util\Helper;

class MaklermodulSetup extends BackendModule
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
    public $storageDirectoryPath;

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
        // set upload folder
        $this->storageDirectoryPath = Config::get('uploadPath').'/maklermodul/';

        // $className = '/vendor/pdir/maklermodul-bundle/src/Resources/contao/Classes/Helper.php';
        $strDomain = Environment::get('httpHost');

        /* @todo empty cache folder from backend */
        $files = \Files::getInstance();

        switch (\Input::get('act')) {
            case 'index':
                $this->setActIndexFile();
                Message::addInfo($GLOBALS['TL_LANG']['MOD']['maklerSetup']['message']['index']);
                break;
            case 'emptyDataFolder':
                $files->rrdir($this->storageDirectoryPath.'data', true);

                // truncate makler table
                $objDatabase = Database::getInstance();
                $objDatabase->execute("TRUNCATE TABLE tl_makler");

                Message::addInfo($GLOBALS['TL_LANG']['MOD']['maklerSetup']['message']['emptyFolder']);
                break;
            case 'emptyUploadFolder':
                $files->rrdir($this->storageDirectoryPath.'upload', true);
                Message::addInfo($GLOBALS['TL_LANG']['MOD']['maklerSetup']['message']['emptyFolder']);
                break;
            case 'emptyTmpFolder':
                $files->rrdir($this->storageDirectoryPath.'org', true);
                Message::addInfo($GLOBALS['TL_LANG']['MOD']['maklerSetup']['message']['emptyFolder']);
                break;
            case 'downloadDemoData':
                $this->downloadDemoData();
                Message::addInfo($GLOBALS['TL_LANG']['MOD']['maklerSetup']['message']['downloadDemoData']);
                break;
            default:
                $this->Template->base = $this->Environment->base;
                $this->Template->version = Helper::VERSION;
                $this->Template->storageDirectoryPath = $this->storageDirectoryPath;
        }

        Controller::redirect(Controller::getReferer());
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

            // add to makler table
            if (strpos($objArchive->file_name, '.json') &&
                strpos($objArchive->file_name, '00index') === false &&
                strpos($objArchive->file_name, 'key-index') === false) {
              $this->addObjectToMaklerTable(str_replace('.json', '', $objArchive->file_name));
            }
        }

        // Regenerating Symlinks
        $this->import('Automator');
        $this->Automator->generateSymlinks();
    }

    protected function addObjectToMaklerTable($slug)
    {
        /* use MaklerModel */
        $maklerModel = new MaklerModel();

        $maklerModel->name = $slug;
        $maklerModel->alias = $slug;
        $maklerModel->obid = $maklerModel->intern = $maklerModel->extern = 'demo';
        $maklerModel->visible = 1;
        $maklerModel->tstamp = time();

        // save model
        $maklerModel->save();
    }

    protected function setActIndexFile()
    {
        $objDatabase = Database::getInstance();
        $objDatabase->prepare('UPDATE tl_module SET immo_actIndexFile=? WHERE type=?')->execute('00index-demo-00001.json', 'immoListView');
    }
}
