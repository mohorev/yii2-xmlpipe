Sphinx XmlPipe Extension for Yii 2
==================================

This extension provides an easy way to create xmlpipe2 data source for the Sphinx search engine.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require --prefer-dist mongosoft/yii2-xmlpipe "*"
```

or add

```json
"mongosoft/yii2-xmlpipe": "*"
```

to the `require` section of your `composer.json` file.

Usage
-----

### XmlPipe document

```php
<?php

namespace app\models;

use mongosoft\xmlpipe\BaseXmlPipe;

class XmlPipeDocument extends BaseXmlPipe
{
    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'name',
            'description',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            ['name' => 'type', 'type' => 'int', 'bits' => 16],
            ['name' => 'name', 'type' => 'string'],
            ['name' => 'description', 'type' => 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function addDocuments()
    {
        $query = Items::find()
            ->select(['type', 'name', 'description'])
            ->asArray();

        foreach ($query->each() as $item) {
            $this->pushDocument([
                'type' => $item['type'],
                'name' => $item['full_name'],
                'description' => $item['description'],
            ]);
        }
    }
}
```

### Controller

```php
<?php

namespace app\controllers;

use yii\web\Controller;

class TestController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'xmlpipe' => [
                'class' => 'mongosoft\xmlpipe\Action',
                'document' => 'app\models\XmlPipeDocument',
            ],
        ];
    }
}
```

### Sphinx config

Add the following lines to the configuration file (sphinx.conf)

```php
source myindex
{
    type               = xmlpipe2
    xmlpipe_command    = curl http://localhost/test/xmlpipe
}

index myindex
{
    source             = myindex
    path               = /var/path/to/index/myindex
    docinfo            = extern
    morphology         = lemmatize_ru
    charset_type       = utf-8
}
```
