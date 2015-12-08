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
    'attributes' => [
        [
            'attribute' => 'images',
            'uploadPath' => '@common/images',
            'sizes' => [[100, 100], [200, 250]]
        ],
        [
            'attribute' => 'file',
            'uploadPath' => '@common/files',
        ],
    ]
]
```

Don't forget enable enctype into ActiveForm

```php
<?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>
    <?= $form->field($model, 'images[]')->fileInput(['multiple' => true]) ?>
    <?= $form->field($model, 'file')->fileInput() ?>
<?php ActiveForm::end(); ?>
```
