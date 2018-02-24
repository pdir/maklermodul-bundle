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
        if ($this->rawData["zustand_angaben.energiepass.epart"] == 'VERBRAUCH')
            $this->epart = 'Verbrauch';
        else if ($this->rawData["zustand_angaben.energiepass.epart"] == 'BEDARF')
            $this->epart = 'Bedarf';
        else
            $this->epart = 'Nicht notwendig';

        // heizung & energietraeger
        if ($this->ausstattungRawData["ausstattung.heizungsart.@OFEN"] == 'true')
            $this->heizung = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.heizungsart.@OFEN'];
        if ($this->ausstattungRawData["ausstattung.heizungsart.@ETAGE"] == 'true')
            $this->heizung = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.heizungsart.@ETAGE'];
        if ($this->ausstattungRawData["ausstattung.heizungsart.@ZENTRAL"] == 'true')
            $this->heizung = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.heizungsart.@ZENTRAL'];
        if ($this->ausstattungRawData["ausstattung.heizungsart.@FERN"] == 'true')
            $this->heizung = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.heizungsart.@FERN'];
        if ($this->ausstattungRawData["ausstattung.heizungsart.@FUSSBODEN"] == 'true')
            $this->heizung = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.heizungsart.@FUSSBODEN'];

        if ($this->ausstattungRawData["ausstattung.befeuerung.@OEL"] == 'true')
            $this->energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@OEL'];
        if ($this->ausstattungRawData["ausstattung.befeuerung.@GAS"] == 'true')
            $this->energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@GAS'];
        if ($this->ausstattungRawData["ausstattung.befeuerung.@ELEKTRO"] == 'true')
            $this->energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@ELEKTRO'];
        if ($this->ausstattungRawData["ausstattung.befeuerung.@ALTERNATIV"] == 'true')
            $this->energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@ALTERNATIV'];
        if ($this->ausstattungRawData["ausstattung.befeuerung.@SOLAR"] == 'true')
            $this->energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@SOLAR'];
        if ($this->ausstattungRawData["ausstattung.befeuerung.@ERDWAERME"] == 'true')
            $this->energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@ERDWAERME'];
        if ($this->ausstattungRawData["ausstattung.befeuerung.@LUFTWP"] == 'true')
            $this->energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@LUFTWP'];
        if ($this->ausstattungRawData["ausstattung.befeuerung.@FERN"] == 'true')
            $this->energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@FERN'];
        if ($this->ausstattungRawData["ausstattung.befeuerung.@BLOCK"] == 'true')
            $this->energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@BLOCK'];
        if ($this->ausstattungRawData["ausstattung.befeuerung.@WASSER-ELEKTRO"] == 'true')
            $this->energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@WASSER-ELEKTRO'];
        if ($this->ausstattungRawData["ausstattung.befeuerung.@PELLET"] == 'true')
            $this->energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@PELLET'];
        if ($this->ausstattungRawData["ausstattung.befeuerung.@KOHLE"] == 'true')
            $this->energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@KOHLE'];
        if ($this->ausstattungRawData["ausstattung.befeuerung.@HOLZ"] == 'true')
            $this->energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@HOLZ'];
        if ($this->ausstattungRawData["ausstattung.befeuerung.@FLUESSIGGAS"] == 'true')
            $this->energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@FLUESSIGGAS'];

        // energy
        if ($this->rawData["zustand_angaben.energiepass.endenergiebedarf.waermewert"])
            $this->energie = $this->rawData["zustand_angaben.energiepass.endenergiebedarf.waermewert"];
        elseif ($this->rawData["zustand_angaben.energiepass.energieverbrauchkennwert"])
            $this->energie = $this->rawData["zustand_angaben.energiepass.energieverbrauchkennwert"];
        else
            $this->energie = $this->rawData["zustand_angaben.energiepass.endenergiebedarf"];
        $this->energie = str_replace(",", ".", $this->energie);

        if ($this->energie >= 0 AND $this->energie < 30)
            $this->eClass = "A+";
        if ($this->energie >= 30 AND $this->energie < 50)
            $this->eClass = "A";
        if ($this->energie >= 50 AND $this->energie < 75)
            $this->eClass = "B";
        if ($this->energie >= 75 AND $this->energie < 100)
            $this->eClass = "C";
        if ($this->energie >= 100 AND $this->energie < 130)
            $this->eClass = "D";
        if ($this->energie >= 130 AND $this->energie < 160)
            $this->eClass = "E";
        if ($this->energie >= 160 AND $this->energie < 200)
            $this->eClass = "F";
        if ($this->energie >= 200 AND $this->energie < 250)
            $this->eClass = "G";
        if ($this->energie >= 250)
            $this->eClass = "H";

        $objFilterTemplate = new \FrontendTemplate(self::DEFAULT_TEMPLATE);

        $arrData = array(
            'epart' => $this->epart,
            'baujahr' => $this->rawData["zustand_angaben.baujahr"],
            'stromwert' => $this->rawData["zustand_angaben.energiepass.endenergiebedarf.stromwert"],
            'waermewert' => $this->rawData["zustand_angaben.energiepass.endenergiebedarf.waermewert"],
            'mitwarmwasser' => ($this->rawData["zustand_angaben.energiepass.mitwarmwasser"] == 'true') ? 'Ja' : '',
            'gueltigBis' => $this->rawData["zustand_angaben.energiepass.gueltig_bis"],
            'energy' => $this->energie,
            'heizungsart' => $this->heizung,
            'energietraeger' => $this->energietraeger,
            'energyClass' => $this->eClass
        );

        $objFilterTemplate->setData($arrData);

        echo $objFilterTemplate->parse();
    }
}
