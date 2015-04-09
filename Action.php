<?php

namespace mongosoft\xmlpipe;

use yii\base\InvalidConfigException;
use yii\di\Instance;

/**
 * @author Alexander Mohorev <dev.mohorev@gmail.com>
 */
class Action extends \yii\base\Action
{
    /**
     * @var BaseXmlPipe|array|string the xmlpipe document object
     * or the application component ID of the xmlpipe document object.
     */
    public $document;
    /**
     * @var callable a PHP callable that will be called when running an action to determine
     * if the current user has the permission to execute the action. If not set, the access
     * check will not be performed. The signature of the callable should be as follows,
     *
     * ```php
     * function ($action) {
     *     throw new ForbiddenHttpException;
     * }
     * ```
     */
    public $checkAccess;


    /**
     * Initializes the action.
     * @throws InvalidConfigException if the xmlpipe document does not exist.
     */
    public function init()
    {
        if ($this->document === null) {
            throw new InvalidConfigException(get_class($this) . '::$document must be set.');
        }

        $this->document = Instance::ensure($this->document, BaseXmlPipe::className());
    }

    /**
     * Runs the action.
     */
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        $this->document->render();
    }
}
