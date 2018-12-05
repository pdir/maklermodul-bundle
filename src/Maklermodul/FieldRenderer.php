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

namespace Pdir\MaklermodulBundle\Maklermodul;

abstract class FieldRenderer
{
    /**
     * @var FieldTranslator
     */
    protected $translator;
    private $key;

    private $value;

    private $settings;

    public function __construct($key, $value, FieldTranslator $translator)
    {
        $this->translator = $translator;
        $this->key = $key;
        $this->value = $value;
        $this->settings = [
            'withoutLabel' => false,
            'append' => '',
            'prefix' => '',
            //'group'	=> 'BILD',
            // 'location' => '',
            // 'mode' => 'crop'
        ];
    }

    abstract public function __toString();

    /**
     * Methode zum holen des Index eines Wertes.
     *
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Methode zum holen eines Wertes.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Prüft, ob Wert ohne Label dargestellt werden soll.
     *
     * @return mixed
     */
    public function isWithoutLabel()
    {
        return $this->getSetting('withoutLabel');
    }

    /**
     * Objektwert wird ohne Label/Name/Bezeichner ausgegeben.
     *
     * @return $this
     */
    public function withoutLabel()
    {
        $this->setSetting('withoutLabel', true);

        return $this;
    }

    /**
     * Ermöglicht das Anhängen von Wörtern und Zahlen.
     *
     * Zum Beispiel eine Einheit bei der Flächenangabe oder bei Preise die Währung.
     *
     * @param $suffix
     *
     * @return $this
     */
    public function append($suffix)
    {
        $this->setSetting('append', $suffix);

        return $this;
    }

    /**
     * Methode, um vor dem Wert ein Label/Text einzufügen.
     *
     * @param string $prefix
     *
     * @return string
     */
    public function prefix($prefix)
    {
        $this->setSetting('prefix', $prefix);

        return $this;
    }

    /**
     * Rückgabe des Index eines Wertes.
     *
     * @param bool $doNotPrint
     *
     * @return mixed
     */
    public function key($doNotPrint = false)
    {
        $returnValue = $this->translator->translate($this->getKey());

        if (!$doNotPrint) {
            echo $returnValue;
        }

        return $returnValue;
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
        $returnValue = $this->getSetting('prefix');
        $returnValue .= $this->getValue();
        $returnValue .= $this->getSetting('append');

        if (!$doNotPrint) {
            echo $returnValue;
        }

        return $returnValue;
    }

    /**
     * Verarbeitet die Darstellung der Ausgabe.
     *
     * Der Wert mit Beschreibung wird nicht in der Detailansicht angezeigt, wenn keine Daten vorhanden sind.
     *
     * @param bool $doNotPrint
     *
     * @return string
     */
    public function render($doNotPrint = false)
    {
        $returnValue = '';
        $value = $this->getValue();

        if (!empty($value)) {
            $returnValue = $this->__toString();
        }

        if (!$doNotPrint) {
            echo $returnValue;
        }

        return $returnValue;
    }

    /**
     * Verarbeitet die Darstellung der Ausgabe.
     *
     * Dieser Objektdetail wird immer angezeigt, egal ob der Wert 0 ist oder keinen Wert besitzt.
     *
     * @param bool $doNotPrint
     *
     * @return mixed
     */
    public function renderAlways($doNotPrint = false)
    {
        $returnValue = $this->__toString();

        if (!$doNotPrint) {
            echo $returnValue;
        }

        return $returnValue;
    }

    protected function setSetting($key, $value)
    {
        $this->settings[$key] = $value;
    }

    protected function getSetting($key)
    {
        if (!isset($this->settings[$key])) {
            throw new \InvalidArgumentException("Unknown key: $key");
        }

        return $this->settings[$key];
    }
}
