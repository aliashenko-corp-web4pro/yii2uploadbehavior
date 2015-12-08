Yii2 Upload Files Behavior
==========================
Upload files via Behavior

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist andrewljashenko/yii2-upload-files-behavior "*"
```

or add

```
"andrewljashenko/yii2-upload-files-behavior": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
[
                'class' => UploadFilesBehavior::className(), //Behavior class
                'attributes' => ['images', 'files'],         //Fields into your Model
                'uploadPath' => '@app/images',               //Upload directory
                'sizes' => [[100, 100], [250, 200]]          //Sizes of images. Stored in @app/images/100x100/YOUR_IMAGE
            ]
```