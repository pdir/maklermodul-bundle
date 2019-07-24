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

namespace Pdir\MaklermodulBundle\Maklermodul\FieldRenderer;

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

        if ($GLOBALS['TL_CONFIG']['websitePath']) {
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
    public function size($maxWidth, $maxHeight)
    {
        $this->setSetting('maxWidth', $maxWidth);
        $this->setSetting('maxHeight', $maxHeight);

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

    /** @noinspection PhpInconsistentReturnPointsInspection */
    private function getShortString()
    {
        // show only given group
        if (false !== strpos($this->getSetting('group'), $this->getValueOf('@gruppe'))) {
            switch ($this->getValueOf('@gruppe')) {
                case 'DOKUMENTE':
                    // render doc list
                    $this->template = $this->getShortTemplateDoc();

                    return sprintf($this->template,
                        $this->getUrlOfPath(Helper::imagePath.$this->getValueOf('daten.pfad')),
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
                        $this->getUrlOfPath(Helper::imagePath.$this->getValueOf('daten.pfad')),
                        $this->getValueOf('anhangtitel'),
                        $this->getValueOf('anhangtitel')
                    );
                    break;
                default:
                    // fallback for xml data without group definition

                    // render as doc/link
                    if($this->getValueOf('format') === 'pdf' || $this->getValueOf('format') === 'doc') {
                        $this->template = $this->getShortTemplateDoc();

                        return sprintf($this->template,
                            $this->getUrlOfPath(Helper::imagePath.$this->getValueOf('daten.pfad')),
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

                    if( strpos($this->getValueOf('daten.pfad'),"http") !== false ) {
                        return sprintf($this->template,
                            $this->getUrlOfPath($this->getValueOf('daten.pfad')),
                            $this->getValueOf('anhangtitel'),
                            $renderedThumbnail
                        );
                    } else {
                        return sprintf($this->template,
                            $this->getUrlOfPath(Helper::imagePath.$this->getValueOf('daten.pfad')),
                            $this->getValueOf('anhangtitel'),
                            $renderedThumbnail
                        );
                    }
            }
        }

        return '';
    }

    private function getUrlOfPath($path)
    {
        if ($this->websitePath) {
            return $this->websitePath.'/'.str_replace(TL_ROOT, '', $path);
        }

        return str_replace(TL_ROOT, '', $path);
    }

    private function getThumbnailString($path, $alt, $location)
    {
        try {
            $width = $this->getSetting('maxWidth');
            $height = $this->getSetting('maxHeight');
            $mode = $this->getSetting('mode');

            $url = $path;
            // @todo make it changeable in the config file
            if ('REMOTE' === $location) {
                $url = str_replace('.jpg', '_small.jpg', $url);
            }
            if ('EXTERN' === $location) {
                $path = $this->resizeImage($path, $width, $height, $mode);
                $url = $this->getUrlOfPath($path);
            }
            $this->template = $this->getThumbnailTemplate(true);
            $result = sprintf($this->template, $url, $width, $height, $alt);

            return $result;
        } catch (\InvalidArgumentException $e) {
            $this->template = $this->getThumbnailTemplate();
            $url = $path;
            // @todo make it changeable in the config file
            if ('REMOTE' === $location) {
                $url = str_replace('.jpg', '_small.jpg', $url);
            }
            if ('EXTERN' === $location) {
                $url = $this->getUrlOfPath($path);
            }

            return sprintf($this->template, $url, $alt);
        }
    }

    private function resizeImage($orgPath, $maxWidth, $maxHeight, $mode)
    {
        return \Image::get(Helper::imagePath.$orgPath, $maxWidth, $maxHeight, $mode);
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
