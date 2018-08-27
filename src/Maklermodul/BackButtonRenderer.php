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
namespace Pdir\MaklermodulBundle\Maklermodul;

class BackButtonRenderer
{
    private $url;
    private $fallbackBrowserBack = false;
    private $translator;

    /**
     * @param string          $referer
     * @param string          $request i.e. $_SERVER['QUERY_STRING']
     * @param FieldTranslator $translator
     */
    public function __construct($referer, $request, FieldTranslator $translator)
    {
        $refererParts = explode('?', $referer);
        $this->fallbackBrowserBack = $this->shouldUseFallback($refererParts, $request);
        $this->url = $refererParts[0] . '?' . $request;
        $this->translator = $translator;
    }

    private function shouldUseFallback($refererParts, $request)
    {
        return (empty($refererParts) or empty($request));
    }

    public function render($doNotPrint = false)
    {
        $rendererdButton = $this->getRenderedButton();

        if ($this->fallbackBrowserBack) {
            $rendererdButton = $this->getFallbackButton();
        }

        if ($doNotPrint === false) {
            echo $rendererdButton;
        }

        return $rendererdButton;
    }

    private function getRenderedButton()
    {
        return sprintf(
            '<a href="%s">%s</a>',
            $this->url,
            $this->getDisplayName()
        );
    }

    private function getFallbackButton()
    {
        return sprintf(
            '<a href="javascript:history.back()">%s</a>',
            $this->getDisplayName()
        );
    }

    /**
     * @todo add translation support
     */
    private function getDisplayName()
    {
        return $this->translator->translate('back');
    }
}
