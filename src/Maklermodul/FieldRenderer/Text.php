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

namespace Pdir\MaklermodulBundle\Maklermodul\FieldRenderer;

use Pdir\MaklermodulBundle\Maklermodul\Domain\Model\Estate;
use Pdir\MaklermodulBundle\Maklermodul\FieldRenderer;

class Text extends FieldRenderer
{
    private $crop = false;

    /**
     * Rückgabe des Wertes als String.
     *
     * @return string
     */
    public function __toString()
    {
        if ($this->getSetting('withoutLabel')) {
            return $this->getShortString();
        }

        return $this->getFullString();
    }

    /**
     * Methode für die Ausgabe eines Textes in einer bestimmten Länge.
     *
     * @param int $length
     *
     * @return $this
     */
    public function crop($length)
    {
        $this->crop = true;
        $this->setSetting('crop', $length);

        return $this;
    }

    /**
     * Methode für die Rückgabe eines Wertes.
     *
     * @param bool $doNotPrint
     *
     * @return string
     */
    public function value($doNotPrint = false)
    {
        $returnValue = parent::value($doNotPrint);

        if ($this->crop) {
            $cropedValue = substr($returnValue, 0, $this->getSetting('crop'));

            if (false !== $cropedValue) {
                $returnValue = $cropedValue;
            }
        }

        return $returnValue;
    }

    private function getShortString()
    {
        $template = $this->getShortTemplate();

        return sprintf(
            $template,
            Estate::sanizeFileName($this->getKey()),
            $this->value(true)
        );
    }

    private function getShortTemplate()
    {
        return <<<'EOT'
<div class="field %s value-text">%s</div>

EOT;
    }

    private function getFullString()
    {
        $template = $this->getFullTemplate();

        return sprintf(
            $template,
            Estate::sanizeFileName($this->getKey()),
            $this->key(true),
            $this->value(true)
        );
    }

    private function getFullTemplate()
    {
        return <<<'EOT'
<div class="field %s">
    <div class="label">%s</div>
    <div class="value-text">%s</div>
</div>

EOT;
    }
}
