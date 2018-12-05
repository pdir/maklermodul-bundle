<?php

/*
 * maklermodul bundle for Contao Open Source CMS
 *
 * Copyright (c) 2018 pdir / digital agentur // pdir GmbH
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

use Contao\CoreBundle\Exception\PageNotFoundException;
use Patchwork\Utf8;
use Pdir\MaklermodulBundle\Maklermodul\ContaoImpl\StaticDIC;
use Pdir\MaklermodulBundle\Maklermodul\Domain\Repository\EstateRepository;
use Pdir\MaklermodulBundle\Maklermodul\FieldRendererFactory;
use Pdir\MaklermodulBundle\Util\Helper;

/**
 * Class DetailView.
 *
 * @copyright  pdir / digital agentur
 * @author     Mathias Arzberger
 */
class DetailView extends \Module
{
    const PARAMETER_KEY = 'expose';

    /**
     * Template.
     *
     * @var string
     */
    protected $strTemplate = 'makler_details_simple';

    private $alias;

    public function __construct($objModule, $strColumn = 'main')
    {
        parent::__construct($objModule, $strColumn);

        if (!empty($this->arrData['immo_readerTemplate']) and TL_MODE !== 'BE') {
            $this->strTemplate = $this->arrData['immo_readerTemplate'];
        }
    }

    /**
     * Display a wildcard in the back end.
     *
     * @return string
     */
    public function generate()
    {
        if (TL_MODE === 'BE') {
            /** @var BackendTemplate|object $objTemplate */
            $objTemplate = new \BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### '.Utf8::strtoupper($GLOBALS['TL_LANG']['FMD']['immoDetailView'][0]).' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id='.$this->id;

            return $objTemplate->parse();
        }

        // Set auto item
        if (!isset($_GET['estate']) && \Config::get('useAutoItem') && isset($_GET['expose'])) {
            \Input::setGet('estate', \Input::get('expose'));
        }

        // get alias from auto item
        $this->alias = \Input::get('estate');

        return parent::generate();
    }

    /**
     * Generate module.
     */
    protected function compile()
    {
        /* @var PageModel $objPage */
        global $objPage;

        //$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];
        //$this->Template->referer = 'javascript:history.go(-1)';

        if ('' === $this->alias) {
            throw new PageNotFoundException('Page not found: '.\Environment::get('uri'));
        }

        if ($this->makler_useModuleDetailCss) {
            $GLOBALS['TL_CSS'][] = Helper::assetFolder.'/css/estate-detail.scss||static';
        }

        $this->Template->estate = $this->createFieldRendererFactory($this->alias);
        $this->Template->placeholderImg = $this->makler_detailViewPlaceholder ? \FilesModel::findByUuid($this->makler_detailViewPlaceholder)->path : Helper::assetFolder.'/img/platzhalterbild.jpg';
        $this->Template->showMap = $this->makler_showMap;
        $this->Template->debug = $this->makler_debug;
    }

    private function createFieldRendererFactory($objectId)
    {
        $repository = EstateRepository::getInstance();
        $estate = $repository->findByObjectId($objectId);

        return new FieldRendererFactory($estate, $this->getTranslationMap());
    }

    private function getTranslationMap()
    {
        return StaticDIC::getTranslationMap();
    }
}
