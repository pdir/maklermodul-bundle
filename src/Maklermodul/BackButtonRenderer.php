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

namespace Pdir\MaklermodulBundle\Maklermodul;

class BackButtonRenderer
{
    private $url;
    private $fallbackBrowserBack = false;
    private $translator;

    /**
     * @param string $referer
     * @param string $request i.e. $_SERVER['QUERY_STRING']
     */
    public function __construct($referer, $request, FieldTranslator $translator)
    {
        $refererParts = explode('?', $referer);
        $this->fallbackBrowserBack = $this->shouldUseFallback($refererParts, $request);
        $this->url = $refererParts[0].'?'.$request;
        $this->translator = $translator;
    }

    public function render($doNotPrint = false)
    {
        $rendererdButton = $this->getRenderedButton();

        if ($this->fallbackBrowserBack) {
            $rendererdButton = $this->getFallbackButton();
        }

        if (false === $doNotPrint) {
            echo $rendererdButton;
        }

        return $rendererdButton;
    }

    private function shouldUseFallback($refererParts, $request)
    {
        return empty($refererParts) || empty($request);
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
