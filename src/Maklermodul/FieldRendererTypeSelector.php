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

use Pdir\MaklermodulBundle\Maklermodul\FieldRenderer\Attachment;
use Pdir\MaklermodulBundle\Maklermodul\FieldRenderer\Date;
use Pdir\MaklermodulBundle\Maklermodul\FieldRenderer\Flag;
use Pdir\MaklermodulBundle\Maklermodul\FieldRenderer\Heading;
use Pdir\MaklermodulBundle\Maklermodul\FieldRenderer\Number;
use Pdir\MaklermodulBundle\Maklermodul\FieldRenderer\Text;

class FieldRendererTypeSelector
{
    private $key;
    private $value;

    /**
     * @var FieldTranslator
     */
    private $translator;

    public function __construct($key, $value, FieldTranslator $translator)
    {
        $this->key = $key;
        $this->value = $value;
        $this->translator = $translator;
    }

    /**
     * Holt den Index eines Wertes.
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
     * Methode für die Rückgabe als Text.
     *
     * @return Text
     */
    public function asText()
    {
        return new Text($this->getKey(), $this->getValue(), $this->translator);
    }

    /**
     * Methode für die Rückgabe von Ganz- und Dezimalzahlen.
     *
     * In den Klammern wird der Wert eingetragen, wieviele Nachkommastellen angezeigt werden sollen.
     *
     * @param int $digestCount
     *
     * @return Number
     */
    public function asNumber($digestCount = 0)
    {
        return new Number($this->getKey(), $this->getValue(), $this->translator, $digestCount);
    }

    /**
     * Methode für die Rückgabe als Datum.
     *
     * Ausgbeformat dd.mm.yyyy
     *
     * @return Date
     */
    public function asDate()
    {
        return new Date($this->getKey(), $this->getValue(), $this->translator);
    }

    /**
     * Methode für die Rückgabe von Ja/Nein.
     *
     * @param $yesValue
     * @param $noValue
     *
     * @return Flag
     */
    public function asFlag($yesValue = 'true', $noValue = 'false')
    {
        return new Flag($this->getKey(), $this->getValue(), $this->translator, $yesValue, $noValue);
    }

    /**
     * Methode für die Rückgabe eines Bildes.
     *
     * @return Attachment
     */
    public function asAttachment()
    {
        return new Attachment($this->getKey(), $this->getValue(), $this->translator);
    }

    /**
     * Methode für die Rückgabe als Überschrift.
     *
     * @param string $tag html tag
     *
     * @return Heading
     */
    public function asHeading($tag = 'div')
    {
        return new Heading($this->getKey(), $this->getValue(), $this->translator, $tag);
    }
}
