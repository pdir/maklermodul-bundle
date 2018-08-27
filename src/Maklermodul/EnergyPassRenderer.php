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

/*
 * Class for energy pass renderer
 *
 * @property string $baujahr
 * @property string $stromwert
 * @property string $waermewert
 * @property string $mitwarmwasser
 * @property string $gueltigBis
 */
class EnergyPassRenderer
{
    const LIST_PREFIX = 'epass.';
    const DEFAULT_TEMPLATE = 'makler_detail_energy_pass';

    private $data;
    private $rawData;
    private $translator;
    private $ausstattungRawData;

    /*
     * Energy pass type
     * @var string
     */
    public $epart;

    /*
    * Energy pass value
    * @var string
    */
    public $energie;

    /*
     * Heating type
     * @var string
     */
    public $heizung;

    /*
     * Energy source type
     * @var string
     */
    public $energietraeger;

    /*
     * Energy CSS class value
     * @var string
     */
    public $eClass;

    public function __construct($rawData, FieldTranslator $translator, $ausstattungRawData)
    {
        $this->rawData = $rawData;
        $this->translator = $translator;
        $this->ausstattungRawData = $ausstattungRawData;
        $this->parseRawData();
    }

    protected function parseRawData()
    {
        $this->data = array();

        foreach ($this->rawData as $key => $value) {
            $parsedKey = str_replace(self::LIST_PREFIX, '', $key);
            list($id, $parsedKey) = explode('.', $parsedKey, 2);
            $this->setData($id, $parsedKey, $value);
        }
    }

    private function setData($id, $key, $value)
    {
        $id = self::LIST_PREFIX . $id;

        if (!isset($this->data[$id])) {
            $this->data[$id] = array();
        }

        $this->data[$id]["$id.$key"] = $value;
    }

    // render energy pass
    public function render()
    {
        //epart
        if ($this->rawData["zustand_angaben.energiepass.epart"] == 'VERBRAUCH') {
            $epart = 'Verbrauch';
        } elseif ($this->rawData["zustand_angaben.energiepass.epart"] == 'BEDARF') {
            $epart = 'Bedarf';
        } else {
            $epart = 'Nicht notwendig';
        }

        // heizung & energietraeger
        if ($this->ausstattungRawData["ausstattung.heizungsart.@OFEN"] == 'true' || $this->ausstattungRawData["ausstattung.heizungsart.@OFEN"] == '1') {
            $heizung = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.heizungsart.@OFEN'];
        }
        if ($this->ausstattungRawData["ausstattung.heizungsart.@ETAGE"] == 'true' || $this->ausstattungRawData["ausstattung.heizungsart.@ETAGE"] == '1') {
            $heizung = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.heizungsart.@ETAGE'];
        }
        if ($this->ausstattungRawData["ausstattung.heizungsart.@ZENTRAL"] == 'true' || $this->ausstattungRawData["ausstattung.heizungsart.@ZENTRAL"] == '1') {
            $heizung = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.heizungsart.@ZENTRAL'];
        }
        if ($this->ausstattungRawData["ausstattung.heizungsart.@FERN"] == 'true' || $this->ausstattungRawData["ausstattung.heizungsart.@FERN"] == '1') {
            $heizung = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.heizungsart.@FERN'];
        }
        if ($this->ausstattungRawData["ausstattung.heizungsart.@FUSSBODEN"] == 'true' || $this->ausstattungRawData["ausstattung.heizungsart.@FUSSBODEN"] == '1') {
            $heizung = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.heizungsart.@FUSSBODEN'];
        }

        if ($this->ausstattungRawData["ausstattung.befeuerung.@OEL"] == 'true' || $this->ausstattungRawData["ausstattung.befeuerung.@OEL"] == '1') {
            $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@OEL'];
        }
        if ($this->ausstattungRawData["ausstattung.befeuerung.@GAS"] == 'true' || $this->ausstattungRawData["ausstattung.befeuerung.@GAS"] == '1') {
            $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@GAS'];
        }
        if ($this->ausstattungRawData["ausstattung.befeuerung.@ELEKTRO"] == 'true' || $this->ausstattungRawData["ausstattung.befeuerung.@ELEKTRO"] == '1') {
            $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@ELEKTRO'];
        }
        if ($this->ausstattungRawData["ausstattung.befeuerung.@ALTERNATIV"] == 'true' || $this->ausstattungRawData["ausstattung.befeuerung.@ALTERNATIV"] == '1') {
            $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@ALTERNATIV'];
        }
        if ($this->ausstattungRawData["ausstattung.befeuerung.@SOLAR"] == 'true' || $this->ausstattungRawData["ausstattung.befeuerung.@SOLAR"] == '1') {
            $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@SOLAR'];
        }
        if ($this->ausstattungRawData["ausstattung.befeuerung.@ERDWAERME"] == 'true' || $this->ausstattungRawData["ausstattung.befeuerung.@ERDWAERME"] == '1') {
            $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@ERDWAERME'];
        }
        if ($this->ausstattungRawData["ausstattung.befeuerung.@LUFTWP"] == 'true' || $this->ausstattungRawData["ausstattung.befeuerung.@LUFTWP"] == '1') {
            $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@LUFTWP'];
        }
        if ($this->ausstattungRawData["ausstattung.befeuerung.@FERN"] == 'true' || $this->ausstattungRawData["ausstattung.befeuerung.@FERN"] == '1') {
            $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@FERN'];
        }
        if ($this->ausstattungRawData["ausstattung.befeuerung.@BLOCK"] == 'true' || $this->ausstattungRawData["ausstattung.befeuerung.@BLOCK"] == '1') {
            $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@BLOCK'];
        }
        if ($this->ausstattungRawData["ausstattung.befeuerung.@WASSER-ELEKTRO"] == 'true' || $this->ausstattungRawData["ausstattung.befeuerung.@WASSER-ELEKTRO"] == '1') {
            $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@WASSER-ELEKTRO'];
        }
        if ($this->ausstattungRawData["ausstattung.befeuerung.@PELLET"] == 'true' || $this->ausstattungRawData["ausstattung.befeuerung.@PELLET"] == '1') {
            $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@PELLET'];
        }
        if ($this->ausstattungRawData["ausstattung.befeuerung.@KOHLE"] == 'true' || $this->ausstattungRawData["ausstattung.befeuerung.@KOHLE"] == '1') {
            $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@KOHLE'];
        }
        if ($this->ausstattungRawData["ausstattung.befeuerung.@HOLZ"] == 'true' || $this->ausstattungRawData["ausstattung.befeuerung.@HOLZ"] == '1') {
            $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@HOLZ'];
        }
        if ($this->ausstattungRawData["ausstattung.befeuerung.@FLUESSIGGAS"] == 'true' || $this->ausstattungRawData["ausstattung.befeuerung.@FLUESSIGGAS"] == '1') {
            $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@FLUESSIGGAS'];
        }

        // energy
        $wertklasse = $this->rawData["zustand_angaben.energiepass.wertklasse"];
        $jahrgang = $this->rawData["zustand_angaben.energiepass.jahrgang"];
        $ausstelldatum = $this->rawData["zustand_angaben.energiepass.ausstelldatum"];
        $gebaeudeart = $this->rawData["zustand_angaben.energiepass.gebaeudeart"];

        if ($this->rawData["zustand_angaben.energiepass.waermewert"]) {
            $energie = $this->rawData["zustand_angaben.energiepass.waermewert"];
        } elseif ($this->rawData["zustand_angaben.energiepass.energieverbrauchkennwert"]) {
            $energie = $this->rawData["zustand_angaben.energiepass.energieverbrauchkennwert"];
        } else {
            $energie = $this->rawData["zustand_angaben.energiepass.endenergiebedarf"];
        }
        $energie = str_replace(",", ".", $energie);

        if ($wertklasse == "") {
            if ($energie >= 0 and $energie < 30) {
                $eClass = "A+";
            }
            if ($energie >= 30 and $energie < 50) {
                $eClass = "A";
            }
            if ($energie >= 50 and $energie < 75) {
                $eClass = "B";
            }
            if ($energie >= 75 and $energie < 100) {
                $eClass = "C";
            }
            if ($energie >= 100 and $energie < 130) {
                $eClass = "D";
            }
            if ($energie >= 130 and $energie < 160) {
                $eClass = "E";
            }
            if ($energie >= 160 and $energie < 200) {
                $eClass = "F";
            }
            if ($energie >= 200 and $energie < 250) {
                $eClass = "G";
            }
            if ($energie >= 250) {
                $eClass = "H";
            }
        } else {
            $eClass = $wertklasse;
        }

        $objFilterTemplate = new \FrontendTemplate(self::DEFAULT_TEMPLATE);

        $objFilterTemplate->epart = $epart;
        $objFilterTemplate->baujahr = $this->rawData["zustand_angaben.baujahr"];
        $objFilterTemplate->stromwert = $this->rawData["zustand_angaben.energiepass.stromwert"];
        $objFilterTemplate->waermewert = $this->rawData["zustand_angaben.energiepass.waermewert"];
        $objFilterTemplate->mitwarmwasser = ($this->rawData["zustand_angaben.energiepass.mitwarmwasser"] == 'true' || $this->rawData["zustand_angaben.energiepass.mitwarmwasser"] == '1') ? 'Ja' : '';
        $objFilterTemplate->gueltigBis = $this->rawData["zustand_angaben.energiepass.gueltig_bis"];
        $objFilterTemplate->energy = $energie;
        $objFilterTemplate->heizungsart = $heizung;
        $objFilterTemplate->energietraeger = $energietraeger;
        $objFilterTemplate->energyClass = $eClass;
        if ($this->rawData["zustand_angaben.energiepass.primaerenergietraeger"]) {
            $objFilterTemplate->primaererEnergietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys'][$this->rawData["zustand_angaben.energiepass.primaerenergietraeger"]] ? $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys'][$this->rawData["zustand_angaben.energiepass.primaerenergietraeger"]] : $this->rawData["zustand_angaben.energiepass.primaerenergietraeger"];
        } else {
            $objFilterTemplate->primaererEnergietraeger = $energietraeger;
        }

        if ($jahrgang == "2008") {
            $objFilterTemplate->jahrgang = "vor 2014";
        } elseif ($jahrgang == "2014") {
            $objFilterTemplate->jahrgang = "ab 1.5.2014";
        } elseif ($jahrgang == "ohne") {
            $objFilterTemplate->jahrgang = "es liegt kein E-pass vor";
        } elseif ($jahrgang == "nicht noetig") {
            $objFilterTemplate->jahrgang = "nicht nötig";
        } else {
            $objFilterTemplate->jahrgang = $jahrgang;
        }

        $objFilterTemplate->ausstelldatum = $ausstelldatum;
        $objFilterTemplate->gebaeudeart = $gebaeudeart;

        echo $objFilterTemplate->parse();
    }
}
