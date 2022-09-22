<?php

declare(strict_types=1);

/*
 * maklermodul bundle for Contao Open Source CMS
 *
 * Copyright (c) 2022 pdir / digital agentur // pdir GmbH
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
use Pdir\MaklermodulBundle\Maklermodul\FieldTranslator;

class Flag extends FieldRenderer
{
    public function __construct($key, $value, FieldTranslator $translator, $yesValue, $noValue)
    {
        parent::__construct($key, $value, $translator);

        $this->setSetting('yesValue', $yesValue);
        $this->setSetting('noValue', $noValue);
    }

    /**
     * Rückgabe des Wertes als Text.
     *
     * @return string
     */
    public function __toString()
    {
        if ($this->getSetting('withoutLabel')) {
            $template = $this->getShortTemplate();

            return sprintf($template,
                $this->getValueString(),
                Estate::sanizeFileName($this->getKey()),
                $this->value(true)
            );
        }

        $template = $this->getLongTemplate();

        return sprintf($template,
            Estate::sanizeFileName($this->getKey()),
            $this->key(true),
            $this->getValueString(),
            $this->value(true)
        );
    }

    /**
     * Rückgabe des Wertes.
     *
     * Der Wert wird so dargestellt, wie er in der Objektbeschreibung steht.
     *
     * @param bool $doNotPrint
     *
     * @return string
     */
    public function value($doNotPrint = false)
    {
        $returnValue = $this->getSetting('prefix');

        if (true === $this->getValue() || 'true' === $this->getValue() || '1' === $this->getValue() || 'Ja' === $this->getValue() || 'JA' === $this->getValue()) {
            $returnValue .= $this->getSetting('yesValue');
        } else {
            $returnValue .= $this->getSetting('noValue');
        }

        $returnValue .= $this->getSetting('append');

        return $returnValue;
    }

    private function getLongTemplate()
    {
        return <<<'EOT'
<div class="field %s">
    <div class="label">%s</div>
    <div class="value-flag %s">%s</div>
</div>
EOT;
    }

    private function getShortTemplate()
    {
        return <<<'EOT'
<div class="field value-flag %s %s">%s</div>
EOT;
    }

    private function getValueString()
    {
        return true === $this->getValue() || 'true' === $this->getValue() || '1' === $this->getValue() ? 'true' : 'false';
    }
}
