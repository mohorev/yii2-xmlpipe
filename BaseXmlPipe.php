<?php

namespace mongosoft\xmlpipe;

use yii\base\Component;

/**
 * @author Alexander Mohorev <dev.mohorev@gmail.com>
 */
abstract class BaseXmlPipe extends Component
{
    /**
     * @var integer the document ID.
     * Note: All document IDs must be unique unsigned non-zero integer numbers.
     */
    private $_inc = 1;
    /**
     * @var XMLFeed
     */
    private $_xmlFeed;


    /**
     * Returns the list of fields.
     * @return array list of fields.
     */
    abstract public function fields();

    /**
     * Returns the list of attributes.
     * @return array list of attributes.
     */
    abstract public function attributes();

    /**
     * Adds the documents.
     */
    abstract public function addDocuments();

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->_xmlFeed = new XMLFeed();
        $this->_xmlFeed->setFields($this->fields());
        $this->_xmlFeed->setAttributes($this->attributes());
    }

    /**
     * @param array $document
     */
    public function pushDocument($document)
    {
        if (!isset($document['id'])) {
            $document['id'] = $this->_inc++;
        }
        $this->_xmlFeed->addDocument($document);
    }

    /**
     * Renders the xmlpipe document.
     */
    public function render()
    {
        $this->_xmlFeed->beginOutput();

        $this->addDocuments();

        $this->_xmlFeed->endOutput();
    }
}
