<?php

declare(strict_types=1);

/*
 * maklermodul bundle for Contao Open Source CMS
 *
 * Copyright (c) 2023 pdir / digital agentur // pdir GmbH
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
use Contao\Environment;
use Contao\FilesModel;
use Contao\Input;
use Contao\Module;
use Pdir\MaklermodulBundle\Maklermodul\ContaoImpl\StaticDIC;
use Pdir\MaklermodulBundle\Maklermodul\Domain\Repository\EstateRepository;
use Pdir\MaklermodulBundle\Maklermodul\FieldRendererFactory;
use Pdir\MaklermodulBundle\Util\Helper;

/**
 * Class DetailView.
 *
 * @copyright  pdir / digital agentur
 * @author     Mathias Arzberger <develop@pdir.de>
 */
class HeaderImageView extends Module
{
    /**
     * Template.
     *
     * @var string
     */
    protected $strTemplate = 'makler_header_image';

    public function __construct($objModule, $strColumn = 'main')
    {
        parent::__construct($objModule, $strColumn);
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
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### '.$GLOBALS['TL_LANG']['FMD']['immoHeaderImageView'][0].' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id='.$this->id;

            return $objTemplate->parse();
        }

        // Set auto item
        if (!isset($_GET['estate']) && Config::get('useAutoItem') && isset($_GET['expose'])) {
            Input::setGet('estate', Input::get('expose'));
        }

        // get alias from auto item
        $this->alias = Input::get('estate');

        return parent::generate();
    }

    /**
     * Generate the module.
     */
    protected function compile(): void
    {
        if ('' === $this->alias) {
            throw new PageNotFoundException('Page not found: '.Environment::get('uri'));
        }

        $estate = $this->createFieldRendererFactory($this->alias);

        if ('1' === $this->makler_showHeadline) {
            $this->Template->headline = $this->headline ?: $estate->rawValue('freitexte.objekttitel');
            $this->Template->showHeadline = true;
        }

        $this->Template->showBackgroundImage = $this->makler_showBackgroundImage;

        $imageIndex = $this->makler_headerImageSource ?: 2;

        if ($estate->rawValue('anhaenge.anhang.#'.$imageIndex.'.daten.pfad')) {
            if (false !== strpos($estate->rawValue('anhaenge.anhang.#'.$imageIndex.'.daten.pfad'), 'http')) {
                $this->Template->headerImage = $estate->rawValue('anhaenge.anhang.#'.$imageIndex.'.daten.pfad');
            } else {
                $this->Template->headerImage = Helper::getImageLinkPath().$estate->rawValue('anhaenge.anhang.#'.$imageIndex.'.daten.pfad');
            }
        } else {
            $placeholder = $this->makler_headerImagePlaceholder ?: Helper::assetFolder.'/img/platzhalterbild.jpg';

            if ($placeholder !== Helper::assetFolder.'/img/platzhalterbild.jpg') {
                $objFile = FilesModel::findByUuid($placeholder);
                $this->Template->headerImage = $objFile->path;
            } else {
                $this->Template->headerImage = $placeholder;
            }
        }

        // image params
        $arrImgSize = $this->imgSize ? unserialize($this->imgSize) : unserialize($this->size);
        $this->Template->imageType = 'image';
        $this->Template->imageWidth = '1920';
        $this->Template->imageHeight = '800';
        $this->Template->imageMode = 'crop';

        if ('' !== $arrImgSize[2]) {
            $this->Template->imageWidth = $arrImgSize[0];
            $this->Template->imageHeight = $arrImgSize[1];
            $this->Template->imageSize = $arrImgSize[2];
            $this->Template->imageType = 'picture';

            if (!is_numeric($arrImgSize[2])) {
                // image mode: proportional, crop or box
                $this->Template->imageMode = $arrImgSize[2];
                $this->Template->imageType = 'image';
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
