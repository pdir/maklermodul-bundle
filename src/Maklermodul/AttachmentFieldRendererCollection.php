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

use Pdir\MaklermodulBundle\Maklermodul\FieldRenderer\Attachment;

class AttachmentFieldRendererCollection implements \Iterator {
    const LIST_PREFIX = 'anhaenge.anhang.#';

    private $data;
    private $rawData;
    private $translator;

    /**
     * @var array[\MaklerModulMplus\FieldRenderer\Attachment]
     */
    private $attachments;

    public function __construct($rawData, FieldTranslator $translator) {
        $this->rawData = $rawData;
        $this->translator = $translator;

        $this->parseRawData();
        $this->sortData();
        $this->createAttachmentObjects();
    }

    protected function parseRawData() {
        $this->data = array();

        foreach ($this->rawData as $key => $value)  {
            $parsedKey = str_replace(self::LIST_PREFIX, '', $key);
            list($id, $parsedKey) = explode('.', $parsedKey, 2);
            $this->setData($id, $parsedKey, $value);
        }
    }

    protected function sortData() {
        $sorted = array();
        $keys = array_keys($this->data);
        natcasesort ($keys);

        foreach ($keys as $key) {
            $sorted[$key] = $this->data[$key];
        }
        $this->data = $sorted;
    }

    private function setData($id, $key, $value) {
        $id = self::LIST_PREFIX . $id;

        if (!isset($this->data[$id])) {
            $this->data[$id] = array();
        }

        $this->data[$id]["$id.$key"] = $value;
    }

    private function createAttachmentObjects() {
        $this->attachments = array();

        foreach ($this->data as $key => $value) {
            $this->attachments[] = new Attachment($key, $value, $this->translator);
        }
    }

    /**
     * Methode zu holen der Bilddaten aus der Objektbeschreibung.
     *
     * @return mixed
     */
    public function getRawData() {
        return $this->rawData;
    }

    /**
     * Methode holt das erste im Array vorkommende Bild.
     *
     * @return Attachment
     */
    public function first() {
        $key = "";
        $value = "";

        $iterator = new \ArrayIterator($this->data);
        $iterator->rewind();

        if ($iterator->valid()) {
            $key = $iterator->key();
            $value = $iterator->current();
        };

        return new Attachment($key, $value, $this->translator, 'TITELBILD');
    }

    /**
     * Setzt den Startpunkt des ersten Bildes und alle folgende Bilder werden geladen.
     *
     * @param $int
     *
     * @return \Iterator
     */
    public function startAt($int) {
        $returnValue = new \ArrayIterator($this->attachments);

        if ($int > 0) {
            $tmpArray = array_slice($this->attachments, $int);
            $returnValue = new \ArrayIterator($tmpArray);
        }

        return $returnValue;
    }

    /**
     * Setzt den Bereich der Bilder, die angezeigt werden sollen.
     *
     * $offset - Startpunkt des ersten Bildes
     * $endOffset - Anzahl der Bilder
     *
     * @param $offset
     * @param $endOffset
     *
     * @return \ArrayIterator
     */
    public function range($offset, $endOffset) {
        return new \ArrayIterator(array_slice($this->attachments, $offset, $endOffset));
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     *
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current() {
        // @@todo: Implement current() method.
        return '';
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     *
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next() {
        // @todo: Implement next() method.
        return '';
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     *
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key() {
        // @todo: Implement key() method.
        return '';
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     *
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid() {
        // @todo: Implement valid() method.
        return '';
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     *
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind() {
        // @todo: Implement rewind() method.
        return '';
    }

}
