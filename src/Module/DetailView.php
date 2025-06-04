<?php

declare(strict_types=1);

/*
 * maklermodul bundle for Contao Open Source CMS
 *
 * Copyright (c) 2025 pdir / digital agentur // pdir GmbH
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

use Contao\BackendTemplate;
use Contao\Config;
use Contao\CoreBundle\Exception\PageNotFoundException;
use Contao\CoreBundle\Routing\ResponseContext\HtmlHeadBag\HtmlHeadBag;
use Contao\Environment;
use Contao\FilesModel;
use Contao\Input;
use Contao\Module;
use Contao\System;
use Pdir\MaklermodulBundle\Maklermodul\ContaoImpl\StaticDIC;
use Pdir\MaklermodulBundle\Maklermodul\Domain\Repository\EstateRepository;
use Pdir\MaklermodulBundle\Maklermodul\FieldRendererFactory;
use Pdir\MaklermodulBundle\Util\Helper;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DetailView.
 *
 * @copyright  pdir / digital agentur
 * @author     Mathias Arzberger
 */
class DetailView extends Module
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

        if (!empty($this->arrData['immo_readerTemplate']) && System::getContainer()->get('contao.routing.scope_matcher')->isFrontendRequest(System::getContainer()->get('request_stack')->getCurrentRequest() ?? Request::create(''))) {
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
        $request = System::getContainer()->get('request_stack')->getCurrentRequest();

        if ($request && System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request)) {
            /** @var BackendTemplate|object $objTemplate */
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### '.$GLOBALS['TL_LANG']['FMD']['immoDetailView'][0].' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = System::getContainer()->get('router')->generate('contao_backend').'?do=themes&amp;table=tl_module&amp;act=edit&amp;id='.$this->id;

            return $objTemplate->parse();
        }

        // Set the item from the auto_item parameter
        if (!isset($_GET['items']) && isset($_GET['auto_item']) && Config::get('useAutoItem'))
        {
            Input::setGet('items', Input::get('auto_item'));
        }

        // Return an empty string if "auto_item" is not set to combine list and reader on same page
        if (Input::get('auto_item') === null)
        {
            return '';
        }

        // get alias from auto item
        $this->alias = Input::get('auto_item');

        return parent::generate();
    }

    /**
     * Generate module.
     */
    protected function compile(): void
    {
        /** @var PageModel $objPage */
        global $objPage;

        //$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];
        //$this->Template->referer = 'javascript:history.go(-1)';

        if ('' === $this->alias) {
            throw new PageNotFoundException('Page not found: '.Environment::get('uri'));
        }

        if ($this->makler_useModuleDetailCss) {
            $GLOBALS['TL_CSS'][] = Helper::assetFolder.'/css/estate-detail.scss||static';
        }

        $estate = $this->createFieldRendererFactory($this->alias);

        $this->Template->estate = $estate;
        $this->Template->placeholderImg = $this->makler_detailViewPlaceholder ? FilesModel::findByUuid($this->makler_detailViewPlaceholder)->path : Helper::assetFolder.'/img/platzhalterbild.jpg';
        $this->Template->showMap = $this->makler_showMap;
        $this->Template->debug = $this->makler_debug;

        // image params
        $arrImgSize = unserialize($this->imgSize);
        $this->Template->attachmentSize = unserialize($this->makler_attachmentSize);
        $this->Template->detailImageType = 'image';
        $this->Template->detailImageWidth = '700';
        $this->Template->detailImageHeight = '500';
        $this->Template->detailImageMode = 'crop';

        if ('' !== $arrImgSize[2]) {
            $this->Template->detailImageWidth = $arrImgSize[0];
            $this->Template->detailImageHeight = $arrImgSize[1];
            $this->Template->detailImageSize = $arrImgSize[2];
            $this->Template->detailImageType = 'picture';

            if (!is_numeric($arrImgSize[2])) {
                // image mode: proportional, crop or box
                $this->Template->detailImageMode = $arrImgSize[2];
                $this->Template->detailImageType = 'image';
            }
        }

        // Overwrite the page metadata (see #2853, #4955 and #87)
        $responseContext = System::getContainer()->get('contao.routing.response_context_accessor')->getResponseContext();

        if ($responseContext && $responseContext->has(c::class))
        {
            /** @var HtmlHeadBag $htmlHeadBag */
            $htmlHeadBag = $responseContext->get(HtmlHeadBag::class);
            $htmlDecoder = System::getContainer()->get('contao.string.html_decoder');

            if ($estate->rawValue('freitexte.objekttitel'))
            {
                $htmlHeadBag->setTitle($estate->rawValue('freitexte.objekttitel')); // Already stored decoded
            }

            if ($estate->rawValue('freitexte.objektbeschreibung'))
            {
                $htmlHeadBag->setMetaDescription($htmlDecoder->inputEncodedToPlainText($estate->rawValue('freitexte.objektbeschreibung')));
            }

            if ($objPage->robots)
            {
                $htmlHeadBag->setMetaRobots($objPage->robots);
            }
        }
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
