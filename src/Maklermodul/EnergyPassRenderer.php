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

/*
 * Class for energy pass renderer
 *
 * @property string $baujahr
 * @property string $stromwert
 * @property string $waermewert
 * @property string $mitwarmwasser
 * @property string $gueltigBis
 */

use Contao\FrontendTemplate;

class EnergyPassRenderer
{
    const LIST_PREFIX = 'epass.';
    const DEFAULT_TEMPLATE = 'makler_detail_energy_pass';

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

    private $data;
    private $rawData;
    private $translator;
    private $ausstattungRawData;

    public function __construct($rawData, FieldTranslator $translator, $ausstattungRawData)
    {
        $this->rawData = $rawData;
        $this->translator = $translator;
        $this->ausstattungRawData = $ausstattungRawData;
        $this->parseRawData();
    }

    // render energy pass
    public function render(): void
    {
        //epart
        if ((isset($this->rawData['zustand_angaben.energiepass.epart']) && 'VERBRAUCH' === $this->rawData['zustand_angaben.energiepass.epart']) || (isset($this->rawData['immoscout.buildingEnergyRatingType']) && 'ENERGY_CONSUMPTION' === $this->rawData['immoscout.buildingEnergyRatingType'])) {
            $epart = 'Verbrauch';
        } elseif ((isset($this->rawData['zustand_angaben.energiepass.epart']) && 'BEDARF' === $this->rawData['zustand_angaben.energiepass.epart']) || (isset($this->rawData['immoscout.buildingEnergyRatingType']) && 'ENERGY_REQUIRED' === $this->rawData['immoscout.buildingEnergyRatingType'])) {
            $epart = 'Bedarf';
        } else {
            $epart = 'Nicht notwendig';
        }

        // heizung & energietraeger
        if (isset($this->ausstattungRawData['ausstattung.heizungsart.@OFEN'])) {
            if ('true' === $this->ausstattungRawData['ausstattung.heizungsart.@OFEN'] || '1' === $this->ausstattungRawData['ausstattung.heizungsart.@OFEN']) {
                $heizung = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.heizungsart.@OFEN'];
            }
        }

        if (isset($this->ausstattungRawData['ausstattung.heizungsart.@ETAGE'])) {
            if ('true' === $this->ausstattungRawData['ausstattung.heizungsart.@ETAGE'] || '1' === $this->ausstattungRawData['ausstattung.heizungsart.@ETAGE']) {
                $heizung = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.heizungsart.@ETAGE'];
            }
        }

        if (isset($this->ausstattungRawData['ausstattung.heizungsart.@ZENTRAL'])) {
            if ('true' === $this->ausstattungRawData['ausstattung.heizungsart.@ZENTRAL'] || '1' === $this->ausstattungRawData['ausstattung.heizungsart.@ZENTRAL']) {
                $heizung = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.heizungsart.@ZENTRAL'];
            }
        }

        if (isset($this->ausstattungRawData['ausstattung.heizungsart.@FERN'])) {
            if ('true' === $this->ausstattungRawData['ausstattung.heizungsart.@FERN'] || '1' === $this->ausstattungRawData['ausstattung.heizungsart.@FERN']) {
                $heizung = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.heizungsart.@FERN'];
            }
        }

        if (isset($this->ausstattungRawData['ausstattung.heizungsart.@FUSSBODEN'])) {
            if ('true' === $this->ausstattungRawData['ausstattung.heizungsart.@FUSSBODEN'] || '1' === $this->ausstattungRawData['ausstattung.heizungsart.@FUSSBODEN']) {
                $heizung = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.heizungsart.@FUSSBODEN'];
            }
        }

        if (isset($this->ausstattungRawData['ausstattung.befeuerung.@OEL'])) {
            if ('true' === $this->ausstattungRawData['ausstattung.befeuerung.@OEL'] || '1' === $this->ausstattungRawData['ausstattung.befeuerung.@OEL']) {
                $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@OEL'];
            }
        }

        if (isset($this->ausstattungRawData['ausstattung.befeuerung.@GAS'])) {
            if ('true' === $this->ausstattungRawData['ausstattung.befeuerung.@GAS'] || '1' === $this->ausstattungRawData['ausstattung.befeuerung.@GAS']) {
                $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@GAS'];
            }
        }

        if (isset($this->ausstattungRawData['ausstattung.befeuerung.@ELEKTRO'])) {
            if ('true' === $this->ausstattungRawData['ausstattung.befeuerung.@ELEKTRO'] || '1' === $this->ausstattungRawData['ausstattung.befeuerung.@ELEKTRO']) {
                $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@ELEKTRO'];
            }
        }

        if (isset($this->ausstattungRawData['ausstattung.befeuerung.@ALTERNATIV'])) {
            if ('true' === $this->ausstattungRawData['ausstattung.befeuerung.@ALTERNATIV'] || '1' === $this->ausstattungRawData['ausstattung.befeuerung.@ALTERNATIV']) {
                $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@ALTERNATIV'];
            }
        }

        if (isset($this->ausstattungRawData['ausstattung.befeuerung.@SOLAR'])) {
            if ('true' === $this->ausstattungRawData['ausstattung.befeuerung.@SOLAR'] || '1' === $this->ausstattungRawData['ausstattung.befeuerung.@SOLAR']) {
                $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@SOLAR'];
            }
        }

        if (isset($this->ausstattungRawData['ausstattung.befeuerung.@ERDWAERME'])) {
            if ('true' === $this->ausstattungRawData['ausstattung.befeuerung.@ERDWAERME'] || '1' === $this->ausstattungRawData['ausstattung.befeuerung.@ERDWAERME']) {
                $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@ERDWAERME'];
            }
        }

        if (isset($this->ausstattungRawData['ausstattung.befeuerung.@LUFTWP'])) {
            if ('true' === $this->ausstattungRawData['ausstattung.befeuerung.@LUFTWP'] || '1' === $this->ausstattungRawData['ausstattung.befeuerung.@LUFTWP']) {
                $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@LUFTWP'];
            }
        }

        if (isset($this->ausstattungRawData['ausstattung.befeuerung.@FERN'])) {
            if ('true' === $this->ausstattungRawData['ausstattung.befeuerung.@FERN'] || '1' === $this->ausstattungRawData['ausstattung.befeuerung.@FERN']) {
                $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@FERN'];
            }
        }

        if (isset($this->ausstattungRawData['ausstattung.befeuerung.@BLOCK'])) {
            if ('true' === $this->ausstattungRawData['ausstattung.befeuerung.@BLOCK'] || '1' === $this->ausstattungRawData['ausstattung.befeuerung.@BLOCK']) {
                $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@BLOCK'];
            }
        }

        if (isset($this->ausstattungRawData['ausstattung.befeuerung.@WASSER-ELEKTRO'])) {
            if ('true' === $this->ausstattungRawData['ausstattung.befeuerung.@WASSER-ELEKTRO'] || '1' === $this->ausstattungRawData['ausstattung.befeuerung.@WASSER-ELEKTRO']) {
                $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@WASSER-ELEKTRO'];
            }
        }

        if (isset($this->ausstattungRawData['ausstattung.befeuerung.@PELLET'])) {
            if ('true' === $this->ausstattungRawData['ausstattung.befeuerung.@PELLET'] || '1' === $this->ausstattungRawData['ausstattung.befeuerung.@PELLET']) {
                $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@PELLET'];
            }
        }

        if (isset($this->ausstattungRawData['ausstattung.befeuerung.@KOHLE'])) {
            if ('true' === $this->ausstattungRawData['ausstattung.befeuerung.@KOHLE'] || '1' === $this->ausstattungRawData['ausstattung.befeuerung.@KOHLE']) {
                $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@KOHLE'];
            }
        }

        if (isset($this->ausstattungRawData['ausstattung.befeuerung.@HOLZ'])) {
            if ('true' === $this->ausstattungRawData['ausstattung.befeuerung.@HOLZ'] || '1' === $this->ausstattungRawData['ausstattung.befeuerung.@HOLZ']) {
                $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@HOLZ'];
            }
        }

        if (isset($this->ausstattungRawData['ausstattung.befeuerung.@FLUESSIGGAS'])) {
            if ('true' === $this->ausstattungRawData['ausstattung.befeuerung.@FLUESSIGGAS'] || '1' === $this->ausstattungRawData['ausstattung.befeuerung.@FLUESSIGGAS']) {
                $energietraeger = $GLOBALS['TL_LANG']['makler_modul_mplus']['field_keys']['ausstattung.befeuerung.@FLUESSIGGAS'];
            }
        }

        // energy
        $wertklasse = $this->rawData['zustand_angaben.energiepass.wertklasse']?? null;
        $jahrgang = $this->rawData['zustand_angaben.energiepass.jahrgang']?? null;
        $ausstelldatum = $this->rawData['zustand_angaben.energiepass.ausstelldatum']?? null;
        $gebaeudeart = $this->rawData['zustand_angaben.energiepass.gebaeudeart']?? null;
        $energie = $this->rawData['zustand_angaben.energiepass.energieverbrauchkennwert']?? null;

        if (isset($this->rawData['zustand_angaben.energiepass.endenergiebedarf'])) {
            $energie = $this->rawData['zustand_angaben.energiepass.endenergiebedarf'];
        }

        if (null !== $energie) {
            $energie = str_replace(',', '.', (string) $energie);
        }

        if (null === $wertklasse) {
            if ($energie >= 0 && $energie < 30) {
                $eClass = 'A+';
            }

            if ($energie >= 30 && $energie < 50) {
                $eClass = 'A';
            }

            if ($energie >= 50 && $energie < 75) {
                $eClass = 'B';
            }

            if ($energie >= 75 && $energie < 100) {
                $eClass = 'C';
            }

            if ($energie >= 100 && $energie < 130) {
                $eClass = 'D';
            }

            if ($energie >= 130 && $energie < 160) {
                $eClass = 'E';
            }

            if ($energie >= 160 && $energie < 200) {
                $eClass = 'F';
            }

            if ($energie >= 200 && $energie < 250) {
                $eClass = 'G';
            }

            if ($energie >= 250) {
                $eClass = 'H';
            }
        } else {
            $eClass = $wertklasse;
        }

        $objFilterTemplate = new FrontendTemplate(self::DEFAULT_TEMPLATE);

        $objFilterTemplate->epart = $epart;
        $objFilterTemplate->baujahr = $this->rawData['zustand_angaben.baujahr']?? null;;
        $objFilterTemplate->stromwert = $this->rawData['zustand_angaben.energiepass.stromwert']?? null;
        $objFilterTemplate->waermewert = $this->rawData['zustand_angaben.energiepass.waermewert']?? null;

        if (isset($this->rawData['zustand_angaben.energiepass.mitwarmwasser'])) {
            $objFilterTemplate->mitwarmwasser = 'true' === $this->rawData['zustand_angaben.energiepass.mitwarmwasser'] || '1' === $this->rawData['zustand_angaben.energiepass.mitwarmwasser'] ? 'Ja' : '';
        }

        $objFilterTemplate->gueltigBis = $this->rawData['zustand_angaben.energiepass.gueltig_bis']?? null;;
        $objFilterTemplate->energy = $energie?? null;
        $objFilterTemplate->heizungsart = $heizung?? null;
        $objFilterTemplate->energietraeger = $energietraeger?? null;
        $objFilterTemplate->energyClass = $eClass?? null;

        if (isset($this->rawData['zustand_angaben.energiepass.primaerenergietraeger'])) {
            $objFilterTemplate->primaererEnergietraeger = $this->rawData['zustand_angaben.energiepass.primaerenergietraeger'];
        }

        if (isset($this->rawData['zustand_angaben.energiepass.endenergiebedarf.primaerenergietraeger'])) {
            $objFilterTemplate->primaererEnergietraeger = $this->rawData['zustand_angaben.energiepass.endenergiebedarf.primaerenergietraeger'];
        }

        if ('2008' === $jahrgang) {
            $objFilterTemplate->jahrgang = 'vor 2014';
        } elseif ('2014' === $jahrgang) {
            $objFilterTemplate->jahrgang = 'ab 1.5.2014';
        } elseif ('ohne' === $jahrgang) {
            $objFilterTemplate->jahrgang = 'es liegt kein E-pass vor';
        } elseif ('nicht noetig' === $jahrgang) {
            $objFilterTemplate->jahrgang = 'nicht nötig';
        } else {
            $objFilterTemplate->jahrgang = $jahrgang;
        }

        $objFilterTemplate->ausstelldatum = $ausstelldatum?? null;
        $objFilterTemplate->gebaeudeart = $gebaeudeart?? null;

        echo $objFilterTemplate->parse();
    }

    protected function parseRawData(): void
    {
        $this->data = [];

        foreach ($this->rawData as $key => $value) {
            $parsedKey = str_replace(self::LIST_PREFIX, '', $key);

            if(strpos($parsedKey, '.') !== false) {
                [$id, $parsedKey] = explode('.', $parsedKey, 2);
            }

            $this->setData($id, $parsedKey, $value);
        }
    }

    private function setData($id, $key, $value): void
    {
        $id = self::LIST_PREFIX.$id;

        if (!isset($this->data[$id])) {
            $this->data[$id] = [];
        }

        $this->data[$id]["$id.$key"] = $value;
    }
}
