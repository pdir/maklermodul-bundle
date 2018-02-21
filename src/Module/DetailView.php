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

use Contao\CoreBundle\Exception\PageNotFoundException;
use Patchwork\Utf8;
use Pdir\MaklermodulBundle\Maklermodul\Domain\Repository\EstateRepository;
use Pdir\MaklermodulBundle\Maklermodul\FieldRendererFactory;

/**
 * Class DetailView
 *
 * @copyright  pdir / digital agentur
 * @author     Mathias Arzberger
 * @package    maklermodul
 */
class DetailView extends \Module
{
    const PARAMETER_KEY = 'expose';

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'makler_details_simple';

    public function __construct($objModule, $strColumn = 'main') {
        parent::__construct( $objModule, $strColumn );

        if (!empty($this->arrData['immo_readerTemplate']) AND TL_MODE != 'BE') {
            $this->strTemplate = $this->arrData['immo_readerTemplate'];
        }
    }

    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            echo "<br>TEST!!!";

            /** @var BackendTemplate|object $objTemplate */
            $objTemplate = new \BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ' . Utf8::strtoupper($GLOBALS['TL_LANG']['FMD']['immoDetailView'][0]) . ' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        echo "<br>TEST2!!!";

        return parent::generate();
    }

    /**
     * Generate module
     * @return void
     */
    protected function compile()
    {
        /** @var PageModel $objPage */
        global $objPage;

        //$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];
        //$this->Template->referer = 'javascript:history.go(-1)';

        echo "<br>TEST3!!!";

        if (!isset($_GET['objectId'])) {
            $this->Template->objectData = null;
            return;
        }

        $this->Template->estate = $this->createFieldRendererFactory($_GET['objectId']);
        $this->Template->gmapApiKey = ($this->makler_gmapApiKey != '') ? $this->makler_gmapApiKey : '';
        $this->Template->placeholderImg = $this->makler_detailViewPlaceholder ? $this->makler_detailViewPlaceholder : "system/modules/makler_modul_mplus/assets/images/platzhalterbild.jpg";
    }

    private function createFieldRendererFactory($objectId) {
        $repository = EstateRepository::getInstance();
        $estate = $repository->findByObjectId($objectId);
        return new FieldRendererFactory($estate, $this->getTranslationMap());
    }

    private function getTranslationMap() {
        return StaticDIC::getTranslationMap();
    }

    /**
     * @param array $arrFragments all url parameters exploded by /
     * @return array
     *
     * @see http://de.contaowiki.org/Strukturierte_URLs
     */
    public static function hookGetPageIdFromUrl($arrFragments)
    {
        if (!$_GET['objectId']) {
            $parameterKeyFound = false;
            foreach ($arrFragments as $key => $value) {
                if ($parameterKeyFound AND $value != 'auto_item') {
                    $_GET['objectId'] = $value;
                    break;
                }

                if ($value == self::PARAMETER_KEY) {
                    $parameterKeyFound = true;
                }
            }

            if ($parameterKeyFound) {
                return array($arrFragments[0]);
            }
        }

        return $arrFragments;
    }
}
