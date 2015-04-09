<?php

namespace mongosoft\xmlpipe;

/**
 * Class SphinxXMLFeed
 *
 * @see http://sphinxsearch.com/docs/current/xmlpipe2.html
 *
 * @author Alexander Mohorev <dev.mohorev@gmail.com>
 */
class XMLFeed extends \XMLWriter
{
    /**
     * @var array the sphinx full-text fields.
     *
     * @see http://sphinxsearch.com/docs/current.html#xmlpipe2
     */
    private $_fields = [];
    /**
     * @var array the sphinx attributes.
     *
     * The following special options are supported:
     *
     * - name: string, the element name that should be treated as an attribute
     *   in the subsequent documents.
     * - type: string, the attribute type. Possible values are "int", "bigint",
     *   "timestamp", "bool", "float", "multi" and "json".
     * - bits: integer, the bit size for "int" attribute type. Valid values are 1 to 32.
     * - default: mixed, the default value for this attribute that should be used
     *   if the attribute's element is not present in the document.
     *
     * @see http://sphinxsearch.com/docs/current.html#xmlpipe2
     */
    private $_attributes = [];


    public function __construct()
    {
        // Store the xml tree in memory.
        $this->openMemory();
        // Toggle indentation on/off.
        $this->setIndent(true);
    }

    public function setFields($fields)
    {
        $this->_fields = $fields;
    }

    public function setAttributes($attributes)
    {
        $this->_attributes = $attributes;
    }

    public function addDocument($doc)
    {
        $this->startElement('sphinx:document');
        $this->writeAttribute('id', $doc['id']);
        // Unset the id key since that is an element attribute.
        unset($doc['id']);

        foreach ($doc as $key => $value) {
            $this->startElement($key);
            $this->text($value);
            $this->endElement();
        }

        $this->endElement();
        echo $this->outputMemory();
    }

    public function beginOutput()
    {
        $this->startDocument('1.0', 'UTF-8');
        $this->startElement('sphinx:docset');
        $this->startElement('sphinx:schema');

        // Add fields to the schema.
        foreach ($this->_fields as $field) {
            $this->startElement('sphinx:field');
            $this->writeAttribute('name', $field);
            $this->endElement();
        }

        // Add attributes to the schema.
        foreach ($this->_attributes as $attributes) {
            $this->startElement('sphinx:attr');
            foreach ($attributes as $key => $value) {
                $this->writeAttribute($key, $value);
            }
            $this->endElement();
        }

        // End sphinx:schema
        $this->endElement();
        echo $this->outputMemory();
    }

    public function endOutput()
    {
        // End sphinx:docset
        $this->endElement();
        echo $this->outputMemory();
    }
}
