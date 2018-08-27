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

class FieldRendererFactory
{
    private $estateData;
    private $translationMap;

    public function __construct($estateData, $translationMap)
    {
        $this->estateData = $estateData;
        $this->translationMap = new FieldTranslator($translationMap);
    }

    /**
     * Holt den Wert anhand des Parameters aus der Objektbeschreibung.
     *
     * Parameter kann zum Beispiel der Wert für die Kaltmiete oder der Preis für
     * das Objekt sein.
     *
     * @param String $key
     * @return \Pdir\MaklermodulBundle\Maklermodul\FieldRendererTypeSelector
     */
    public function renderer($key)
    {
        return new FieldRendererTypeSelector($key, $this->rawValue($key), $this->translationMap);
    }

    /**
     * Gibt den Wert zurück, so wie er vom Immobilientool übertragen wurde.
     *
     * $key - Index des Wertes im Array
     *
     * @param $key
     * @return string
     */
    public function rawValue($key)
    {
        if (is_a($this->estateData, '\Pdir\MaklermodulBundle\Maklermodul\Domain\Model\Estate')) {
            return $this->estateData->getValueOf($key);
        }

        return $this->getRawValueOfArray($key);
    }

    private function getRawValueOfArray($key)
    {
        if (isset($this->estateData[$key])) {
            return $this->estateData[$key];
        }

        return null;
    }

    /**
     * Prüft ob ein ein bestimmtes Feld vorhanden ist.
     *
     * $key - Index des Wertes im Array
     *
     * @param $key
     * @return string
     */
    public function keyExists($key)
    {
        if (is_a($this->estateData, '\Pdir\MaklermodulBundle\Maklermodul\Domain\Model\Estate')) {
            return $this->estateData->checkIfKeyExists($key);
        }

        return $this->getRawValueOfArray($key);
    }

    /**
     * Methode für die Rückgabe eines Arrays mit Bildern
     *
     * @return \Pdir\MaklermodulBundle\Maklermodul\AttachmentFieldRendererCollection
     */
    public function attachments()
    {
        return new AttachmentFieldRendererCollection($this->getFilteredValuesBy('anhaenge'), $this->translationMap);
    }

    private function getFilteredValuesBy($startOfKey)
    {
        $returnValue = array();
        $estateData = $this->estateData;

        if (is_a($estateData, '\Pdir\MaklermodulBundle\Maklermodul\Domain\Model\Estate')) {
            $estateData = $estateData->getFieldsIterator();
        }
        if (!$estateData) {
            return array();
        }
        foreach ($estateData as $key => $value) {
            if ($startOfKey === "" || strpos($key, $startOfKey) === 0) {
                $returnValue[$key] = $value;
            }
        }

        return $returnValue;
    }

    /**
     *
     * Liefert einen Renderer zurück, der einen Zurück Button erzeugen kann.
     *
     * Dieser Button berücksichtigt die Filtereinstellung vor Aufruf der
     * Detailansicht.
     * Achtung diese Funktion nutzt $_SERVER['HTTP_REFERER'], $_SERVER['QUERY_STRING']
     *
     * @returns \Pdir\MaklermodulBundle\Maklermodul\BackButtonRenderer
     */
    public function backButton()
    {
        $referer = $_SERVER['HTTP_REFERER'];
        $request = $_SERVER['QUERY_STRING'];

        return new BackButtonRenderer($referer, $request, $this->translationMap);
    }

    /**
     * Methode für die Rückgabe der Energiepassangaben
     *
     * @return \Pdir\MaklermodulBundle\Maklermodul\EnergyPassRenderer
     */
    public function energyPass()
    {
        return new EnergyPassRenderer($this->getFilteredValuesBy('zustand_angaben'), $this->translationMap, $this->getFilteredValuesBy('ausstattung'));
    }
}
