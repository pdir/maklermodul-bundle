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
namespace Pdir\MaklermodulBundle\Maklermodul\FieldRenderer;

use Pdir\MaklermodulBundle\Maklermodul\Domain\Model\Estate;
use Pdir\MaklermodulBundle\Maklermodul\FieldRenderer;
use Pdir\MaklermodulBundle\Maklermodul\FieldTranslator;

class Flag extends FieldRenderer {
    public function __construct($key, $value, FieldTranslator $translator, $yesValue, $noValue) {
        parent::__construct($key, $value, $translator);

        $this->setSetting('yesValue', $yesValue);
        $this->setSetting('noValue', $noValue);
    }

    /**
     * Rückgabe des Wertes.
     *
     * Der Wert wird so dargestellt, wie er in der Objektbeschreibung steht.
     *
     * @param bool $doNotPrint
     * @return string
     */
    public function value($doNotPrint = false) {
        $returnValue = $this->getSetting('prefix');

        if ($this->getValue() === true OR $this->getValue() == 'true' OR $this->getValue() == '1') {
            $returnValue .= $this->getSetting('yesValue');
        } else {
            $returnValue .= $this->getSetting('noValue');
        }

        $returnValue .= $this->getSetting('append');
        return $returnValue;
    }

    /**
     * Rückgabe des Wertes als Text.
     *
     * @return string
     */
    public function __toString() {
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

    private function getLongTemplate() {
        return <<<EOT
<div class="field %s">
    <div class="label">%s</div>
    <div class="value-flag %s">%s</div>
</div>
EOT;
    }

    private function getShortTemplate() {
        return <<<EOT
<div class="field value-flag %s %s">%s</div>
EOT;
    }

    private function getValueString() {
        return ($this->getValue() === true OR $this->getValue() == 'true' OR $this->getValue() == '1') ? 'true' : 'false';
    }
}
