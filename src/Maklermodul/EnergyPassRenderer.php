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

use Pdir\MaklermodulBundle\Maklermodul\Domain\Model\Estate;
use Pdir\MaklermodulBundle\Maklermodul\FieldRenderer;

class EnergyPassRenderer {

    const LIST_PREFIX = 'epass.';
    const DEFAULT_TEMPLATE = 'makler_detail_energy_pass';

    private $data;
    private $rawData;
    private $translator;
    private $ausstattungRawData;

    public function __construct($rawData, FieldTranslator $translator, $ausstattungRawData) {
        $this->rawData = $rawData;
        $this->translator = $translator;
        $this->ausstattungRawData = $ausstattungRawData;
        $this->parseRawData();
    }

    protected function parseRawData() {
        $this->data = array();

        foreach ($this->rawData as $key => $value)  {
            $parsedKey = str_replace(self::LIST_PREFIX, '', $key);
            list($id, $parsedKey) = explode('.', $parsedKey, 2);
            $this->setData($id, $parsedKey, $value);
        }
    }

    private function setData($id, $key, $value) {
        $id = self::LIST_PREFIX . $id;

        if (!isset($this->data[$id])) {
            $this->data[$id] = array();
        }

        $this->data[$id]["$id.$key"] = $value;
    }

    // render energy pass
    public function render() {
        //epart
        if($this->rawData["zustand_angaben.energiepass.epart"] == 'VERBRAUCH')
            $epart = 'Verbrauch';
        else if($this->rawData["zustand_angaben.energiepass.epart"] == 'BEDARF')
            $epart = 'Bedarf';
        else
            $epart = 'Nicht notwendig';

        // heizung & energietraeger
        if(	$this->ausstattungRawData["ausstattung.heizungsart.@OFEN"] == 'true' )
            $heizung = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.heizungsart.@OFEN'];
        if(	$this->ausstattungRawData["ausstattung.heizungsart.@ETAGE"] == 'true' )
            $heizung = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.heizungsart.@ETAGE'];
        if(	$this->ausstattungRawData["ausstattung.heizungsart.@ZENTRAL"] == 'true' )
            $heizung = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.heizungsart.@ZENTRAL'];
        if(	$this->ausstattungRawData["ausstattung.heizungsart.@FERN"] == 'true' )
            $heizung = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.heizungsart.@FERN'];
        if(	$this->ausstattungRawData["ausstattung.heizungsart.@FUSSBODEN"] == 'true' )
            $heizung = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.heizungsart.@FUSSBODEN'];

        if(	$this->ausstattungRawData["ausstattung.befeuerung.@OEL"] == 'true' )
            $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@OEL'];
        if(	$this->ausstattungRawData["ausstattung.befeuerung.@GAS"] == 'true' )
            $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@GAS'];
        if(	$this->ausstattungRawData["ausstattung.befeuerung.@ELEKTRO"] == 'true' )
            $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@ELEKTRO'];
        if(	$this->ausstattungRawData["ausstattung.befeuerung.@ALTERNATIV"] == 'true' )
            $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@ALTERNATIV'];
        if(	$this->ausstattungRawData["ausstattung.befeuerung.@SOLAR"] == 'true' )
            $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@SOLAR'];
        if(	$this->ausstattungRawData["ausstattung.befeuerung.@ERDWAERME"] == 'true' )
            $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@ERDWAERME'];
        if(	$this->ausstattungRawData["ausstattung.befeuerung.@LUFTWP"] == 'true' )
            $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@LUFTWP'];
        if(	$this->ausstattungRawData["ausstattung.befeuerung.@FERN"] == 'true' )
            $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@FERN'];
        if(	$this->ausstattungRawData["ausstattung.befeuerung.@BLOCK"] == 'true' )
            $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@BLOCK'];
        if(	$this->ausstattungRawData["ausstattung.befeuerung.@WASSER-ELEKTRO"] == 'true' )
            $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@WASSER-ELEKTRO'];
        if(	$this->ausstattungRawData["ausstattung.befeuerung.@PELLET"] == 'true' )
            $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@PELLET'];
        if(	$this->ausstattungRawData["ausstattung.befeuerung.@KOHLE"] == 'true' )
            $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@KOHLE'];
        if(	$this->ausstattungRawData["ausstattung.befeuerung.@HOLZ"] == 'true' )
            $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@HOLZ'];
        if(	$this->ausstattungRawData["ausstattung.befeuerung.@FLUESSIGGAS"] == 'true' )
            $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@FLUESSIGGAS'];

        // energy
        if($this->rawData["zustand_angaben.energiepass.endenergiebedarf.waermewert"])
            $energie = $this->rawData["zustand_angaben.energiepass.endenergiebedarf.waermewert"];
        elseif($this->rawData["zustand_angaben.energiepass.energieverbrauchkennwert"])
            $energie = $this->rawData["zustand_angaben.energiepass.energieverbrauchkennwert"];
        else
            $energie = $this->rawData["zustand_angaben.energiepass.endenergiebedarf"];
        $energie = str_replace(",", ".", $energie);

        if($energie >= 0 AND $energie < 30)
            $eClass = "A+";
        if($energie >= 30 AND $energie < 50)
            $eClass = "A";
        if($energie >= 50 AND $energie < 75)
            $eClass = "B";
        if($energie >= 75 AND $energie < 100)
            $eClass = "C";
        if($energie >= 100 AND $energie < 130)
            $eClass = "D";
        if($energie >= 130 AND $energie < 160)
            $eClass = "E";
        if($energie >= 160 AND $energie < 200)
            $eClass = "F";
        if($energie >= 200 AND $energie < 250)
            $eClass = "G";
        if($energie >= 250)
            $eClass = "H";

        $objFilterTemplate = new \FrontendTemplate(self::DEFAULT_TEMPLATE);

        $objFilterTemplate->epart = $epart;
        $objFilterTemplate->baujahr = $this->rawData["zustand_angaben.baujahr"];
        $objFilterTemplate->stromwert = $this->rawData["zustand_angaben.energiepass.endenergiebedarf.stromwert"];
        $objFilterTemplate->waermewert = $this->rawData["zustand_angaben.energiepass.endenergiebedarf.waermewert"];
        $objFilterTemplate->mitwarmwasser = ($this->rawData["zustand_angaben.energiepass.mitwarmwasser"] == 'true') ? 'Ja' : '';
        $objFilterTemplate->gueltigBis = $this->rawData["zustand_angaben.energiepass.gueltig_bis"];
        $objFilterTemplate->energy = $energie;
        $objFilterTemplate->heizungsart = $heizung;
        $objFilterTemplate->energietraeger = $energietraeger;
        $objFilterTemplate->energyClass = $eClass;

        echo $objFilterTemplate->parse();
    }
}
