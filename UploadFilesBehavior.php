<?php

namespace andrewljashenko\behaviors;

use yii\base\Behavior;
use yii\db\BaseActiveRecord;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * Class UploadFilesBehavior
 * @package andrewljashenko\behaviors
 */
class UploadFilesBehavior extends Behavior
{
    /**
     * @var string
     */
    public $uploadPath = '@common';
    /**
     * @var array
     */
    public $attributes = array();
    /**
     * @var array
     */
    public $sizes = array();
    /**
     * Initialize Behavior
     */
    public function init(){}

    /**
     * Connect ActiveRecord Events
     *
     * @return array
     * @version 1.0
     */
    public function events()
    {
        $event[BaseActiveRecord::EVENT_BEFORE_INSERT] = 'beforeSave';
        $event[BaseActiveRecord::EVENT_BEFORE_UPDATE] = 'beforeSave';
        return $event;
    }

    /**
     * Upload Files Before Save
     *
     * @return void
     * @version 1.0
     */
    public function beforeSave()
    {
        $owner = $this->owner;
        // If attributes are not empty.
        if ($this->attributes) {
            foreach ($this->attributes as $attr) {
                // If Isset attribute in our Model.
                if (isset($owner->{$attr['attribute']}) && $path = $this->getPath($attr)) {
                    $files = UploadedFile::getInstances($owner, $attr['attribute']);
                    // If array with files is not empty.
                    if ($files) {
                        foreach ($files as $file) {
                            $file->saveAs(Url::to($path . DIRECTORY_SEPARATOR . $file->name));
                        }
                    }
                }
            }
        }
    }

    /**
     * Return Upload Dir Path
     *
     * @param $attr
     * @return bool
     */
    private function getPath($attr)
    {
        $path = isset($attr['uploadPath']) && !empty($attr['uploadPath']) ?
                $attr['uploadPath'] : $this->uploadPath;
        return !empty($path) ? $path : false;
    }
}