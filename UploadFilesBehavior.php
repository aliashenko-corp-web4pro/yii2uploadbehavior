<?php

namespace andrewljashenko\behaviors;

use yii\base\Behavior;
use yii\db\BaseActiveRecord;
use yii\helpers\Url;
use yii\web\UploadedFile;

class UploadFilesBehavior extends Behavior
{
    public $uploadPath = '@common';
    public $attributes;
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
     * @version 1.0
     */
    public function beforeSave()
    {
        $owner = $this->owner;
        //if attributes are not empty
        if ($this->attributes) {
            foreach ($this->attributes as $attr) {
                //if Isset attribute in our Model
                if (isset($owner->{$attr['attribute']}) && $path = $this->getPath($attr)) {
                    $files = UploadedFile::getInstances($owner, $attr['attribute']);
                    //if array with files is not empty
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