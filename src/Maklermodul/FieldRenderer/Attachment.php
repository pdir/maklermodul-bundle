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

namespace Pdir\MaklermodulBundle\Maklermodul\FieldRenderer;

use Contao\FrontendTemplate;
use Contao\Image;
use Contao\StringUtil;
use Contao\System;
use Pdir\MaklermodulBundle\Maklermodul\FieldRenderer;
use Pdir\MaklermodulBundle\Maklermodul\FieldTranslator;
use Pdir\MaklermodulBundle\Util\Helper;

class Attachment extends FieldRenderer
{
    public $websitePath;

    /*
     * Element Template
     * @var string
     */
    public $template;

    public function __construct($key, $value, FieldTranslator $translator, $group = 'BILD')
    {
        parent::__construct(
            $key,
            $value,
            $translator
        );

        if (isset($GLOBALS['TL_CONFIG']['websitePath'])) {
            $this->websitePath = $GLOBALS['TL_CONFIG']['websitePath'];
        }

        // set default values for images
        $this->setSetting('group', $group);
        $this->setSetting('location', '');
        $this->setSetting('link', false);
        $this->setSetting('mode', 'crop');
    }

    /**
     * Rückgabe des Wertes als Text.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getShortString();
    }

    /**
     * Methode zum Setzen der Bildbreite und -höhe.
     *
     * @param int $maxWidth
     * @param int $maxHeight
     *
     * @return $this
     */
    public function size($maxWidth, $maxHeight, $size = ['', '', ''])
    {
        $this->setSetting('maxWidth', $maxWidth);
        $this->setSetting('maxHeight', $maxHeight);
        $this->setSetting('size', $size);

        return $this;
    }

    /**
     * Methode zum setzen der Anhang Gruppe.
     *
     * @param string $attGroup
     *
     * @return $this;
     */
    public function group($attGroup)
    {
        $this->setSetting('group', $attGroup);

        return $this;
    }

    /**
     * Methode zum setzen der Anhang Location.
     *
     * @param string $attLocation
     *
     * @return $this;
     */
    public function location($attLocation)
    {
        $this->setSetting('location', $attLocation);

        return $this;
    }

    /**
     * Methode zum Setzen der Resize Mode.
     *
     * @param string $mode
     *
     * @return $this
     */
    public function mode($mode)
    {
        $this->setSetting('mode', $mode);

        return $this;
    }

    /**
     * Methode um Bilder ohne Link anzuzeigen.
     *
     * @param string $link
     *
     * @return $this
     */
    public function withoutLink($link)
    {
        $this->setSetting('link', $link);

        return $this;
    }

    /**
     * @noinspection PhpInconsistentReturnPointsInspection
     */
    private function getShortString()
    {
        // show only given group
        $givenGroups = StringUtil::trimsplit(',', $this->getSetting('group'));
        $group = $this->getValueOf('@gruppe');

        if ('' === $group && \in_array($this->getValueOf('format'), ['jpg', 'png', 'svg', 'webp'], true)) {
            $group = 'BILD';
        }

        if (empty($givenGroups) || \in_array($group, $givenGroups, true)) {
            switch ($group) {
                case 'DOKUMENTE':
                    // render doc list
                    $this->template = $this->getShortTemplateDoc();

                    return sprintf($this->template,
                        $this->getUrlOfPath(Helper::getImageLinkPath().$this->getValueOf('daten.pfad')),
                        substr($this->getValueOf('format'), 1),
                        $this->getValueOf('anhangtitel'),
                        $this->getValueOf('anhangtitel')
                    );
                    break;

                case 'FILMLINK':
                    // render doc list
                    $this->template = $this->getShortTemplateDoc();

                    return sprintf($this->template,
                            $this->getUrlOfPath($this->getValueOf('daten.pfad')),
                            substr($this->getValueOf('format'), 1),
                            $this->getValueOf('anhangtitel'),
                            $this->getValueOf('anhangtitel')
                    );
                    break;

                case 'FILM':
                    $this->template = $this->getShortTemplateMedia();

                    return sprintf($this->template,
                        substr($this->getValueOf('format'), 1),
                        $this->getUrlOfPath(Helper::getImageLinkPath().$this->getValueOf('daten.pfad')),
                        $this->getValueOf('anhangtitel'),
                        $this->getValueOf('anhangtitel')
                    );
                    break;

                default:
                    // fallback for xml data without group definition

                    // render as doc/link
                    if ('pdf' === $this->getValueOf('format') || 'application/pdf' === $this->getValueOf('format') || 'doc' === $this->getValueOf('format')) {
                        $this->template = $this->getShortTemplateDoc();

                        return sprintf($this->template,
                            $this->getUrlOfPath(Helper::getImageLinkPath().$this->getValueOf('daten.pfad')),
                            substr($this->getValueOf('format'), 1),
                            $this->getValueOf('anhangtitel'),
                            $this->getValueOf('anhangtitel')
                        );
                    }

                    // render as image
                    $renderedThumbnail = $this->getThumbnailString(
                        $this->getValueOf('daten.pfad'),
                        $this->getValueOf('anhangtitel'),
                        $this->getValueOf('@location')
                    );

                    if ($this->getSetting('link')) {
                        return $renderedThumbnail;
                    }

                    $this->template = $this->getShortTemplate();

                    if (false !== strpos($this->getValueOf('daten.pfad'), 'http')) {
                        return sprintf($this->template,
                            $this->getUrlOfPath($this->getValueOf('daten.pfad')),
                            $this->getValueOf('anhangtitel'),
                            $renderedThumbnail
                        );
                    }

                    return sprintf($this->template,
                        $this->getUrlOfPath(Helper::getImageLinkPath().$this->getValueOf('daten.pfad')),
                        $this->getValueOf('anhangtitel'),
                        $renderedThumbnail
                    );
            }
        }

        return '';
    }

    private function getUrlOfPath($path)
    {
        if (isset($this->websitePath)) {
            return $this->websitePath.'/'.str_replace(TL_ROOT, '', $path);
        }

        if (null === $path) {
            return null;
        }

        return str_replace(TL_ROOT, '', $path);
    }

    private function getThumbnailString($path, $alt, $location)
    {
        try {
            $width = $this->getSetting('maxWidth');
            $height = $this->getSetting('maxHeight');
            $size = $this->getSetting('size');

            if ('' === $size[0]) {
                $size[0] = $width;
            }

            if ('' === $size[1]) {
                $size[1] = $height;
            }

            $url = $path;
            // @todo make it changeable in the config file
            if ('REMOTE' === $location) {
                $url = str_replace('.jpg', '_small.jpg', $url);

                $this->template = $this->getThumbnailTemplate(true);

                return sprintf($this->template, $url, $size[0], $size[1], $alt);
            }

            // use image factory if mode is set
            if (!is_numeric($size[2])) {
                $path = $this->resizeImage($path, $size);
                $url = $this->getUrlOfPath($path);

                $this->template = $this->getThumbnailTemplate(true);

                return sprintf($this->template, $url, $size[0], $size[1], $alt);
            }

            return $this->resizePicture($path, $size, $alt);
        } catch (\InvalidArgumentException $e) {
            $this->template = $this->getThumbnailTemplate();
            $url = $path;
            // @todo make it changeable in the config file
            if ('REMOTE' === $location) {
                $url = str_replace('.jpg', '_small.jpg', $url);
            }

            if ('EXTERN' === $location || 'INTERN' === $location) {
                $url = $this->getUrlOfPath($path);
            }

            return sprintf($this->template, $url, $alt);
        }
    }

    private function resizeImage($orgPath, $size)
    {
        return Image::get(Helper::getImagePath().$orgPath, $size[0], $size[1], $size[2]);
    }

    private function resizePicture($orgPath, $size, $alt)
    {
        $container = System::getContainer();
        $rootDir = $container->getParameter('kernel.project_dir');
        $imagePath = Helper::getImagePath().$orgPath;

        $picture = $container
            ->get('contao.image.picture_factory')
            ->create($rootDir.'/'.$imagePath, $size[2])
        ;

        $staticUrl = $container->get('contao.assets.files_context')->getStaticUrl();
        $picture = [
            'img' => $picture->getImg($container->getParameter('kernel.project_dir'), $staticUrl),
            'sources' => $picture->getSources($container->getParameter('kernel.project_dir'), $staticUrl),
        ];

        $picture['alt'] = $alt;

        $pictureTemplate = new FrontendTemplate('picture_default');
        $pictureTemplate->setData($picture);

        return $pictureTemplate->parse();
    }

    private function getThumbnailTemplate($resized = false)
    {
        $returnValue = '<img src="%s"'.PHP_EOL;

        if ($resized) {
            $returnValue .= '        width="%s" height="%s" alt="%s" />';
        } else {
            $returnValue .= '        alt="%s" />';
        }

        return $returnValue;
    }

    private function getShortTemplate()
    {
        $returnValue = '<a href="%s"'.PHP_EOL;
        $returnValue .= '   data-lightbox="galerie" title="%s">'.PHP_EOL;
        $returnValue .= '   %s'.PHP_EOL;
        $returnValue .= '</a>'.PHP_EOL;

        return $returnValue;
    }

    private function getValueOf($key)
    {
        $returnValue = '';
        $value = $this->getValue();
        $key = $this->getFullyQualifiedKey($key);

        if (isset($value[$key])) {
            $returnValue = $value[$key];
        }

        return $returnValue;
    }

    private function getFullyQualifiedKey($key)
    {
        return $this->getKey().'.'.$key;
    }

    private function getShortTemplateDoc()
    {
        $returnValue = '<li><a href="%s"'.PHP_EOL;
        $returnValue .= '   class="link-icon %s" title="Download %s" target="_blank">'.PHP_EOL;
        $returnValue .= '   %s'.PHP_EOL;
        $returnValue .= '</a></li>'.PHP_EOL;

        return $returnValue;
    }

    private function getShortTemplateMedia()
    {
        $returnValue = '<div class="ce_player">';
        $returnValue .= '<video width="640" height="360" controls>';
        $returnValue .= '<source type="video/%s" src="%s">';
        $returnValue .= '</video>';
        $returnValue .= '</div>';

        return $returnValue;
    }
}
