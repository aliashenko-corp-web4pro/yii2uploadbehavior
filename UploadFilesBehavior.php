<?php

namespace andrewljashenko\behaviors;

use yii\base\Behavior;
use yii\db\BaseActiveRecord;
use yii\helpers\Url;
use yii\web\UploadedFile;
use Gregwar\Image\Image;

/**
 * Class UploadFilesBehavior
 * @package andrewljashenko\behaviors
 */
class UploadFilesBehavior extends Behavior
{
    /**
     * @var string Upload files directory.
     */
    public $uploadPath = '@common';
    /**
     * @var array Array of attributes
     */
    public $attributes = array();

    /**
     * Initialize Behavior.
     */
    public function init(){}

    /**
     * Connect ActiveRecord Events.
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
     * Upload Files Before Save.
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
                            $url = Url::to($path . DIRECTORY_SEPARATOR . $file->name);
                            $file->saveAs($url);
                            // If it is image then crop it.
                            if (getimagesize($url) && isset($attr['sizes'])) {
                                $this->crop($attr, $file, Url::to($path));
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * @param $attr
     * @param $file
     * @param $path
     */
    private function crop($attr, $file, $path)
    {
        // Is isset Sizes of the image.
        if (isset($attr['sizes']) && $attr['sizes']) {
            foreach ($attr['sizes'] as $size) {
                Image::open($path . DIRECTORY_SEPARATOR . $file->name)
                    ->cropResize($size[0], $size[1])
                    ->save($path . DIRECTORY_SEPARATOR . $size[0] . 'x' . $size[1] . DIRECTORY_SEPARATOR . $file->name);
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